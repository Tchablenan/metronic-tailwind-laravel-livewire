<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Activation de Compte - CMO VISTAMD</title>
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
            border-bottom: 2px solid #1075B9;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1075B9;
        }
        .content {
            margin: 20px 0;
        }
        .appointment-details {
            background: #f9f9f9;
            border-left: 4px solid #1075B9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-row {
            margin: 8px 0;
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
        .credentials {
            background: #f0f7ff;
            border: 1px solid #1075B9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credentials-title {
            font-weight: 600;
            color: #1075B9;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            background: #1075B9;
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
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
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
            <p style="margin: 0; color: #666; font-size: 14px;">Gestion Médicale Intelligente</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #1075B9;">Bienvenue, {{ $patient->first_name }}!</h2>
            
            <p>Votre compte utilisateur a été créé avec succès dans le système <strong>CMO VISTAMD</strong>.</p>
            
            <p>Un rendez-vous vous a également été programmé. Voici les détails:</p>

            <!-- Appointment Details -->
            <div class="appointment-details">
                <div class="detail-row">
                    <span class="label">📅 Date:</span>
                    <span class="value">{{ $appointment->appointment_date->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">⏰ Heure:</span>
                    <span class="value">{{ $appointment->appointment_time->format('H:i') }}</span>
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
            </div>

            <!-- Credentials -->
            <div class="credentials">
                <div class="credentials-title">🔐 Vos identifiants d'accès:</div>
                <p style="margin: 8px 0;"><strong>Email:</strong> {{ $patient->email }}</p>
                <p style="margin: 8px 0;"><strong>Mot de passe temporaire:</strong> <code style="background: white; padding: 2px 6px; border-radius: 3px;">{{ $appointment->contact_method ?? '*****' }}</code></p>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> Pour des raisons de sécurité, veuillez changer votre mot de passe lors de votre première connexion.
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Se connecter</a>
            </div>

            <p><strong>Prochaines étapes:</strong></p>
            <ul>
                <li>Accédez à votre compte avec vos identifiants</li>
                <li>Changez votre mot de passe</li>
                <li>Complétez votre profil médical</li>
                <li>Confirmez votre rendez-vous</li>
            </ul>

            <p>Si vous avez des questions, n'hésitez pas à <strong>nous contacter</strong> au <strong>+225 XX XX XX XX</strong> ou par email.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2026 CMO VISTAMD - Tous droits réservés</p>
            <p>Grand-Bassam, Côte d'Ivoire</p>
            <p>Cet email a été envoyé automatiquement. Veuillez ne pas y répondre directement.</p>
        </div>
    </div>
</body>
</html>
