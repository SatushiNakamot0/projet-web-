# Plateforme de Petites Annonces — Laravel Implementation Plan

## Project Understanding (from diagrams)

This is a **classifieds ads platform** (like Leboncoin) with 3 actor roles:

| Actor | Capabilities |
|---|---|
| **Visiteur** (Guest) | Browse/search ads, filter by category/price, view ad details, register, login |
| **Membre** (Authenticated User) | Everything a Visiteur can do + post ads (with photos), manage their ads (edit/delete), contact advertiser |
| **Administrateur** | Everything a Membre can do + moderate ads (approve/reject), manage users (activate/deactivate/delete) |
| **Système Email** | External — sends verification emails on registration, password reset links, ad confirmation |

### Key Flows (from sequence diagrams)
1. **S'inscrire / Se connecter** — Registration with email verification + login with session + password reset via email
2. **Publier une annonce** — Multi-step form with photo upload, saved to DB, email confirmation, optional moderation queue
3. **Gérer ses annonces** — Member views their own ads list, edits (pre-filled form, validation), or deletes (with confirmation modal)
4. **Rechercher / Modérer** — Keyword search + category/price filters for visitors; admin panel with pending ad queue + user management

---

## Laravel Project Structure (already in `/code`)

```
code/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/                  ← Registration, Login, Password Reset
│   │   ├── AnnonceController.php  ← CRUD annonces
│   │   ├── SearchController.php   ← Search + filters
│   │   └── Admin/
│   │       ├── ModerationController.php
│   │       └── UserController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Annonce.php
│   │   ├── Categorie.php
│   │   └── Photo.php
│   └── Mail/
│       ├── VerificationEmail.php
│       └── AnnonceConfirmation.php
├── database/migrations/           ← DB schema
├── resources/views/               ← Blade templates
│   ├── auth/
│   ├── annonces/
│   └── admin/
└── routes/web.php                 ← All routes
```

---

## Database Schema

### Tables to create via migrations

```sql
-- users (already partially created by Laravel)
users: id, name, email, password, role (enum: visiteur/membre/admin), 
       email_verified_at, is_active, timestamps

-- categories
categories: id, nom, slug, timestamps

-- annonces
annonces: id, user_id (FK), categorie_id (FK), titre, description, 
          prix, ville, statut (enum: en_attente/publiee/rejetee/archivee),
          timestamps

-- photos
photos: id, annonce_id (FK), chemin, timestamps
```

---

## Routes Plan

```
GET  /                        → Search + listing
GET  /annonces/{id}           → Détail annonce
GET  /annonces/create         → Formulaire création [auth]
POST /annonces                → Enregistrer [auth]
GET  /annonces/{id}/edit      → Formulaire édition [auth + owner]
PUT  /annonces/{id}           → Modifier [auth + owner]
DELETE /annonces/{id}         → Supprimer [auth + owner]
GET  /mes-annonces            → Liste annonces du membre [auth]
GET  /admin/annonces          → Panel modération [admin]
POST /admin/annonces/{id}/approve → Approuver [admin]
POST /admin/annonces/{id}/reject  → Rejeter [admin]
GET  /admin/users             → Gestion utilisateurs [admin]
```

---

## ✅ Task Division

---

## 👤 YAZID — Backend & Database

### Phase 1 — Database & Models
- [ ] Create migration for `categories` table
- [ ] Create migration for `annonces` table (with statut, prix, ville, etc.)
- [ ] Create migration for `photos` table
- [ ] Update `users` migration to add `role` and `is_active` columns
- [ ] Run all migrations: `php artisan migrate`
- [ ] Create `Annonce`, `Categorie`, `Photo` Eloquent models with relationships
- [ ] Seed categories (Immobilier, Véhicules, Électronique, Emploi, etc.)

### Phase 2 — Authentication
- [x] Install Laravel Breeze: `composer require laravel/breeze && php artisan breeze:install blade`
- [x] Customize registration to include role = 'membre' by default
- [x] Add email verification (already supported by Breeze)
- [x] Add password reset flow

### Phase 3 — Annonces Backend
- [x] `AnnonceController` — CRUD complete (store, update, destroy, show, index)
- [x] File upload logic for photos (store in `storage/app/public/photos`)
- [x] Implement ownership middleware (only creator can edit/delete) -> using AnnoncePolicy
- [x] Route model binding + authorization policies

### Phase 4 — Admin Panel Backend
- [x] `Admin/ModerationController` — list pending, approve, reject
- [x] `Admin/UserController` — list users, activate/deactivate/delete
- [x] Admin middleware to protect `/admin/*` routes

---

## 👤 NAWAR — Frontend (Blade Views) & Search

### Phase 1 — Layout & Design
- [ ] Create `layouts/app.blade.php` — main layout with navbar
- [ ] Create `layouts/admin.blade.php` — admin layout
- [ ] Style with Tailwind CSS (already included in Breeze) or vanilla CSS
- [ ] Responsive navbar: logo, search bar, login/register buttons

### Phase 2 — Annonce Views
- [ ] `annonces/index.blade.php` — homepage with search bar + filter sidebar + ad cards grid
- [ ] `annonces/show.blade.php` — full ad detail page with photos, price, contact button
- [ ] `annonces/create.blade.php` — form to post ad (titre, description, prix, ville, catégorie, photos)
- [ ] `annonces/edit.blade.php` — pre-filled edit form (same as create)
- [ ] `annonces/mes-annonces.blade.php` — member's personal ad list with edit/delete buttons

### Phase 3 — Search & Filters
- [ ] Implement `SearchController` with query + category + price min/max filters
- [ ] Search results view with active filter display
- [ ] Contact advertiser form/modal (sends email)

### Phase 4 — Admin Views
- [ ] `admin/annonces/index.blade.php` — pending ads list with approve/reject buttons
- [ ] `admin/users/index.blade.php` — user management table

---

## Suggested Work Order

```
Week 1:
  Yazid → Migrations + Models + Seeders
  Nawar → Layout + Homepage UI (mock data)

Week 2:
  Yazid → Auth (Breeze) + AnnonceController
  Nawar → Annonce views (create, show, list)

Week 3:
  Yazid → Admin backend (moderation + user mgmt)
  Nawar → Search/filter + Admin views

Week 4:
  Both → Integration, testing, bug fixes, polish
```

---

> [!IMPORTANT]
> **Getting started right now:**
> 1. Yazid runs: `cd code && php artisan make:migration create_categories_table`
> 2. Nawar installs Breeze: `composer require laravel/breeze && php artisan breeze:install blade && npm install && npm run dev`
> 3. Configure `.env` with your MySQL database (or keep SQLite for dev)
