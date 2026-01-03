/**
 * ============================================
 * GESTION DES RENDEZ-VOUS
 * ============================================
 */

(function () {
    "use strict";

    /**
     * Initialisation
     */
    document.addEventListener("DOMContentLoaded", function () {
        console.log('✅ Appointments.js chargé');

        initAvailabilityCheck();
        initConfirmAppointment();
        initCancelAppointment();
        initDeleteConfirmation();
    });

    /**
     * Vérifier la disponibilité du créneau (CREATE/EDIT)
     */
    function initAvailabilityCheck() {
        const doctorSelect = document.getElementById('doctor_id');
        const dateInput = document.getElementById('appointment_date');
        const timeInput = document.getElementById('appointment_time');
        const durationSelect = document.getElementById('duration');
        const messageDiv = document.getElementById('availability-message');

        if (!doctorSelect || !dateInput || !timeInput || !durationSelect) return;

        async function checkAvailability() {
            const doctorId = doctorSelect.value;
            const date = dateInput.value;
            const time = timeInput.value;
            const duration = durationSelect.value;

            // Pas de vérification si pas de médecin sélectionné
            if (!doctorId || !date || !time) {
                messageDiv.classList.add('hidden');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) return;

            try {
                const response = await fetch('/appointments/check-availability', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        doctor_id: doctorId,
                        appointment_date: date,
                        appointment_time: time,
                        duration: duration,
                        appointment_id: document.getElementById('appointment_id')?.value // Pour l'édition
                    })
                });

                const data = await response.json();

                messageDiv.classList.remove('hidden');
                if (data.available) {
                    messageDiv.className = 'mt-2 text-xs text-green-600 font-medium';
                    messageDiv.innerHTML = '✓ Créneau disponible';
                } else {
                    messageDiv.className = 'mt-2 text-xs text-red-600 font-medium';
                    messageDiv.innerHTML = '✗ ' + data.message;
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Vérifier à chaque changement
        doctorSelect.addEventListener('change', checkAvailability);
        dateInput.addEventListener('change', checkAvailability);
        timeInput.addEventListener('change', checkAvailability);
        durationSelect.addEventListener('change', checkAvailability);
    }

    /**
     * Confirmer un rendez-vous (AJAX)
     */
    function initConfirmAppointment() {
        const buttons = document.querySelectorAll('.btn-confirm-appointment');

        buttons.forEach(button => {
            button.addEventListener('click', async function() {
                const appointmentId = this.dataset.appointmentId;
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                if (!csrfToken) {
                    alert('Erreur: Token CSRF manquant');
                    return;
                }

                if (!confirm('Confirmer ce rendez-vous ?')) {
                    return;
                }

                try {
                    const response = await fetch(`/appointments/${appointmentId}/confirm`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showFlashMessage(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showFlashMessage(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    showFlashMessage('Erreur de connexion', 'error');
                }
            });
        });
    }

    /**
     * Annuler un rendez-vous (AJAX)
     */
    function initCancelAppointment() {
        const buttons = document.querySelectorAll('.btn-cancel-appointment');

        buttons.forEach(button => {
            button.addEventListener('click', async function() {
                const appointmentId = this.dataset.appointmentId;
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                if (!csrfToken) {
                    alert('Erreur: Token CSRF manquant');
                    return;
                }

                const reason = prompt('Raison de l\'annulation :');
                if (!reason) return;

                try {
                    const response = await fetch(`/appointments/${appointmentId}/cancel`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ cancellation_reason: reason })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showFlashMessage(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showFlashMessage(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    showFlashMessage('Erreur de connexion', 'error');
                }
            });
        });
    }

    /**
     * Confirmation de suppression
     */
    function initDeleteConfirmation() {
        const buttons = document.querySelectorAll('.btn-delete-appointment');

        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?\n\nCette action peut être annulée.')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    /**
     * Afficher un message flash
     */
    function showFlashMessage(message, type = "success") {
        const container = document.getElementById("flash-messages") || createFlashContainer();

        const bgColor = type === "success"
            ? "bg-green-50 border-green-200 text-green-800"
            : "bg-red-50 border-red-200 text-red-800";

        const iconClass = type === "success"
            ? "ki-check-circle text-green-600"
            : "ki-cross-circle text-red-600";

        const alertHTML = `
            <div class="flex items-center gap-3 px-4 py-3 ${bgColor} border rounded-lg shadow-sm mb-3 transition-all">
                <i class="ki-filled ${iconClass}"></i>
                <span class="flex-1 font-medium text-sm">${message}</span>
                <button onclick="this.parentElement.remove()" class="hover:opacity-75 transition-opacity">
                    <i class="ki-filled ki-cross text-sm"></i>
                </button>
            </div>
        `;

        container.insertAdjacentHTML("beforeend", alertHTML);

        setTimeout(() => {
            const alert = container.lastElementChild;
            if (alert) {
                alert.style.transition = "opacity 0.3s";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    }

    function createFlashContainer() {
        const container = document.createElement("div");
        container.id = "flash-messages";
        container.className = "fixed top-20 right-4 z-50 w-96";
        document.body.appendChild(container);
        return container;
    }
})();
