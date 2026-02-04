# RAPPORT - Ajout Infos Medicales & Securite ServiceRequest

**Date** : 04/02/2026  
**Duree totale** : n/c

---

##  Tache 1 : Migration
- [x] Migration creee : database/migrations/2026_02_04_000001_add_medical_info_to_service_requests_table.php
- [ ] Migration executee (a confirmer)
- [ ] Verification Tinker (a faire)
- Colonnes ajoutees (19) : 7 triage, 6 assurance, 6 examens
  - Triage : temperature, tension (sys/dia), poids, taille, allergies, medicaments
  - Assurance : has_insurance, compagnie, n police, taux, plafond, expiration
  - Examens : has_previous_exams, type, nom, etablissement, date, fichier

---

##  Tache 2 : Modele ServiceRequest
- [x] Champs medicaux/assurance/examens dans `$fillable`
- [x] Casts : dates, decimaux, booleens
- [x] Accesseurs : `getFormattedBloodPressureAttribute()`, `getBmiAttribute()`, `hasExamFile()`, `getExamFileUrlAttribute()`, `getPreviousExamTypeLabel()`
- [x] SoftDeletes + scopes de filtrage
- [x] Syntaxe PHP OK

Fichier : app/Models/ServiceRequest.php

---

##  Tache 3 : Formulaire secretaire (create)
- Section Triage : 7 champs + IMC auto (JS `calculateBMI()`)
- Section Assurance : checkbox + 6 champs conditionnels (`toggleInsuranceFields()`)
- Section Examens : info box + checkbox + 5 champs + upload (`toggleExamFields()`)
- Gestion `old()`/erreurs complete

Fichier : resources/views/demo1/secretary/service-requests/create.blade.php

---

##  Tache 4 : Validation / Upload
- Regles `required_if` pour assurance/examens
- Upload examen -> disque `public`, dossier `exam_results`, chemin stocke `previous_exam_file_path`

Fichier : app/Http/Controllers/Demo1/SecretaryServiceRequestController.php

---

##  Tache 5 : Vues details (show)
- Sections conditionnelles : Triage, Assurance, Examens (download si fichier)
- IMC affiche avec couleurs
- Bouton  Envoyer au medecin chef  (secretaire)
- Raison de rejet affichee si status = rejected
- Bouton Modifier visible uniquement pour la secretaire
- Formulaire de rejet medecin : affiche directement avec raison obligatoire

Fichiers :
- resources/views/demo1/secretary/service-requests/show.blade.php
- resources/views/demo1/service-requests/show.blade.php

---

##  Tache 6 : Securite des routes
- Bloc routes secretaire protege : `Route::middleware('role:secretary')->prefix('secretary/service-requests')...`
- Le medecin ne voit plus le bouton Modifier et naccede pas aux routes secretaire

Fichier : routes/web.php

---

##  Tache 7 : Tests (a completer)
| Test | Resultat | Notes |
|------|----------|-------|
| Migration executee |  | lancer `php artisan migrate` si besoin |
| storage:link |  | executer `php artisan storage:link` puis verifier acces fichier |
| Formulaire triage/assurance/examens |  | saisie manuelle a tester |
| IMC auto |  | verifier calcul (poids/taille) |
| Upload fichier examen |  | tester PDF/JPG, limite 5 Mo |
| Validation required_if |  | assurer blocage si checkbox cochee sans champs |
| Vue details |  | sections conditionnelles + download OK |
| Bouton Modifier roles |  | visible secretaire uniquement |
| Rejet medecin avec raison |  | formulaire visible, raison stockee/affichee |

---

##  Statistiques
- Fichiers crees : 1 (migration)
- Fichiers modifies : 4+ (modele, controleurs, vues create/show, routes)
- Colonnes DB ajoutees : 19
- Methodes ajoutees : 4 accesseurs + 3 JS toggles/calcul IMC
- Securite : middleware `role:secretary` sur routes secretaires

---

##  Problemes resolus
- Acces medecin aux routes secretaire -> verrouille par middleware
- Bouton Modifier visible medecin -> restreint au role secretaire
- Formulaire de rejet medecin cache -> rendu visible, raison obligatoire

---

##  Notes
- Executer `php artisan migrate` et `php artisan storage:link` si non faits
- Tester le workflow complet : secretaire cree/modifie -> medecin rejette avec raison -> secretaire corrige et renvoie -> medecin convertit/valide

---

Pret pour production : **Non** (tests restants a executer)
