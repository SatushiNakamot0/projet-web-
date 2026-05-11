# Flux des Requêtes — MarketAd World
## De la requête HTTP jusqu'à la base de données

---

## 1. Le Cycle de Vie d'une Requête Laravel (Vue Globale)

Chaque interaction de l'utilisateur suit ce parcours :

```mermaid
flowchart LR
    A["🌐 Navigateur"] -->|"Requête HTTP"| B["📡 Serveur Web"]
    B --> C["⚙️ public/index.php"]
    C --> D["🔧 Kernel HTTP"]
    D --> E["🛡️ Middleware"]
    E --> F["🗺️ Router"]
    F --> G["🎮 Controller"]
    G --> H["📦 Model (Eloquent)"]
    H --> I["🗄️ Base MySQL"]
    I --> H
    H --> G
    G --> J["🖼️ View (Blade)"]
    J --> A
```

### Étapes détaillées :

| # | Étape | Fichier | Rôle |
|---|-------|---------|------|
| 1 | **Requête HTTP** | — | Le navigateur envoie `GET /annonces` ou `POST /annonces` |
| 2 | **Point d'entrée** | `public/index.php` | Charge l'autoloader Composer et démarre Laravel |
| 3 | **Kernel HTTP** | `bootstrap/app.php` | Construit l'application, charge la config |
| 4 | **Middleware** | `app/Http/Middleware/` | Vérifie auth, CSRF, admin... **avant** le contrôleur |
| 5 | **Router** | `routes/web.php` | Fait correspondre l'URL → contrôleur + méthode |
| 6 | **Controller** | `app/Http/Controllers/` | Logique métier : validation, traitement |
| 7 | **Model (Eloquent)** | `app/Models/` | Communique avec MySQL via des requêtes SQL générées |
| 8 | **Base de données** | MySQL | Exécute le SQL, renvoie les résultats |
| 9 | **View (Blade)** | `resources/views/` | Génère le HTML avec les données du contrôleur |
| 10 | **Réponse HTTP** | — | Le HTML est renvoyé au navigateur |

---

## 2. Exemple Concret #1 : Consulter la Page d'Accueil

> L'utilisateur tape `https://marketadworld.up.railway.app/` dans son navigateur.

```mermaid
sequenceDiagram
    actor U as Utilisateur
    participant R as Router (web.php)
    participant C as AnnonceController
    participant M as Model Annonce
    participant DB as MySQL
    participant V as View Blade

    U->>R: GET /
    R->>C: index($request)
    Note over C: Route 'home' détectée
    C->>M: Annonce::active()->with(['photos','categorie'])
    Note over M: Scope: WHERE statut = 'publiee'
    M->>DB: SELECT * FROM annonces WHERE statut='publiee' ORDER BY date_publication DESC LIMIT 6
    DB-->>M: 6 annonces
    M->>DB: SELECT * FROM photos WHERE id_annonce IN (1,2,3,4,5,6)
    DB-->>M: photos associées
    M->>DB: SELECT * FROM categories WHERE id IN (...)
    DB-->>M: catégories
    M-->>C: Collection de 6 annonces (avec relations chargées)
    C->>M: Categorie::all()
    M->>DB: SELECT * FROM categories
    DB-->>M: 8 catégories
    C->>V: view('annonces.index-home', compact('annonces','categories'))
    V-->>U: HTML complet avec les 6 annonces + barre de recherche
```

### Ce qui se passe dans le code :

**`routes/web.php`** :
```php
Route::get('/', [AnnonceController::class, 'index'])->name('home');
```

**`AnnonceController@index`** :
```php
$query = Annonce::active()->with(['photos', 'categorie']);
// ... filtres optionnels (recherche, catégorie, prix) ...
$annonces = $query->latest('date_publication')->take(6)->get();
return view('annonces.index-home', compact('annonces', 'categories'));
```

**`Annonce::scopeActive()`** génère :
```sql
SELECT * FROM annonces WHERE statut = 'publiee'
```

**`->with(['photos', 'categorie'])`** = **Eager Loading** → évite le problème N+1 en chargeant les relations en 2-3 requêtes au lieu de 6+6.

---

## 3. Exemple Concret #2 : Inscription d'un Utilisateur

> L'utilisateur remplit le formulaire d'inscription et clique "Créer mon compte".

```mermaid
sequenceDiagram
    actor U as Utilisateur
    participant R as Router
    participant MW as Middleware
    participant C as RegisteredUserController
    participant V as Validation
    participant M as Model User
    participant DB as MySQL
    participant Auth as Auth::login()

    U->>R: POST /register {nom, prenom, email, mot_de_passe}
    R->>MW: middleware('guest')
    Note over MW: Vérifie que l'user n'est PAS connecté
    MW->>C: store($request)
    C->>V: $request->validate(rules)
    Note over V: nom: required, max:100<br/>email: unique:utilisateurs<br/>mot_de_passe: confirmed, min:8
    V->>DB: SELECT count(*) FROM utilisateurs WHERE email = '...'
    DB-->>V: 0 (email disponible)
    V-->>C: ✅ Validation OK
    C->>M: User::create({...})
    Note over M: Le cast 'hashed' hash automatiquement le mot_de_passe via Bcrypt (12 rounds)
    M->>DB: INSERT INTO utilisateurs (nom,prenom,email,mot_de_passe,role,statut) VALUES (...)
    DB-->>M: ✅ ID = 5
    M-->>C: Objet User créé
    C->>Auth: Auth::login($user)
    Note over Auth: Crée une session dans la table 'sessions'
    Auth->>DB: INSERT INTO sessions (...)
    C-->>U: redirect('/dashboard') → redirect('/') avec cookie de session
```

### La chaîne Middleware pour `POST /register` :

```
Requête HTTP
  └─→ EncryptCookies        (déchiffre les cookies)
      └─→ StartSession      (charge la session depuis MySQL)
          └─→ VerifyCsrfToken  (vérifie le token @csrf du formulaire)
              └─→ guest middleware  (s'assure que l'user n'est pas déjà connecté)
                  └─→ RegisteredUserController@store
```

---

## 4. Exemple Concret #3 : Publier une Annonce

> Un membre connecté remplit le formulaire et soumet une annonce avec une photo.

```mermaid
sequenceDiagram
    actor U as Membre
    participant MW as Middleware auth
    participant C as AnnonceController
    participant V as Validation
    participant MA as Model Annonce
    participant MP as Model Photo
    participant S as Storage
    participant Mail as Mail SMTP
    participant DB as MySQL

    U->>MW: POST /annonces {titre, description, prix, image}
    MW->>MW: ✅ Utilisateur connecté (session valide)
    MW->>C: store($request)
    C->>V: validate(titre, description, prix, image)
    Note over V: image: mimes:jpeg,png,jpg,gif | max:5120
    V-->>C: ✅ OK

    C->>MA: Annonce::create({id_utilisateur: Auth::id(), statut: 'en_attente', ...})
    MA->>DB: INSERT INTO annonces (...) VALUES (...)
    DB-->>MA: ✅ annonce.id = 12

    C->>S: $request->file('image')->store('photos', 'public')
    Note over S: Fichier sauvé dans storage/app/public/photos/abc123.jpg
    S-->>C: path = "photos/abc123.jpg"

    C->>MP: Photo::create({id_annonce: 12, url: '/storage/photos/abc123.jpg'})
    MP->>DB: INSERT INTO photos (...) VALUES (...)
    DB-->>MP: ✅ photo.id = 25

    C->>Mail: Mail::to(user)->send(AnnonceSoumise)
    Note over Mail: try/catch — timeout 5s, ne bloque pas si échec

    C-->>U: redirect('/annonces/12') + flash('Annonce publiée avec succès')
```

### Le flux du fichier image :

```
Formulaire (enctype="multipart/form-data")
  → PHP reçoit le fichier temporaire
    → Laravel valide : type MIME + taille ≤ 5MB
      → $request->file('image')->store('photos', 'public')
        → Fichier copié vers : storage/app/public/photos/abc123.jpg
          → Lien symbolique : public/storage → storage/app/public
            → URL accessible : /storage/photos/abc123.jpg
              → Enregistré dans la table 'photos' avec cette URL
```

---

## 5. Exemple Concret #4 : Modération Admin

> L'admin approuve une annonce en attente.

```mermaid
sequenceDiagram
    actor A as Admin
    participant MW1 as Middleware auth
    participant MW2 as Middleware IsAdmin
    participant C as ModerationController
    participant M as Model Annonce
    participant DB as MySQL

    A->>MW1: PATCH /admin/moderation/12/approve
    MW1->>MW1: ✅ Connecté
    MW1->>MW2: Vérifier le rôle
    MW2->>DB: SELECT role FROM utilisateurs WHERE id = Auth::id()
    DB-->>MW2: role = 'admin'
    MW2->>MW2: ✅ isAdmin() = true
    MW2->>C: approve(Annonce $annonce)
    Note over C: Route Model Binding:<br/>Laravel charge automatiquement<br/>l'Annonce #12 depuis la DB
    C->>M: $annonce->update({statut: 'publiee', motif_rejet: null})
    M->>DB: UPDATE annonces SET statut='publiee', motif_rejet=NULL WHERE id=12
    DB-->>M: ✅ 1 row affected
    C-->>A: redirect('/admin/moderation') + flash('Annonce approuvée ✅')
```

### La double couche de protection :

```
Requête PATCH /admin/moderation/12/approve
  │
  ├─ Middleware 'auth'     → L'utilisateur est-il connecté ?         → sinon redirect /login
  │
  └─ Middleware 'IsAdmin'  → L'utilisateur a-t-il le rôle 'admin' ? → sinon 403 Forbidden
      │
      └─ ModerationController@approve → Exécute la logique
```

Si un membre essaie d'accéder à `/admin/moderation`, il reçoit une **erreur 403** :
```php
// IsAdmin.php
if (!Auth::check() || !Auth::user()->isAdmin()) {
    abort(403, 'Accès refusé. Réservé aux administrateurs.');
}
```

---

## 6. Exemple Concret #5 : Envoyer un Message

> Un membre contacte un vendeur à propos d'une annonce.

```mermaid
sequenceDiagram
    actor U as Acheteur
    participant C as MessageController
    participant M as Model Message
    participant DB as MySQL

    U->>C: POST /messages/3 {contenu: "Bonjour, encore dispo?", id_annonce: 12}
    C->>C: validate(contenu: required|max:1000)
    C->>M: Message::create({id_expediteur: Auth::id(), id_destinataire: 3, id_annonce: 12, ...})
    M->>DB: INSERT INTO messages (id_expediteur, id_destinataire, id_annonce, contenu, lu, date_envoi) VALUES (5, 3, 12, '...', false, NOW())
    DB-->>M: ✅ message.id = 42
    C-->>U: redirect('/messages/3') + flash('Message envoyé')
```

### Quand le vendeur ouvre la conversation :

```mermaid
sequenceDiagram
    actor V as Vendeur
    participant C as MessageController
    participant DB as MySQL

    V->>C: GET /messages/5
    C->>DB: UPDATE messages SET lu=true WHERE id_expediteur=5 AND id_destinataire=3 AND lu=false
    Note over DB: Marquer tous les messages reçus comme lus
    C->>DB: SELECT * FROM messages WHERE (exp=5 AND dest=3) OR (exp=3 AND dest=5) ORDER BY date_envoi
    DB-->>C: Liste de messages triés chronologiquement
    C-->>V: Vue conversation avec tous les messages
```

---

## 7. Résumé : Les Couches de l'Application

```mermaid
flowchart TB
    subgraph "🌐 FRONTEND (Navigateur)"
        A[HTML/CSS/JS]
        B[Alpine.js - Interactivité]
        C[Formulaires avec @csrf]
    end

    subgraph "🛡️ MIDDLEWARE (Filtrage)"
        D[EncryptCookies]
        E[StartSession]
        F[VerifyCsrfToken]
        G["auth / guest / IsAdmin"]
    end

    subgraph "🗺️ ROUTING"
        H["web.php → Controller@method"]
    end

    subgraph "🎮 CONTROLLERS (Logique)"
        I[Validation des données]
        J[Logique métier]
        K[Appel aux Models]
    end

    subgraph "📦 MODELS (Eloquent ORM)"
        L[Scopes - active / enAttente]
        M[Relations - belongsTo / hasMany]
        N["Casts - hashed, datetime, boolean"]
    end

    subgraph "🗄️ BASE DE DONNÉES (MySQL)"
        O[utilisateurs]
        P[annonces]
        Q[categories]
        R[photos]
        S[messages]
        T[sessions]
    end

    A --> D --> E --> F --> G --> H --> I --> J --> K --> L --> O
    K --> M --> P
    K --> N --> Q
```

---

## 8. Eloquent ORM — Comment le PHP Devient du SQL

Eloquent **traduit automatiquement** le PHP en requêtes SQL. Voici les correspondances exactes dans notre projet :

| Code PHP (Eloquent) | SQL Généré |
|---------------------|------------|
| `Annonce::all()` | `SELECT * FROM annonces` |
| `Annonce::active()->get()` | `SELECT * FROM annonces WHERE statut = 'publiee'` |
| `Annonce::find(12)` | `SELECT * FROM annonces WHERE id = 12 LIMIT 1` |
| `Annonce::create([...])` | `INSERT INTO annonces (...) VALUES (...)` |
| `$annonce->update(['statut' => 'publiee'])` | `UPDATE annonces SET statut = 'publiee' WHERE id = 12` |
| `$annonce->delete()` | `DELETE FROM annonces WHERE id = 12` |
| `$annonce->photos` | `SELECT * FROM photos WHERE id_annonce = 12 ORDER BY ordre` |
| `$annonce->utilisateur` | `SELECT * FROM utilisateurs WHERE id = {id_utilisateur}` |
| `Annonce::with(['photos'])->get()` | 2 requêtes : `SELECT * FROM annonces` + `SELECT * FROM photos WHERE id_annonce IN (...)` |
| `User::where('role','admin')->first()` | `SELECT * FROM utilisateurs WHERE role = 'admin' LIMIT 1` |
| `Auth::user()->annonces()->count()` | `SELECT count(*) FROM annonces WHERE id_utilisateur = {auth_id}` |

---

## 9. Le Cycle Complet en une Image

```
┌─────────────────────────────────────────────────────────────────┐
│                        NAVIGATEUR                               │
│  L'utilisateur clique "Publier une annonce"                     │
│  → Le navigateur envoie : POST /annonces + données + image     │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                     public/index.php                            │
│  Point d'entrée unique — charge Laravel                        │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                      MIDDLEWARE STACK                            │
│  1. EncryptCookies    → déchiffre les cookies                  │
│  2. StartSession      → charge la session depuis MySQL         │
│  3. VerifyCsrfToken   → vérifie le token anti-CSRF             │
│  4. auth middleware   → vérifie que l'user est connecté        │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                    ROUTER (web.php)                              │
│  POST /annonces → AnnonceController@store                      │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                 CONTROLLER (AnnonceController)                  │
│  1. $request->validate()  → valide titre, prix, image          │
│  2. Annonce::create()     → insère dans MySQL                  │
│  3. store('photos')       → sauvegarde l'image sur le disque   │
│  4. Photo::create()       → enregistre l'URL dans MySQL        │
│  5. Mail::send()          → tente d'envoyer un email           │
│  6. redirect()            → redirige vers la page de l'annonce │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                        ┌──────┴──────┐
                        ▼             ▼
┌─────────────────────────┐  ┌────────────────────┐
│     MODEL (Eloquent)    │  │   STORAGE (disque)  │
│  Annonce::create()      │  │  photos/abc123.jpg  │
│  → INSERT INTO annonces │  └────────────────────┘
│  Photo::create()        │
│  → INSERT INTO photos   │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│     BASE MYSQL          │
│  annonces: id=12        │
│  photos: id_annonce=12  │
└─────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      VIEW (Blade)                               │
│  show.blade.php reçoit $annonce                                │
│  → Génère le HTML avec les données                             │
│  → Renvoie la réponse HTTP 200 au navigateur                   │
└─────────────────────────────────────────────────────────────────┘
```

---

*Ce document trace le parcours complet de chaque requête dans MarketAd World — de la saisie utilisateur jusqu'à la persistance en base de données et le rendu final.*
