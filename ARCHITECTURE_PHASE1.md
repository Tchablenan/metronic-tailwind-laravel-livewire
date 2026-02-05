# 🏗️ Architecture - Phase 1 : Dashboards Différenciés

## Vue d'ensemble du flux

```
Utilisateur accède à /dashboard
         ↓
    Middleware auth
         ↓
DashboardController::index()
         ↓
    Est-ce un médecin?
    /           \
   OUI           NON
   ↓              ↓
  ✅          Erreur 401
             Redirection login
   ↓
Est-ce un médecin chef?
(is_chief == true)
    /           \
   OUI           NON
   ↓              ↓
CHIEF        REGULAR
DASHBOARD    DASHBOARD
   ↓              ↓
  Vue      demo1/doctor/
dashboard- dashboard.blade.php
chief.     
blade.php  Affiche:
           • Stats personnelles
Affiche:    • RDV du jour (seul ce médecin)
• Stats     • 4 boutons accès rapide
  globales
• RDV tous
  médecins
• Perf par
  médecin
• Demandes
• 6 boutons
  accès
```

---

## Structure des fichiers modifiés

### 1️⃣ DashboardController.php (204 lignes)

```
app/Http/Controllers/DashboardController.php
├── index()
│   └── Vérifier rôle et rediriger
├── doctorDashboard() (privé)
│   ├── Calcul stats perso (4)
│   ├── Récupérer RDV du jour
│   └── Retourner vue régulier
└── chiefDashboard() (privé)
    ├── Calcul stats globales (6)
    ├── Récupérer RDV tous médecins
    ├── Calcul perf médecins
    ├── Récupérer demandes récentes
    └── Retourner vue chef
```

### 2️⃣ Routes Web (2 lignes ajoutées)

```
routes/web.php
└── GET /dashboard
    ├── Middleware: auth
    └── Controller: DashboardController@index
```

### 3️⃣ Vue Médecin Régulier (184 lignes)

```
resources/views/demo1/doctor/dashboard.blade.php
├── En-tête
│   ├── Titre: "Mon Tableau de Bord"
│   ├── Salutation personnalisée
│   └── Date du jour
├── Section 1: 4 cartes stats
│   ├── RDV du jour (bleu)
│   ├── Consultations ce mois (vert)
│   ├── Patients suivis (violet)
│   └── RDV prochains 7j (orange)
├── Section 2: Tableau RDV
│   ├── Colonnes: Heure | Patient | Type | Statut | Actions
│   ├── NO Colonne "Médecin"
│   └── Message vide si aucun RDV
└── Section 3: 4 boutons accès rapides
    ├── Mes Rendez-vous (actif)
    ├── Mes Consultations (actif)
    ├── Mon Planning (désactivé)
    └── Mes Patients (désactivé)
```

### 4️⃣ Vue Médecin Chef (346 lignes)

```
resources/views/demo1/doctor/dashboard-chief.blade.php
├── En-tête
│   ├── Titre: "Tableau de Bord Directeur"
│   ├── Badge "Médecin Chef"
│   ├── Salutation
│   └── Date
├── Section 1: 6 cartes stats globales
│   ├── RDV aujourd'hui (bleu)
│   ├── Consultations ce mois (vert)
│   ├── Demandes en attente (orange)
│   ├── Patients total (violet)
│   ├── Médecins actifs (cyan)
│   └── Taux complétion (dynamique)
├── Section 2: Tableau RDV (tous)
│   ├── Colonnes: Heure | Patient | MÉDECIN | Type | Statut | Actions
│   └── YES Colonne "Médecin"
├── Section 3: Performance par médecin
│   ├── Colonnes: Médecin | RDV | Consultations | Taux | Patients | Actions
│   └── Une ligne par médecin régulier
├── Section 4: Demandes récentes
│   ├── Colonnes: Patient | Service | Statut | Date | Actions
│   └── 5 dernières demandes
└── Section 5: 6 boutons accès rapides
    ├── Tous RDV (actif)
    ├── Demandes (actif)
    ├── Gestion Personnel (actif)
    ├── Mes Consultations (désactivé)
    ├── Statistiques (désactivé)
    └── Paramètres (désactivé)
```

---

## Flux de données

### Dashboard Médecin Régulier

```
doctorDashboard()
    ↓
Récupère $doctorId = Auth::id()
    ↓
Calcule 4 stats:
├── myAppointmentsToday
├── myConsultationsThisMonth
├── myPatientsSeen
└── myUpcomingAppointments
    ↓
Récupère max 10 RDV avec:
├── WHERE doctor_id = $doctorId
├── WHERE appointment_date = today()
├── WITH patient relation
└── ORDER BY appointment_time ASC
    ↓
Retourne vue avec variables:
├── $myAppointmentsToday
├── $myConsultationsThisMonth
├── $myPatientsSeen
├── $myUpcomingAppointments
├── $todayAppointments (Collection)
└── $totalTodayAppointments
    ↓
Affiche vue: demo1.doctor.dashboard
```

### Dashboard Médecin Chef

```
chiefDashboard()
    ↓
Calcule 6 stats globales:
├── allAppointmentsToday (TOUS)
├── allConsultationsThisMonth (TOUTES)
├── pendingRequests
├── totalPatients
├── activeDoctors
└── completionRate (%)
    ↓
Récupère RDV du jour (tous médecins):
├── WHERE appointment_date = today()
├── WITH patient, doctor relations
├── ORDER BY appointment_time ASC
├── LIMIT 10
└── Count total
    ↓
Pour chaque médecin régulier:
├── Count RDV ce mois
├── Count consultations
├── Calculate taux complétion
└── Count patients distincts (completed)
    ↓
Récupère 5 dernières demandes:
├── ServiceRequest::latest()
├── WITH patient relation
└── LIMIT 5
    ↓
Retourne vue avec variables:
├── Stats globales (6)
├── RDV du jour (Collection)
├── Performance médecins (Array)
├── Demandes récentes (Collection)
└── Total demandes (count)
    ↓
Affiche vue: demo1.doctor.dashboard-chief
```

---

## Modèles et Relations utilisées

### User Model
```php
User::where('role', 'doctor')
    ->where('is_chief', true/false)
    ->where('is_active', true)
```

### Appointment Model
```php
Appointment::where('doctor_id', $id)
    ->whereDate('appointment_date', today())
    ->with(['patient', 'doctor'])
    ->orderBy('appointment_time', 'asc')
    
// Accesseurs utilisés:
$appointment->status_label
$appointment->type_label
```

### Consultation Model
```php
Consultation::where('doctor_id', $id)
    ->whereMonth('consultation_date', now()->month)
```

### ServiceRequest Model
```php
ServiceRequest::where('status', 'pending')
    ->orWhere('payment_status', 'pending')
    ->with(['patient'])
```

---

## Design Système

### Color Scheme (Cartes Statistiques)

| Stat | Couleur | Icône |
|------|---------|-------|
| RDV | Bleu (#3B82F6) | calendar |
| Consultations | Vert (#10B981) | stethoscope |
| Patients | Violet (#A855F7) | user |
| Demandes | Orange (#F59E0B) | notepad |
| Médecins | Cyan (#06B6D4) | user-tick |
| Taux | Dynamique | chart-line |

### Responsive Grid

**Dashboard régulier** :
- Mobile (< 768px): 1 colonne
- Tablet (768-1024px): 2 colonnes
- Desktop (> 1024px): 4 colonnes

**Dashboard chef** :
- Mobile: 1 colonne
- Tablet: 2 colonnes
- Desktop: 3 colonnes

### Composants réutilisés

1. **Carte statistique**
   - Bordure gauche colorée
   - Icône avec background coloré
   - Valeur grande
   - Label court
   - Sous-texte optionnel

2. **Tableau**
   - Header gris
   - Hover effect sur lignes
   - Badges pour statuts
   - Lien action simple

3. **Bouton d'accès**
   - Icône + texte
   - Hover effect
   - Actif ou désactivé
   - Badge "Bientôt" optionnel

---

## Variables d'environnement requises

Aucune variable spéciale requise pour cette phase.
Le contrôleur utilise uniquement `Auth::user()` et la base de données existante.

---

## Limitations actuelles

1. **Boutons "Bientôt"** : Les fonctionnalités suivantes sont désactivées :
   - Planning (calendrier)
   - Mes Patients
   - Statistiques détaillées
   - Paramètres

2. **Performances** :
   - Les requêtes ne sont pas cachées
   - Pas de pagination pour "Performance par médecin" (OK si < 50 médecins)
   - Pas de pagination pour "Demandes récentes"

3. **Erreurs** :
   - Pas de gestion spéciale si un utilisateur n'est pas médecin
   - Redirection par défaut vers login

---

## Dépendances

```php
// Models
App\Models\User
App\Models\Appointment
App\Models\Consultation
App\Models\ServiceRequest

// Facades
Illuminate\Support\Facades\Auth
Carbon\Carbon (via use statement)

// Middleware
'auth' (authentification)
```

---

## Next Steps - Phase 2

### Modifications attendues
1. Mettre à jour la navigation/sidebar pour afficher différentes options selon le rôle
2. Masquer/afficher les boutons de menu selon `is_chief`
3. Ajouter validation des permissions pour les routes sensibles

### Nouveaux fichiers à créer
- Middleware pour vérifier le rôle médecin
- Vue navigation spécifique pour chaque rôle

---

## Documentation de référence

- **Laravel Middleware** : https://laravel.com/docs/11.x/middleware
- **Eloquent ORM** : https://laravel.com/docs/11.x/eloquent
- **Blade Templates** : https://laravel.com/docs/11.x/blade

---

*Document généré pour Phase 1 - 4 février 2026*
