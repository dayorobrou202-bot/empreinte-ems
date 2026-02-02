ETAPE3 - Point de référence
===========================

Date: 2026-01-22

Résumé minimal de l'état du projet (point de sauvegarde "ETAPE3"):

- Présences
  - Frontend: fetch() AJAX pour pointer, CSRF meta ajouté.
  - Backend: `PresenceController@store` robuste aux colonnes manquantes, logs ajoutés.

- Congés
  - Modèle: `app/Models/Conge.php` ajouté.
  - Contrôleur: `app/Http/Controllers/CongeController.php`
    - `index()` rend la vue adaptée selon rôle (admin -> `pages.admin.conges_equipe`, collaborateur -> `pages.conges`).
    - `store()` valide et crée une demande, notifie les admins via `NewCongeRequest`.
    - `updateStatus()` met à jour `status` (valeurs admises: `en_attente`, `approuve`, `refuse`).
  - Vues:
    - `resources/views/pages/conges.blade.php` (formulaire collaborateur + tableau des congés)
    - `resources/views/pages/admin/conges_equipe.blade.php` (vue admin, actions approuver/refuser)
  - Notifications:
    - `app/Notifications/NewCongeRequest.php` (mail + database)

- Routes (extraits)
  - Public/auth: `/conges` GET (collaborateur), `/conges` POST (store)
  - Admin group (`prefix admin`, middleware `admin`):
    - `/admin/conges/equipe` -> `CongeController@adminIndex` (view admin)
    - `POST /admin/conges/{conge}/update` -> `CongeController@updateStatus` (route name: `admin.conges.update`)

Tests recommandés après ce point de sauvegarde:
- Exécuter `composer dump-autoload` et `php artisan optimize:clear`.
- Soumettre une demande en tant que collaborateur (/conges) et vérifier la notification en base et l'email (si configuré).
- Se connecter en admin et ouvrir `/admin/conges/equipe`, approuver/refuser, vérifier changement de statut.

Notes:
- Statuts standardisés en français: `en_attente`, `approuve`, `refuse`.
- Si vous souhaitez un champ `processed_by` / `processed_at` ou un compteur de demandes non lues, je peux ajouter une migration et l'UI correspondante.

Fichier créé automatiquement par l'agent — conservez ce fichier comme référence ETAPE3.
