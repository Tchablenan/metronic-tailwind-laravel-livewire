# 🔄 Documentation de la Refactorisation - Healthcare Booking System

## 📋 Vue d'ensemble

Ce document explique la refactorisation effectuée sur le projet de gestion de rendez-vous médicaux (Healthcare Booking System) et établit la nouvelle architecture pour les développements futurs.

**Date** : 28 Janvier 2026  
**Version** : 2.0 (Post-Refactoring)  
**Stack** : Laravel 11 + Livewire 3 + Tailwind CSS 4

---

## 🎯 Qu'est-ce qui a été refactorisé ?

### ❌ Problèmes identifiés (Avant)
1. **AppointmentController** → 749 lignes (trop gros)
2. **Hardcoding** → Statuts/types en tableaux hardcodés
3. **Logique dupliquée** → Patient matching répétée en 3 endroits
4. **TODOs non implémentés** → 4 notifications manquantes
5. **Pas de services** → Logique métier mélangée dans les contrôleurs

### ✅ Solutions apportées

#### **1. Création de 3 Enums** (`app/Enums/`)

**`AppointmentStatus.php`**
```php
enum AppointmentStatus: string
{
    case SCHEDULED = 'scheduled';
    case CONFIRMED = 'confirmed';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';
    
    public function label(): string { /* ... */ }
    public function color(): string { /* ... */ }
    public static function options(): array { /* key-value */ }
}
```

**`AppointmentType.php`**
```php
enum AppointmentType: string
{
    case CONSULTATION = 'consultation';
    case FOLLOWUP = 'followup';
    // ... autres types
    
    public function label(): string { /* FR translations */ }
    public function icon(): string { /* Keen icons */ }
    public static function options(): array { /* ... */ }
}
```

**`UserRole.php`**
```php
enum UserRole: string
{
    case DOCTOR = 'doctor';
    case NURSE = 'nurse';
    case SECRETARY = 'secretary';
    case PATIENT = 'patient';
    case PARTNER = 'partner';
    case HOME_CARE_MEMBER = 'home_care_member';
    
    public function label(): string { /* ... */ }
    public function avatarColor(): string { /* ... */ }
}
```

**Bénéfices** :
- ✅ Type-safe au lieu de strings magiques
- ✅ Labels/couleurs centralisées
- ✅ Facile à ajouter de nouveaux statuts
- ✅ Les vues peuvent appeler `AppointmentStatus::options()`

---

#### **2. Création de 2 Services** (`app/Services/`)

**`PatientMatcherService.php`** - Logique intelligente de matching de patients

```php
public function matchOrCreatePatient(ServiceRequest $sr): array
{
    // 1. Match parfait (email + phone)
    // 2. Match par email (plus fiable)
    // 3. Match par phone (avertissement)
    // 4. Création nouveau patient
    
    return [
        'patient' => $patient,
        'warning' => $warning,
        'created' => $created,
    ];
}

public function createPatientFromServiceRequest(ServiceRequest $sr): User
{
    // Crée patient + token d'activation
}
```

**Avantages** :
- ✅ Logique centralisée (utilisée dans create + store)
- ✅ Gestion intelligente des doublons
- ✅ Réutilisable dans d'autres contrôleurs

**`AppointmentFilterService.php`** - Filtrage centralisé

```php
public function applyFilters(Builder $query, Request $request): Builder
{
    // Applique: search, status, type, date, doctor_id, patient_id
}

public function applyRoleBasedFilters(Builder $query, $user): Builder
{
    // Patients voir seulement leurs RDV
    // Nurses voir seulement les leurs (sauf show_all)
}

public function applySorting(Builder $query): Builder
{
    // order by appointment_date desc, appointment_time desc
}
```

**Avantages** :
- ✅ Réutilisable dans les listes
- ✅ Logique de filtrage explicite
- ✅ Facile de tester

---

#### **3. Création de 3 Notifications** (`app/Notifications/`)

**`NewUserCreatedNotification.php`**
```php
// Envoie email avec mot de passe temporaire
// Implémente le TODO de UsersController::resetPassword()
```

**`AppointmentConfirmationNotification.php`**
```php
// Envoie confirmation de RDV au patient
// Affiche: date, heure, type, médecin, lieu
```

**`ServiceRequestNotification.php`**
```php
// 3 types: 'received' (patient), 'forwarded' (médecin chef), 'converted' (patient)
// Implémente TODOs dans Api/ServiceRequestController et SecretaryServiceRequestController
```

---

#### **4. AppointmentController Refactorisé** (749 → 608 lignes, -19%)

**Avant** : Logique mélangée, pas d'injection de dépendances
```php
class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // 50 lignes de filtres hardcodés...
        if ($request->filled('search')) { ... }
        if ($request->filled('status')) { ... }
        // ...
        $statuses = ['scheduled' => 'Prévu', ...]; // hardcodé
    }
}
```

**Après** : Services injectés, logique claire
```php
class AppointmentController extends Controller
{
    public function __construct(
        AppointmentFilterService $filterService,
        PatientMatcherService $patientMatcher
    ) { }

    public function index(Request $request)
    {
        $query = $this->filterService->applyFilters($query, $request);
        $query = $this->filterService->applyRoleBasedFilters($query, Auth::user());
        $query = $this->filterService->applySorting($query);
        
        $statuses = AppointmentStatus::options(); // depuis l'Enum
        $types = AppointmentType::options();
    }
    
    public function create(Request $request)
    {
        if ($request->has('service_request_id')) {
            $match = $this->patientMatcher->matchOrCreatePatient($sr);
        }
    }
}
```

---

### 🔧 Fichiers modifiés

| Fichier | Avant | Après | Changement |
|---------|-------|-------|-----------|
| **AppointmentController.php** | 749 lignes | 608 lignes | Refactorisé avec services |
| **UsersController.php** | TODO non implanté | Notification implémentée | Email mot de passe |
| **SecretaryServiceRequestController.php** | TODO non implanté | Notification implémentée | Email médecin chef |
| **Api/ServiceRequestController.php** | 2 TODOs | Notifications + email | Confirmations complètes |

### ✨ Fichiers créés

```
app/
├── Enums/
│   ├── AppointmentStatus.php ✨
│   ├── AppointmentType.php ✨
│   └── UserRole.php ✨
├── Services/
│   ├── PatientMatcherService.php ✨
│   └── AppointmentFilterService.php ✨
└── Notifications/
    ├── NewUserCreatedNotification.php ✨
    ├── AppointmentConfirmationNotification.php ✨
    └── ServiceRequestNotification.php ✨
```

---

## 📐 Nouvelle Architecture

### Architecture générale

```
┌─────────────────────────────────────────────────────────┐
│           CONTRÔLEURS (Routes HTTP/AJAX)                │
├─────────────────────────────────────────────────────────┤
│ AppointmentController, UsersController, etc.            │
└────────────────┬────────────────────────────────────────┘
                 │ Injection de dépendances
                 ↓
┌─────────────────────────────────────────────────────────┐
│            SERVICES (Logique métier)                    │
├─────────────────────────────────────────────────────────┤
│ AppointmentFilterService, PatientMatcherService        │
└────────────────┬────────────────────────────────────────┘
                 │
                 ├─────────────────────────────┐
                 ↓                             ↓
        ┌────────────────┐          ┌──────────────────┐
        │ MODÈLES        │          │ ENUMS + TRAITS   │
        │ (Eloquent)     │          │ (Type-safe)      │
        │ Appointment    │          │ AppointmentStatus│
        │ ServiceRequest │          │ AppointmentType  │
        │ User           │          │ UserRole         │
        └────────────────┘          └──────────────────┘
                 │
                 ├─────────────────────────────┐
                 ↓                             ↓
        ┌────────────────┐          ┌──────────────────┐
        │ POLICIES       │          │ NOTIFICATIONS    │
        │ (Authorization)│          │ (Email/SMS)      │
        │ AppointmentP.  │          │ NewUserCreated   │
        │ UserPolicy     │          │ Confirmation     │
        └────────────────┘          │ ServiceRequest   │
                                    └──────────────────┘
```

### Patterns utilisés

#### **1. Dependency Injection (Services)**
```php
// ✅ Nouveau pattern
public function __construct(
    AppointmentFilterService $filterService,
    PatientMatcherService $patientMatcher
) {
    $this->filterService = $filterService;
    $this->patientMatcher = $patientMatcher;
}
```

#### **2. Enums avec helpers**
```php
// ✅ Au lieu de hardcoding
$statuses = AppointmentStatus::options();
// Retourne: ['scheduled' => 'Prévu', 'confirmed' => 'Confirmé', ...]

foreach (AppointmentStatus::cases() as $status) {
    echo $status->label();  // 'Prévu', 'Confirmé', etc.
    echo $status->color();  // 'warning', 'info', etc.
}
```

#### **3. Service avec business logic**
```php
// Service encapsule la complexité
$match = $this->patientMatcher->matchOrCreatePatient($serviceRequest);
// Retourne: ['patient' => $user, 'warning' => $msg, 'created' => bool]

if (!$match['patient']) {
    $patient = $this->patientMatcher->createPatientFromServiceRequest($sr);
}
```

#### **4. Notifications avec templates**
```php
// Envoie automatiquement
$user->notify(new NewUserCreatedNotification($user, $tempPassword));
// Le contenu du mail est dans la notification (pas hardcodé dans le contrôleur)
```

---

## 🚀 Workflow clé du projet

### Flux 1: Création directe de RDV

```
┌─────────────────────────────────────────────────────────┐
│ Doctor/Secretary remplit formulaire de RDV              │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ AppointmentController::create()                         │
│ - Affiche formulaire avec patients/médecins             │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ AppointmentController::store()                          │
│ - Valide formulaire                                      │
│ - Vérifie conflits d'horaire                            │
│ - Crée Appointment                                       │
│ - Envoie email de confirmation au patient               │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ Patient reçoit email avec détails du RDV                │
└─────────────────────────────────────────────────────────┘
```

### Flux 2: ServiceRequest → RDV (Complex - C'est le core)

```
┌──────────────────────────────────────┐
│ Patient soumet formulaire (API)      │
│ - nom, email, phone, service_type    │
│ - urgency, message, date préférée    │
└──────────────────────────────────────┘
            ↓
     Api/ServiceRequestController::store()
            ↓
┌──────────────────────────────────────┐
│ ServiceRequest créée (pending)        │
│ - Email confirmation au patient       │
│ - Notification aux secrétaires        │
└──────────────────────────────────────┘
            ↓
     Secretary marque comme PAYÉE
     (SecretaryServiceRequestController::markPaid)
            ↓
┌──────────────────────────────────────┐
│ ServiceRequest.payment_status = paid  │
└──────────────────────────────────────┘
            ↓
     Secretary ENVOIE AU MÉDECIN CHEF
     (SecretaryServiceRequestController::sendToDoctor)
            ↓
┌──────────────────────────────────────┐
│ Chief Doctor reçoit notification     │
│ ServiceRequest.sent_to_doctor = true  │
└──────────────────────────────────────┘
            ↓
     Doctor ouvre formulaire de conversion
     (AppointmentController::create with service_request_id)
            ↓
┌──────────────────────────────────────┐
│ PatientMatcherService cherche patient:│
│ 1. Email + phone match                │
│ 2. Email match                        │
│ 3. Phone match (avec avertissement)   │
│ 4. Crée nouveau patient + token       │
└──────────────────────────────────────┘
            ↓
     Doctor CONFIRME ET CRÉE LE RDV
     (AppointmentController::store)
            ↓
┌──────────────────────────────────────┐
│ Appointment créé + statut = scheduled │
│ ServiceRequest.status = converted     │
│ Patient reçoit email d'activation    │
│ (ou email de confirmation s'existe)   │
└──────────────────────────────────────┘
```

### Flux 3: Gestion du RDV (Lifecycle)

```
scheduled (créé)
    ↓
confirm() → confirmed
    ↓
start() → in_progress
    ↓
complete() → completed (avec notes optionnelles)

OU À TOUT MOMENT:
cancel() → cancelled (avec raison d'annulation)
```

---

## 📝 Comment continuer le projet

### Principes de développement post-refactoring

#### ✅ À faire (Patterns respectés)

**1. Nouveau filtre requis ?**
```php
// ❌ NE PAS faire dans le contrôleur
public function index() {
    if ($request->filled('urgency')) {
        $query->where('urgency', $request->urgency);
    }
}

// ✅ FAIRE dans AppointmentFilterService
public function applyFilters(Builder $query, Request $request): Builder
{
    // ... statut existants
    
    if ($request->filled('urgency')) {
        $query->where('urgency', $request->urgency);
    }
    
    return $query;
}
```

**2. Nouveau statut/type pour Appointment ?**
```php
// ❌ NE PAS faire
$types = ['type1' => 'Label 1'];

// ✅ FAIRE dans AppointmentType enum
enum AppointmentType: string
{
    // ...
    case NEW_TYPE = 'new_type';
    
    public function label(): string {
        return match ($this) {
            self::NEW_TYPE => 'Label français',
            // ...
        };
    }
}

// Usage dans contrôleur
$types = AppointmentType::options();
```

**3. Nouvelle notification à envoyer ?**
```php
// ✅ Créer dans app/Notifications/
class MyNewNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    public function via($notifiable): array {
        return ['mail'];
    }
    
    public function toMail($notifiable): MailMessage {
        return (new MailMessage)
            ->subject('Subject')
            ->greeting('Bonjour')
            ->line('Contenu')
            ->action('Bouton', url('/...'));
    }
}

// Usage
$user->notify(new MyNewNotification($data));
```

**4. Nouvelle logique métier complexe ?**
```php
// ✅ Créer un Service
class MyComplexService
{
    public function doComplexThing($param): array {
        // Logique complexe
        return ['result' => $result];
    }
}

// Injection dans contrôleur
public function __construct(MyComplexService $service) {}

// Usage
$result = $this->service->doComplexThing($data);
```

#### ❌ À éviter

- ❌ Logique métier directement dans les contrôleurs
- ❌ Hardcoding de statuts/labels
- ❌ Code dupliqué (utiliser Services)
- ❌ TODOs non documentés (créer issues/notes)
- ❌ Pas de validations/authorizations

---

## 🔮 Prochaines étapes recommandées

### Phase 1: Complétion du paiement
```
TODO:
1. Intégrer Stripe/Paypal pour payment_status
2. Webhooks pour confirmer paiement
3. Email de reçu de paiement (Notification)
4. Dashboard paiements pour secrétaire
```

### Phase 2: Notifications SMS
```
TODO:
1. Intégrer Twilio (service)
2. Créer SmsNotification (base notification)
3. Envoyer SMS rappels 24h avant RDV
4. SMS confirmation après création
```

### Phase 3: Reminders automatiques
```
TODO:
1. Créer Command: php artisan appointments:send-reminders
2. Scheduler (Kernel.php) pour lancer chaque matin
3. Ajouter champs: reminder_sent, reminder_sent_at
4. Tester avec Artisan Tinker
```

### Phase 4: Tests
```
TODO:
1. Tests unitaires: PatientMatcherService
2. Tests features: AppointmentController CRUD
3. Tests permissions: Policies
4. Tests notifications: Email sends correctly
```

### Phase 5: Dashboard
```
TODO:
1. Stats: RDV par jour/mois
2. Revenue tracker (paiements)
3. Calendar view pour médecins
4. Availability slots management
```

---

## 🔗 Fichiers importants à connaître

| Fichier | Rôle | Points clés |
|---------|------|-----------|
| **app/Http/Controllers/AppointmentController.php** | CRUD RDV | Index (filters), Create (form), Store (validation + email) |
| **app/Services/AppointmentFilterService.php** | Filtrage | applyFilters(), applyRoleBasedFilters(), applySorting() |
| **app/Services/PatientMatcherService.php** | Patient matching | matchOrCreatePatient(), createPatientFromServiceRequest() |
| **app/Enums/AppointmentStatus.php** | Statuts | label(), color(), options() |
| **app/Enums/AppointmentType.php** | Types RDV | label(), icon(), options() |
| **app/Models/Appointment.php** | Model RDV | Relations, methods: confirm(), start(), complete(), cancel() |
| **app/Models/ServiceRequest.php** | Modèle demande | Workflow: pending→paid→converted |
| **app/Notifications/** | Emails | 3 notifications: NewUser, Confirmation, ServiceRequest |
| **routes/web.php** | Routes | Resource routes, Group par rôle |
| **database/migrations/** | Schema | 10 migrations cumulatives |

---

## 💾 Commandes utiles

```bash
# Vérifier la structure
php artisan tinker
> App\Enums\AppointmentStatus::options();
> app(App\Services\AppointmentFilterService::class);

# Lancer le serveur
php artisan serve

# Migrer la DB
php artisan migrate

# Seeder utilisateurs de test
php artisan db:seed --class=UserSeeder

# Queue (pour notifications)
php artisan queue:work

# Tests
php artisan test
```

---

## 🎓 Exemple d'extension futur

### Ajouter un nouveau type de notification

**1. Créer notification**
```php
// app/Notifications/AppointmentReminderNotification.php
class AppointmentReminderNotification extends Notification
{
    public function via($notifiable) { return ['mail']; }
    public function toMail($notifiable) { /* ... */ }
}
```

**2. Ajouter dans contrôleur**
```php
// app/Http/Controllers/AppointmentController.php
$appointment->patient->notify(
    new AppointmentReminderNotification($appointment)
);
```

**3. Créer Command si récurrent**
```php
// app/Console/Commands/SendAppointmentReminders.php
class SendAppointmentReminders extends Command
{
    public function handle()
    {
        $appointments = Appointment::whereDate(...)
            ->where('reminder_sent', false)
            ->get();
        
        foreach ($appointments as $apt) {
            $apt->patient->notify(new AppointmentReminderNotification($apt));
            $apt->update(['reminder_sent' => true]);
        }
    }
}
```

**4. Ajouter dans Scheduler**
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('appointments:send-reminders')
        ->dailyAt('08:00');
}
```

---

## 📞 Support et questions

Pour continuer le développement :

1. **Respecter les patterns** établis (Services, Enums, Notifications)
2. **Documenter les TODOs** dans le code ou en issues
3. **Tester localement** avant de committer
4. **Suivre PSR-12** pour le code PHP
5. **Utiliser migrations** pour schema changes
6. **Créer des policies** pour nouvelles autorisations

---

**Dernier update** : 28/01/2026  
**Version refactorisation** : 2.0  
**Status** : ✅ Ready for development
