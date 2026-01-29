<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre demande de service</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        .details-section {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #667eea;
            min-width: 150px;
        }
        .value {
            color: #666;
            text-align: right;
            flex: 1;
        }
        .reference-number {
            background-color: #e8f1ff;
            border: 2px solid #667eea;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .reference-number p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .reference-number .number {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }
        .next-steps {
            background-color: #fef9f0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .next-steps h3 {
            color: #ff9800;
            margin-top: 0;
            font-size: 16px;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
            color: #666;
            font-size: 14px;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f5f5f5;
            border-top: 1px solid #e0e0e0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
        }
        .button:hover {
            background-color: #5568d3;
        }
        .divider {
            border: 0;
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Demande de service reçue</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Bonjour <strong>{{ $serviceRequest->first_name }} {{ $serviceRequest->last_name }}</strong>,</p>
                <p>Merci d'avoir soumis votre demande de service. Nous avons bien reçu votre demande et nous vous contacterons dans les plus brefs délais.</p>
            </div>

            <!-- Reference Number -->
            <div class="reference-number">
                <p>Numéro de référence de votre demande</p>
                <div class="number">#{{ str_pad($serviceRequest->id, 8, '0', STR_PAD_LEFT) }}</div>
                <p>Conservez ce numéro pour toute correspondance future</p>
            </div>

            <!-- Request Details -->
            <div class="details-section">
                <div class="detail-row">
                    <span class="label">Type de service:</span>
                    <span class="value">
                        @switch($serviceRequest->service_type)
                            @case('appointment')
                                Rendez-vous médical
                                @break
                            @case('home_visit')
                                Visite à domicile
                                @break
                            @case('emergency')
                                Urgence
                                @break
                            @case('transport')
                                Transport
                                @break
                            @case('consultation')
                                Consultation
                                @break
                            @default
                                {{ $serviceRequest->service_type }}
                        @endswitch
                    </span>
                </div>
                @if($serviceRequest->urgency)
                <div class="detail-row">
                    <span class="label">Urgence:</span>
                    <span class="value">
                        @switch($serviceRequest->urgency)
                            @case('low')
                                Basse
                                @break
                            @case('medium')
                                Moyenne
                                @break
                            @case('high')
                                Élevée
                                @break
                        @endswitch
                    </span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="label">Email:</span>
                    <span class="value">{{ $serviceRequest->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Téléphone:</span>
                    <span class="value">{{ $serviceRequest->phone_number }}</span>
                </div>
                @if($serviceRequest->preferred_date)
                <div class="detail-row">
                    <span class="label">Date préférée:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($serviceRequest->preferred_date)->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>Prochaines étapes</h3>
                <ul>
                    <li>Notre équipe examinera votre demande dans les 24 heures</li>
                    <li>Nous vous contacterons à l'email ou au téléphone fourni pour confirmer les détails</li>
                    <li>Un rendez-vous sera fixé selon votre disponibilité</li>
                    <li>Vous recevrez une notification de confirmation avant la date prévue</li>
                </ul>
            </div>

            <p style="color: #666; font-size: 14px; margin-top: 20px;">
                Si vous avez des questions ou si vous souhaitez modifier votre demande, n'hésitez pas à nous contacter en mentionnant votre numéro de référence.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 5px 0;">© {{ date('Y') }} CMO VISTAMD - Centre Médical Opérationnel</p>
            <p style="margin: 5px 0;">Tous droits réservés</p>
            <p style="margin: 5px 0;">Cet email a été envoyé automatiquement. Veuillez ne pas répondre à cet email.</p>
        </div>
    </div>
</body>
</html>
