/**
 * ============================================
 * GESTION DES UTILISATEURS - PAGES SÉPARÉES
 * ============================================
 * Plus simple, juste pour les interactions client-side
 */

(function () {
    "use strict";

    /**
     * Initialisation
     */
    document.addEventListener("DOMContentLoaded", function () {
        initConditionalFields();
        initAvatarPreview();
        initDeleteConfirmation();
        initToggleStatus();
        initResetPassword();
    });

    /**
     * Champs conditionnels (spécialité/licence pour doctor/nurse)
     */
    function initConditionalFields() {
        const roleSelect = document.getElementById("role");
        if (!roleSelect) return;

        function toggleFields() {
            const role = roleSelect.value;
            const showFields = ["doctor", "nurse"].includes(role);

            const specialityField = document.getElementById("speciality-field");
            const licenseField = document.getElementById("license-field");

            if (specialityField) {
                specialityField.style.display = showFields ? "block" : "none";
                const input = specialityField.querySelector("input");
                if (input) input.required = showFields;
            }

            if (licenseField) {
                licenseField.style.display = showFields ? "block" : "none";
                const input = licenseField.querySelector("input");
                if (input) input.required = showFields;
            }
        }

        roleSelect.addEventListener("change", toggleFields);
        toggleFields(); // Initial check
    }

    /**
     * Preview de l'avatar
     */
    function initAvatarPreview() {
        const avatarInput = document.getElementById("avatar");
        const avatarPreview = document.getElementById("avatar-preview");

        if (!avatarInput || !avatarPreview) return;

        avatarInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                avatarPreview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="${event.target.result}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg"
                             alt="Preview" />
                        <button type="button"
                                onclick="removeAvatarPreview()"
                                class="absolute -top-2 -right-2 kt-btn kt-btn-sm kt-btn-icon kt-btn-danger">
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        });

        // Fonction globale pour supprimer le preview
        window.removeAvatarPreview = function () {
            avatarInput.value = "";
            avatarPreview.innerHTML = `
                <label for="avatar" class="cursor-pointer inline-block">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                        <i class="ki-filled ki-picture text-3xl text-gray-400"></i>
                    </div>
                </label>
            `;
        };
    }

    /**
     * Confirmation de suppression
     */
    function initDeleteConfirmation() {
        const deleteButtons = document.querySelectorAll(".btn-delete-user");

        deleteButtons.forEach((button) => {
            button.addEventListener("click", function (e) {
                const userName = this.dataset.userName;

                if (
                    !confirm(
                        `Êtes-vous sûr de vouloir supprimer l'utilisateur "${userName}" ?\n\nCette action est irréversible.`,
                    )
                ) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    /**
     * Toggle status (AJAX)
     */
    function initToggleStatus() {
        const toggleButtons = document.querySelectorAll(".btn-toggle-status");

        toggleButtons.forEach((button) => {
            button.addEventListener("click", function (e) {
                e.preventDefault();

                const userId = this.dataset.userId;
                const csrfToken = document.querySelector(
                    'meta[name="csrf-token"]',
                ).content;

                fetch(`/users/${userId}/toggle-status`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            showFlashMessage(data.message, "success");
                            // Recharger la page après 1 seconde
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            showFlashMessage(
                                data.message || "Erreur",
                                "error",
                            );
                        }
                    })
                    .catch((error) => {
                        console.error("Erreur:", error);
                        showFlashMessage("Erreur de connexion", "error");
                    });
            });
        });
    }

    /**
     * Reset password (AJAX)
     */
    function initResetPassword() {
        const resetButtons = document.querySelectorAll(".btn-reset-password");

        resetButtons.forEach((button) => {
            button.addEventListener("click", function (e) {
                e.preventDefault();

                const userId = this.dataset.userId;
                const userName = this.dataset.userName;

                if (
                    !confirm(
                        `Voulez-vous vraiment réinitialiser le mot de passe de "${userName}" ?`,
                    )
                ) {
                    return;
                }

                const csrfToken = document.querySelector(
                    'meta[name="csrf-token"]',
                ).content;

                fetch(`/users/${userId}/reset-password`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert(
                                `${data.message}\n\nMot de passe temporaire: ${data.temp_password}\n\nVeuillez le noter et le communiquer à l'utilisateur.`,
                            );
                            showFlashMessage(data.message, "success");
                        } else {
                            showFlashMessage(
                                data.message || "Erreur",
                                "error",
                            );
                        }
                    })
                    .catch((error) => {
                        console.error("Erreur:", error);
                        showFlashMessage("Erreur de connexion", "error");
                    });
            });
        });
    }

    /**
     * Afficher un message flash
     */
/**
 * Afficher un message flash
 */
function showFlashMessage(message, type = "success") {
    const container = document.getElementById("flash-messages") || createFlashContainer();

    // Couleurs Tailwind au lieu de classes Metronic
    const bgColor = type === "success"
        ? "bg-green-50 border-green-200 text-green-800"
        : "bg-red-50 border-red-200 text-red-800";

    const iconClass = type === "success"
        ? "ki-check-circle text-green-600"
        : "ki-cross-circle text-red-600";

    const alertHTML = `
        <div class="flex items-center gap-3 px-4 py-3 ${bgColor} border rounded-lg shadow-sm mb-3 animate-in slide-in-from-top">
            <i class="ki-filled ${iconClass}"></i>
            <span class="flex-1 font-medium">${message}</span>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75 transition-opacity">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>
    `;

    container.insertAdjacentHTML("beforeend", alertHTML);

    // Auto-dismiss après 5 secondes
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
