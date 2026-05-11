# 🚀 Comment lancer le projet

## Prérequis

- **PHP** ≥ 8.2
- **Composer**
- **Node.js** ≥ 18 + npm

> Pas besoin de MySQL — le projet utilise **SQLite** (fichier local, zéro config).

---

## Étapes

### 1. Cloner le projet

```bash
git clone https://github.com/SatushiNakamot0/projet-web-.git
cd projet-web-/code
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Créer la base de données + données exemples

```bash
php artisan migrate:fresh --seed
```

> Cela crée les tables, les catégories, les utilisateurs de test et **11 annonces exemples avec photos**.

### 5. Lien storage (pour les images)

```bash
php artisan storage:link
```

### 6. Compiler le CSS/JS

```bash
npm run build
```

### 7. Lancer le serveur

```bash
php artisan serve
```

Ouvrir **http://127.0.0.1:8000** dans le navigateur. ✅

---

## Comptes de test

| Rôle   | Email               | Mot de passe   |
|--------|---------------------|----------------|
| Admin  | admin@annonces.ma   | password123    |
| Membre | membre@annonces.ma  | password123    |
