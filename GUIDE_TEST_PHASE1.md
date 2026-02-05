# 🧪 GUIDE DE TEST - Phase 1 : Dashboards Différenciés

## ✅ Checklist de vérification

### 1️⃣ Vérifications techniques

```bash
# Vérifier syntaxe PHP du contrôleur
php -l app/Http/Controllers/DashboardController.php
# Résultat attendu : No syntax errors detected ✅

# Vérifier configuration
php artisan config:cache
# Résultat attendu : Configuration cached successfully ✅

# Vérifier route
php artisan route:list | findstr dashboard
# Résultat attendu : GET|HEAD dashboard ... DashboardController@index
```

---

### 2️⃣ Créer données de test

Connectez-vous à la base de données et exécutez (via tinker ou interface) :

```php
use App\Models\User, App\Models\Appointment, Illuminate\Support\Facades\Hash;

// 1. Créer un médecin régulier
$doctor = User::create([
    'first_name' => 'Adjoua',
    'last_name' => 'N\'Dri',
    'email' => 'doctor.regular@cmovistamd.local',
    'password' => Hash::make('password123'),
    'phone_number' => '+22507654321',
    'role' => 'doctor',
    'is_chief' => false,
    'speciality' => 'Pédiatrie',
    'license_number' => 'CI-MED-2024-003',
    'is_active' => true,
    'email_verified_at' => now(),
]);

// 2. Récupérer un patient
$patient = User::where('role', 'patient')->first();
// Si aucun patient, créez-en un d'abord

// 3. Créer 2 RDV pour ce médecin aujourd'hui
Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'appointment_date' => today(),
    'appointment_time' => '10:00:00',
    'duration' => 30,
    'type' => 'consultation',
    'status' => 'confirmed',
    'reason' => 'Consultation pédiatrique',
    'location' => 'cabinet',
]);

Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'appointment_date' => today(),
    'appointment_time' => '14:00:00',
    'duration' => 30,
    'type' => 'suivi',
    'status' => 'scheduled',
    'reason' => 'Suivi pédiatrique',
    'location' => 'cabinet',
]);

echo "✅ Données créées!";
```

---

### 3️⃣ Test du Dashboard Médecin Régulier

#### Authentification
- **Email** : `doctor.regular@cmovistamd.local`
- **Password** : `password123`

#### Vérifications visuelles

Accédez à `/dashboard` et vérifiez :

- ✅ **Titre** : "Mon Tableau de Bord"
- ✅ **Salutation** : "Bonjour Dr. Adjoua N'Dri"
- ✅ **4 Cartes de statistiques** :
  - Carte 1 : "RDV aujourd'hui" affiche **2**
  - Carte 2 : "Consultations ce mois" affiche un nombre
  - Carte 3 : "Patients suivis" affiche un nombre
  - Carte 4 : "RDV prochains 7j" affiche un nombre

- ✅ **Section "Mes Rendez-vous d'Aujourd'hui"** :
  - Affiche le tableau avec 2 RDV
  - Colonnes visibles : Heure | Patient | Type | Statut | Actions
  - Colonne "Médecin" **N'EST PAS PRÉSENTE** ⚠️
  
- ✅ **RDV affichés** :
  - 10:00 - Patient | consultation | confirmed
  - 14:00 - Patient | suivi | scheduled

- ✅ **4 Boutons d'accès rapides** :
  - "Mes Rendez-vous" (bleu) → clickable
  - "Mes Consultations" (vert) → clickable
  - "Mon Planning" (gris) → badge "Bientôt"
  - "Mes Patients" (gris) → badge "Bientôt"

#### ❌ Points à ne PAS voir
- ❌ Badge "Médecin Chef"
- ❌ Colonne "Médecin" dans le tableau des RDV
- ❌ Cartes globales (RDV tous médecins)
- ❌ Tableau "Performance par Médecin"

---

### 4️⃣ Test du Dashboard Médecin Chef

#### Authentification
- **Email** : `doctor@cmovistamd.local` (ou médecin chef existant)
- **Password** : [votre mot de passe]

#### Vérifications visuelles

Accédez à `/dashboard` et vérifiez :

- ✅ **Titre** : "Tableau de Bord Directeur"
- ✅ **Badge** : "Médecin Chef" visible
- ✅ **Salutation** : "Bonjour Dr. [Nom du chef]"
- ✅ **6 Cartes de statistiques globales** :
  - Carte 1 : "RDV aujourd'hui" (nombre ≥ 2, incluant celui du médecin régulier)
  - Carte 2 : "Consultations ce mois"
  - Carte 3 : "Demandes en attente"
  - Carte 4 : "Patients enregistrés"
  - Carte 5 : "Médecins actifs" (≥ 1, le médecin régulier)
  - Carte 6 : "Taux de complétion" (en %)

- ✅ **Section "RDV du Jour (Tous médecins)"** :
  - Tableau avec **colonne "Médecin"** ✅
  - Affiche au moins 2 RDV (inclut ceux du médecin régulier)
  - Colonne "Médecin" montre "Dr. N'Dri" pour les 2 RDV

- ✅ **Section "Performance par Médecin"** :
  - Tableau affichant le médecin régulier "Adjoua N'Dri"
  - Colonnes : Médecin | RDV ce mois | Consultations | Taux complétion | Patients vus | Actions
  - Affiche les stats du médecin régulier

- ✅ **Section "Demandes Récentes"** :
  - Tableau visible (même si vide)
  - Affiche les 5 dernières demandes de service

- ✅ **6 Boutons d'accès rapides** (3 colonnes) :
  - "Tous les Rendez-vous" → clickable
  - "Demandes de Service" → clickable
  - "Gestion Personnel" → clickable
  - "Mes Consultations" → clickable
  - "Statistiques" → clickable
  - "Paramètres" → clickable

#### ❌ Points à ne PAS voir
- ❌ Titre "Mon Tableau de Bord" (dashboard régulier)
- ❌ Boutons grisés "Bientôt"

---

### 5️⃣ Test des redirections

1. **Médecin régulier accède à /dashboard**
   - ✅ Voit le dashboard régulier (sans badge chef)

2. **Médecin chef accède à /dashboard**
   - ✅ Voit le dashboard chef (avec badge "Médecin Chef")

3. **Utilisateur non authentifié accède à /dashboard**
   - ✅ Redirigé vers `/login`

4. **Utilisateur non médecin accède à /dashboard**
   - ✅ Redirigé ou affiche erreur (selon cas)

---

### 6️⃣ Test des liens de navigation

#### Dashboard régulier

| Bouton | Destination | Résultat |
|--------|-------------|----------|
| Mes Rendez-vous | `/appointments` | Page liste RDV |
| Mes Consultations | `#` | Inactif |
| Mon Planning | `#` | Inactif |
| Mes Patients | `#` | Inactif |

#### Dashboard chef

| Bouton | Destination | Résultat |
|--------|-------------|----------|
| Tous les RDV | `/appointments` | Page liste RDV |
| Demandes | `/service-requests` | Page demandes |
| Gestion Personnel | `/users` | Page utilisateurs |
| Mes Consultations | `#` | Inactif |
| Statistiques | `#` | Inactif |
| Paramètres | `#` | Inactif |

---

### 7️⃣ Test responsive

Vérifier sur différentes tailles d'écran :

- **Mobile (375px)** : Grid 1 colonne
- **Tablet (768px)** : Grid 2 colonnes
- **Desktop (1024px+)** : Grid 4 colonnes (régulier) / 3 colonnes (chef)

---

## 🐛 Dépannage

### Problème : Page blanche
- Vérifier les logs : `storage/logs/laravel.log`
- Vérifier que les vues existent :
  - `resources/views/demo1/doctor/dashboard.blade.php`
  - `resources/views/demo1/doctor/dashboard-chief.blade.php`

### Problème : Route non trouvée
```bash
php artisan route:clear
php artisan config:clear
php artisan route:list | findstr dashboard
```

### Problème : Pas de données affichées
- Vérifier que les RDV sont bien créés pour aujourd'hui
- Vérifier les relations `patient` et `doctor` sur Appointment

### Problème : Mauvais dashboard affiché
- Vérifier la valeur de `is_chief` sur l'utilisateur connecté
- Vérifier qu'aucune exception n'est levée

---

## 📝 Rapport après tests

Après avoir effectué tous les tests, consigner :

- [ ] Syntaxe PHP OK
- [ ] Routes enregistrées
- [ ] Dashboard régulier affiche correctement
- [ ] Dashboard chef affiche correctement
- [ ] Redirection selon rôle fonctionne
- [ ] Responsive OK sur tous appareils
- [ ] Tous les liens fonctionnent

**Problèmes trouvés** :
- [liste]

**Notes additionnelles** :
- [notes]

---

✅ **Prêt pour Phase 2**
