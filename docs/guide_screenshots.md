# Guide de Réalisation des Captures d'Écran (Backend)

Ce document explique comment réaliser, nommer et ranger les captures d'écran nécessaires pour illustrer la partie **Backend** dans notre rapport de projet, en attendant que l'interface Frontend soit terminée.

---

## 📁 1. Organisation des Dossiers (Où placer les images)

Toutes les captures doivent être enregistrées dans le dossier `docs/screenshots/` du projet.
Pour garder un projet propre, crée les sous-dossiers suivants si ce n'est pas déjà fait :

- `docs/screenshots/01_conception/` : *Pour les diagrammes (MCD, Cas d'utilisation, etc. déjà faits)*
- `docs/screenshots/02_backend/` : *Pour tes captures techniques du Backend (voir ci-dessous)*
- `docs/screenshots/03_frontend/` : *Dossier réservé pour Nawar quand les vues seront prêtes*

---

## 📸 2. Ce qu'il faut capturer (Liste des tâches)

Pour prouver que le Backend est fonctionnel et solide, tu dois réaliser **4 captures d'écran obligatoires** depuis ton terminal et ton éditeur (VS Code).

> **Important :** Sur Windows, utilise l'**Outil Capture d'écran** (Raccourci : `Touche Windows + Maj + S`).

### Capture 1 : La base de données et les migrations
Cette capture prouve que le script de base de données génère bien nos tables (`utilisateurs`, `annonces`, etc.) et insère les données de test (Seeders).

* **Comment faire :** 
  1. Ouvre ton terminal.
  2. Rentre dans le dossier de code : `cd code`
  3. Lance : `php artisan migrate:fresh --seed`
* **Ce qu'il faut photographier :** Le texte vert de succès du terminal.
* **Nom du fichier exact :** `01_migrations_et_seeders.png`

### Capture 2 : Les tests de conformité MCD
Cette capture prouve que les relations (One-to-Many, cascade delete) fonctionnent comme prévu dans le diagramme MCD.

* **Comment faire :** 
  1. Toujours dans le dossier `code`, lance : `php test_mcd.php`
* **Ce qu'il faut photographier :** Le texte de sortie du terminal montrant les petits "✅" (Utilisateurs trouvés, Annonces créées, Photos liées).
* **Nom du fichier exact :** `02_test_relations_mcd.png`

### Capture 3 : Les routes de l'application (API)
Cette capture montre au jury que l'architecture MVC est prête et sécurisée, avec des routes d'administration et d'annonces.

* **Comment faire :** 
  1. Toujours dans le dossier `code`, lance : `php artisan route:list`
* **Ce qu'il faut photographier :** La liste des routes (focalise-toi sur les lignes qui contiennent `admin/` et `annonces`).
* **Nom du fichier exact :** `03_liste_des_routes.png`

### Capture 4 : La qualité du code source
Cette capture montre que le code est propre, structuré, et documenté en Darija comme exigé.

* **Comment faire :** 
  1. Ouvre VS Code.
  2. Garde l'explorateur de fichiers ouvert à gauche (pour qu'on voie bien `Controllers`, `Models`, `Policies`).
  3. Ouvre un fichier principal au milieu (par exemple `app/Policies/AnnoncePolicy.php` ou `app/Models/User.php`) pour qu'on lise les commentaires.
* **Ce qu'il faut photographier :** Toute la fenêtre de ton VS Code.
* **Nom du fichier exact :** `04_structure_code_backend.png`

---

## 🏷️ 3. La règle d'or pour le nommage

Pour le rapport final, il est crucial que les images soient nommées uniformément. Garde ce format en tête :
- **Tout en minuscules**
- **Pas d'espaces** (utilise des tirets du bas `_` à la place)
- Extension **`.png`** (car le format PNG rend le texte des terminaux et du code beaucoup plus net que le `.jpg`).

Une fois ces 4 captures générées et placées dans `docs/screenshots/02_backend/`, tu peux commiter et pousser sur GitHub. Ton travail de rapport technique sera quasiment terminé !
