# 📧 RAPPORT MAIL - État & Configuration

**Date**: 28 Janvier 2026  
**Module**: Emails & Notifications  
**Status**: ⚠️ **PARTIELLEMENT FONCTIONNEL**

---

## ✅ Ce qui fonctionne

### 1. Configuration SMTP
```
✅ MAIL_MAILER: smtp
✅ MAIL_HOST: sandbox.smtp.mailtrap.io (Mailtrap)
✅ MAIL_PORT: 2525
✅ MAIL_USERNAME: Configuré
✅ MAIL_PASSWORD: Configuré
✅ MAIL_FROM_ADDRESS: noreply@cmovistamd.com
✅ MAIL_FROM_NAME: CMO VISTAMD
✅ MAIL_ENCRYPTION: tls
```

**Note**: Mailtrap est un service de test d'emails gratuit en développement. 
En production, remplacer par un vrai service (Sendgrid, AWS SES, etc.)

### 2. Notifications Créées
```
✅ NewUserCreatedNotification
   - Envoie lors création nouvel utilisateur
   - Contient email + mot de passe temporaire
   - Utilise ShouldQueue (va en queue pour traitement asynchrone)

✅ AppointmentConfirmationNotification
   - Envoie lors création/confirmation RDV
   - Détails RDV: date, heure, médecin, lieu
   - Utilise ShouldQueue

✅ ServiceRequestNotification
   - 3 types: 'received', 'forwarded', 'converted'
   - Envoie aux patients et médecins
   - Utilise ShouldQueue
```

### 3. Envoi Direct d'Emails
```
✅ AppointmentController::sendAppointmentEmail()
   - Appel direct à Mail::send()
   - 2 templates: activate-account, appointment-confirmation
   - Avec try/catch pour éviter blocage
   - Synchrone (bloque le request jusqu'au résultat)
```

### 4. Queue Configuration
```
✅ QUEUE_CONNECTION: database
   - Les notifications en queue vont dans la table 'jobs'
   - Permet traitement asynchrone (non-blocking)
```

---

## ✅ Ce qui a été corrigé

### Fichiers Templates Créés

**1. `resources/views/emails/activate-account.blade.php`** ✨
- Template HTML pour activation compte
- Affiche: identifiants, détails RDV, instructions
- Responsive design
- Couleurs branding CMO VISTAMD

**2. `resources/views/emails/appointment-confirmation.blade.php`** ✨
- Template HTML pour confirmation RDV
- Affiche: date, heure, lieu, médecin, motif
- Affiche "patient_notes" si présentes
- Avertissement annulation (24h avant)
- Instructions à apporter

---

## ⚠️ Ce qui ne fonctionne PAS encore

### 1. QUEUE WORKER NON ACTIF
```
❌ php artisan queue:work n'est PAS lancé
   → Les notifications en queue ne seront JAMAIS traitées
   → Les emails ne seront pas envoyés
   
SOLUTION: Lancer dans un terminal séparé:
$ php artisan queue:work --timeout=60
```

**Impact**:
- ❌ NewUserCreatedNotification → NE sera PAS envoyée
- ❌ AppointmentConfirmationNotification → NE sera PAS envoyée (via notify())
- ❌ ServiceRequestNotification → NE sera PAS envoyée

**Mais** ⚠️
- ✅ AppointmentController::sendAppointmentEmail() → S'enverra (Mail::send direct)

---

## 📋 Flux d'Envoi Actuels

### Cas 1: Créer un RDV normal
```
AppointmentController::store()
    ↓
sendAppointmentEmail($appointment, false)
    ↓
Mail::send('emails.appointment-confirmation')
    ↓
📧 Email reçu IMMÉDIATEMENT (synchrone)
```
**Status**: ✅ FONCTIONNE

---

### Cas 2: Créer un RDV + Nouveau Patient
```
AppointmentController::store()
    ↓
sendAppointmentEmail($appointment, true)
    ↓
Mail::send('emails.activate-account')
    ↓
📧 Email reçu IMMÉDIATEMENT (synchrone)
```
**Status**: ✅ FONCTIONNE

---

### Cas 3: Créer Utilisateur (via UsersController::resetPassword)
```
UsersController::resetPassword()
    ↓
$user->notify(new NewUserCreatedNotification($user, $tempPassword))
    ↓
Enqueued en queue (job créé dans table 'jobs')
    ↓
⏸️ BLOQUÉ ICI - queue:work pas actif
    ↓
❌ Email NE sera PAS envoyé
```
**Status**: ❌ NE FONCTIONNE PAS (queue worker manquant)

---

### Cas 4: ServiceRequest reçue (API)
```
Api/ServiceRequestController::store()
    ↓
$secretary->notify(new ServiceRequestNotification($sr, 'received'))
    ↓
Enqueued en queue
    ↓
⏸️ BLOQUÉ - queue:work pas actif
    ↓
❌ Email NE sera PAS envoyé
```
**Status**: ❌ NE FONCTIONNE PAS

---

### Cas 5: ServiceRequest envoyée au Chef
```
SecretaryServiceRequestController::sendToDoctor()
    ↓
$chief->notify(new ServiceRequestNotification($sr, 'forwarded'))
    ↓
Enqueued en queue
    ↓
❌ Email NE sera PAS envoyé
```
**Status**: ❌ NE FONCTIONNE PAS

---

## 🔧 Solutions & Recommandations

### COURT TERME (Dev Local)

**1. Lancer le Queue Worker**
```bash
# Terminal séparé
php artisan queue:work --timeout=60

# Avec auto-restart en développement
php artisan queue:work --timeout=60 --tries=3
```

**2. Vérifier les emails dans Mailtrap**
```
1. Aller à https://mailtrap.io
2. Se connecter avec les credentials dans .env
3. Voir les emails testés (inbox de test)
4. Vérifier HTML rendering, liens, etc.
```

---

### MOYEN TERME (Test/Staging)

**1. Alternative: Envoyer synchrone (plus simple)**

Remplacer `ShouldQueue` par envoi immédiat:
```php
// Avant
class NewUserCreatedNotification extends Notification implements ShouldQueue

// Après
class NewUserCreatedNotification extends Notification
```

**Avantage**: Les emails s'envoient immédiatement  
**Inconvénient**: Bloque les requests HTTP si SMTP lent

---

### LONG TERME (Production)

**1. Service Email Professionnel**

Remplacer Mailtrap par:
- **Sendgrid** (recommandé, 100 emails/jour gratuit)
- **AWS SES** (très bon marché)
- **Postmark** (emails transactionnels)
- **Mailgun** (flexible)

```env
# Exemple Sendgrid
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your_key_here
```

**2. Queue en Arrière Plan**

Utiliser une vraie solution de queue:
- **Redis** (rapide, en mémoire)
- **RabbitMQ** (robuste, enterprise)
- **AWS SQS** (serverless, scalable)

```bash
# Production avec Redis
php artisan queue:work redis --timeout=90 --tries=3
```

---

## 📊 État Actuel du Système

| Fonctionnalité | Status | Notes |
|---|---|---|
| Config SMTP | ✅ OK | Mailtrap configuré |
| Templates Email | ✅ OK | 2 templates créés |
| Envoi Direct (AppointmentController) | ✅ OK | Mail::send synchrone |
| Notifications Créées | ✅ OK | 3 notifications prêtes |
| Queue Configuration | ✅ OK | Database queue setup |
| Queue Worker Actif | ❌ NO | Pas lancé - BLOCKER |
| Emails en Queue | ⏸️ PENDING | Attendent worker |

---

## 🚀 Checklist Avant Production

- [ ] Tester envoi email avec `php artisan queue:work` actif
- [ ] Vérifier réception dans Mailtrap
- [ ] Remplacer Mailtrap par Sendgrid/AWS SES
- [ ] Configurer Redis ou autre queue backend
- [ ] Ajouter email error handling
- [ ] Tester avec vraies adresses (test@gmail.com, etc.)
- [ ] Setup monitoring des failing jobs
- [ ] Setup retry policy (exponentiel backoff)
- [ ] Documenter credentials production

---

## 💡 Pour Tester Maintenant

```bash
# Terminal 1 - Serveur Laravel
php artisan serve

# Terminal 2 - Vite (assets)
npm run dev

# Terminal 3 - Queue Worker (IMPORTANT!)
php artisan queue:work --timeout=60

# Terminal 4 - Tests
php artisan tinker
> dispatch(new App\Jobs\SendEmailJob(...));
```

**Ensuite tester**:
1. Créer un RDV → Email d'activation devrait arriver
2. Créer un utilisateur → Email NewUser devrait arriver (si queue:work actif)
3. Vérifier dans Mailtrap (https://mailtrap.io)

---

## 📞 Troubleshooting

### Erreur: "View [emails.activate-account] not found"
```
✅ CORRIGÉ - Templates créés dans resources/views/emails/
```

### Les emails ne s'envoient pas
```
1. Vérifier queue:work est lancé
   $ ps aux | grep queue:work

2. Vérifier table 'jobs' a des jobs
   $ php artisan tinker
   > DB::table('jobs')->count();

3. Vérifier logs
   $ tail -f storage/logs/laravel.log

4. Vérifier Mailtrap
   $ https://mailtrap.io/inboxes
```

### SMTP Connection Error
```
Vérifier credentials .env:
- MAIL_HOST: sandbox.smtp.mailtrap.io
- MAIL_PORT: 2525
- MAIL_USERNAME: Correct?
- MAIL_PASSWORD: Correct?
```

---

**Dernier Update**: 28/01/2026  
**Status**: ✅ Structure prête, ⏸️ Queue worker manquant  
**Prêt pour**: Test avec queue:work lancé

