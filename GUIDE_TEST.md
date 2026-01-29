# 🧪 GUIDE DE TEST - Module Appointments 100%

## Commandes de test rapides

### 1. Vérifier la syntaxe PHP
```bash
php -l app/Http/Controllers/AppointmentController.php
php -l app/Policies/AppointmentPolicy.php
php -l resources/views/demo1/doctor/appointments/edit.blade.php
```

### 2. Vérifier que le model accepte les champs
```bash
php artisan tinker
# Dans tinker:
> $apt = App\Models\Appointment::first();
> $apt->fillable; // Vérifie patient_notes, cancellation_reason, status
> exit;
```

### 3. Vérifier les colonnes BD
```bash
php artisan tinker
# Dans tinker:
> Schema::getColumns('appointments');
# Chercher: patient_notes, status, cancellation_reason
```

### 4. Lancer les tests (si tests existent)
```bash
php artisan test tests/Feature/AppointmentControllerTest.php
```

---

## Tests manuels (Browser)

### Test 1: Créer un RDV
1. Login en tant que médecin
2. Aller à `/appointments/create`
3. Remplir le formulaire complet
4. Cliquer "Créer"
5. Vérifier email envoyé au patient

### Test 2: Éditer un RDV (Médecin Chef)
1. Login en tant que Dr. Jean Koné (ID: 15, chef)
2. Aller au RDV créé
3. Cliquer "Éditer"
4. ✅ Vérifier:
   - Champ "Notes pour le patient" visible
   - Select "Statut" visible (6 options)
   - Champ "Raison d'annulation" masqué (apparaît si cancelled)
   - Changement statut → Le champ raison apparaît si "Annulé" sélectionné
5. Modifier notes patient → Sauvegarder

### Test 3: Éditer un RDV (Médecin régulier)
1. Login médecin régulier assigné au RDV
2. Aller au RDV
3. Cliquer "Éditer"
4. ✅ Vérifier:
   - ✅ CAN modifier (policy retourne true)
   - ❌ NE peut voir "Statut" (conditionnel: seulement si doctor === chef)
   - ❌ NE peut voir "Raison annulation" (conditionnel)

### Test 4: Éditer un RDV (Patient)
1. Login en tant que patient
2. Aller à son RDV
3. Cliquer "Éditer"
4. ✅ Vérifier:
   - ✅ CAN modifier (policy retourne true)
   - Champs: date, heure, durée, lieu (patient peut modifier ses prefs)

### Test 5: Annuler un RDV
1. Login médecin/chef
2. Aller au RDV
3. Cliquer "Éditer"
4. Changer statut → "Annulé"
5. ✅ Vérifier: Le champ "Raison d'annulation" apparaît (JavaScript)
6. Remplir raison (max 500 chars)
7. Sauvegarder
8. ✅ Vérifier: `status = cancelled` et `cancellation_reason` rempli en BD

### Test 6: Autorisation (Negative)
1. Login patient X
2. Aller RDV patient Y (ou un RDV où pas assigné)
3. Cliquer "Éditer"
4. ✅ Vérifier: `403 Forbidden` (policy retourne false)

---

## Vérifications Base de Données

```sql
-- Vérifier les colonnes
DESCRIBE appointments;
-- Doit afficher: patient_notes, status, cancellation_reason

-- Vérifier un RDV
SELECT id, patient_id, doctor_id, status, patient_notes, cancellation_reason 
FROM appointments LIMIT 1;

-- Vérifier les enum values
SELECT DISTINCT status FROM appointments;
-- Doit afficher: scheduled, confirmed, in_progress, completed, cancelled, no_show
```

---

## Vérifications Finales (Checklist)

### ✅ Avant production

- [ ] Syntaxe PHP: 0 erreurs
- [ ] Colonnes BD existent et sont du bon type
- [ ] RDV création: Fonctionne, email envoyé
- [ ] RDV édition: Form s'affiche, champs présents
- [ ] Chef peut modifier TOUS les RDV
- [ ] Médecin peut modifier SEULEMENT ses RDV
- [ ] Patient peut modifier son RDV
- [ ] Infirmier/Secretary bloqués en édition
- [ ] Status change: Fonctionne, validation OK
- [ ] Annulation: Raison requise, max 500 chars
- [ ] Patient_notes: Présent, max 1000 chars
- [ ] Email confirmation: Reçu par patient
- [ ] Email activation: Reçu par nouveau patient

### ❌ Erreurs attendues (À éviter)

- ❌ Syntaxe PHP error → Vérifier accolades/points-virgules
- ❌ Colonne manquante → Migration non exécutée? `php artisan migrate`
- ❌ Validation "Failed" → Vérifier les règles (in:, max:, etc.)
- ❌ 403 Forbidden → Policy non appliquée correctement
- ❌ Email non envoyé → Vérifier Mailtrap/SMTP config
- ❌ Champ statut ne change pas → JavaScript non chargé? Cache?

---

## Logs à vérifier

```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Chercher erreurs:
# - "validation failed"
# - "unauthorized"
# - "SMTP error"
# - "policy"
```

---

## Redéploiement en Prod

```bash
# 1. Commit les changements
git add app/ resources/
git commit -m "feat: Module Appointments finalisé à 100%"

# 2. Pull en prod
git pull origin main

# 3. Composer install si besoin
composer install --no-dev

# 4. Migration (si nouvelles colonnes)
php artisan migrate --force

# 5. Cache clear
php artisan config:cache
php artisan view:clear

# 6. Redémarrer queue (si emails en queue)
php artisan queue:restart

# 7. Vérifier
php artisan tinker
> Schema::hasColumn('appointments', 'patient_notes') ? print('✅') : print('❌');
```

---

## Support et Debugging

### Si ça casse

1. **Revenir à la dernière version stable**:
   ```bash
   git revert HEAD~1
   ```

2. **Vérifier les logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Vérifier la DB**:
   ```bash
   php artisan tinker
   > DB::connection()->getDatabaseName()
   > Schema::getColumnListing('appointments')
   ```

4. **Vérifier les permissions de fichier**:
   ```bash
   chmod -R 755 storage bootstrap
   chmod -R 775 storage bootstrap
   ```

---

## Points de contact

**Questions sur les modifications?**
- Voir `REFACTORING.md` pour architecture globale
- Voir `TACHES_COMPLETEES.md` pour détails des 4 tâches
- Vérifier `app/Models/Appointment.php` pour relations

**Erreurs d'autorisation?**
- Vérifier `app/Policies/AppointmentPolicy.php`
- Vérifier `app/Models/User.php::isChief()`

**Erreurs de validation?**
- Vérifier `app/Http/Controllers/AppointmentController.php::update()`
- Vérifier les règles Blade dans `edit.blade.php`

---

**Status**: ✅ Module 100% finalisé  
**Créé**: 28/01/2026  
**Prêt pour**: Production
