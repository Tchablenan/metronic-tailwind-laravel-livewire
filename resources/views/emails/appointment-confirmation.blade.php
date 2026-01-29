<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Rendez-vous - CMO VISTAMD</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1075B9;
        }
        .check-icon {
            color: #22c55e;
            font-size: 48px;
            margin: 10px 0;
        }
        .content {
            margin: 20px 0;
        }
        .appointment-details {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-row {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #333;
        }
        .patient-notes {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .patient-notes-title {
            font-weight: 600;
            color: #b45309;
            margin-bottom: 8px;
        }
        .btn {
            display: inline-block;
            background: #22c55e;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .important {
            background: #fee2e2;
            border: 1px solid #ef5350;
            color: #c62828;
            padding: 12px;
            border-radius: 4px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🏥 CMO VISTAMD</div>
            <div class="check-icon">✓</div>
            <h1 style="color: #22c55e; margin: 0; font-size: 20px;">Rendez-vous Confirmé!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Bonjour {{ $patient->first_name }},</h2>

            <p>Votre rendez-vous a bien été confirmé dans notre système. Voici les détails de votre consultation:</p>

            <!-- Appointment Details -->
            <div class="appointment-details">
                <div class="detail-row">
                    <span class="label">📅 Date:</span>
                    <span class="value"><strong>{{ $appointment->appointment_date->format('l d/m/Y', 'fr_FR') }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="label">⏰ Heure:</span>
                    <span class="value"><strong>{{ $appointment->appointment_time->format('H:i') }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="label">⏱️ Durée:</span>
                    <span class="value">{{ $appointment->duration }} minutes</span>
                </div>
                <div class="detail-row">
                    <span class="label">🏥 Type:</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">📍 Lieu:</span>
                    <span class="value">
                        @switch($appointment->location)
                            @case('cabinet') Cabinet Médical @break
                            @case('domicile') À domicile @break
                            @case('urgence') Urgences @break
                            @case('hopital') Hôpital @break
                            @default {{ $appointment->location }}
                        @endswitch
                    </span>
                </div>
                @if($appointment->doctor)
                <div class="detail-row">
                    <span class="label">👨‍⚕️ Médecin:</span>
                    <span class="value">Dr. {{ $appointment->doctor->full_name }}</span>
                </div>
                @endif
                @if($appointment->reason)
                <div class="detail-row">
                    <span class="label">📝 Motif:</span>
                    <span class="value">{{ $appointment->reason }}</span>
                </div>
                @endif
            </div>

            <!-- Patient Notes -->
            @if($appointment->patient_notes)
            <div class="patient-notes">
                <div class="patient-notes-title">💬 Message du médecin:</div>
                <p style="margin: 0;">{{ $appointment->patient_notes }}</p>
            </div>
            @endif

            <!-- Important Info -->
            <div class="important">
                <strong>⚠️ Important:</strong> Si vous ne pouvez pas vous présenter à ce rendez-vous, veuillez <strong>nous prévenir au moins 24 heures à l'avance</strong>.
            </div>

            <p><strong>Ce qu'il faut apporter:</strong></p>
            <ul>
                <li>Votre pièce d'identité (CNI ou passeport)</li>
                <li>Votre carte de santé ou d'assurance maladie (si applicable)</li>
                <li>Tout document médical pertinent (analyses, examens antérieurs, etc.)</li>
                <li>Une liste de vos médicaments actuels</li>
            </ul>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/appointments/' . $appointment->id) }}" class="btn">Voir mon Rendez-vous</a>
            </div>

            <p><strong>Vous avez des questions?</strong></p>
            <p>Contactez-nous:</p>
            <ul>
                <li>📞 Téléphone: +225 XX XX XX XX</li>
                <li>📧 Email: contact@cmovistamd.com</li>
                <li>⏰ Lundi - Vendredi: 8h00 - 18h00</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2026 CMO VISTAMD - Tous droits réservés</p>
            <p>Grand-Bassam, Côte d'Ivoire</p>
            <p style="margin-top: 15px; color: #999; font-style: italic;">
                Cet email a été envoyé automatiquement suite à la confirmation de votre rendez-vous.
                <br>Veuillez ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
