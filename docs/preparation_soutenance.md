# 🎓 Préparation Soutenance — MarketAd World

Guide pour répondre aux questions du professeur sur chaque aspect du projet.

---

## 1. Le Logo

**Question : D'où vient le logo ?**

Le logo a été **conçu par nous-mêmes** à l'aide d'un outil de design en ligne (type Canva / logiciel de création graphique). Il représente :
- Un **globe terrestre** (World) symbolisant la portée internationale de la plateforme
- Un **pin de localisation** (marker) évoquant les annonces localisées
- Une **étiquette de prix** (price tag) attachée au globe, représentant les petites annonces / e-commerce

La couleur **indigo (#4f46e5)** a été choisie pour son aspect professionnel et moderne. Le logo est stocké dans `public/images/logo.png` et utilisé dans la navbar et le footer.

Nous avons itéré sur 3 versions du logo (disponibles dans `docs/ux design/`) :
- `logo.png` — version brute (5 MB)
- `logo_bla_background.png` — version sans fond
- `logofinal.png` — version optimisée utilisée en production (795 KB)

---

## 2. Le Nom "MarketAd World"

**Market** = Marché / **Ad** = Annonce (Advertisement) / **World** = Monde

→ "Le marché mondial des annonces" — un nom anglais choisi pour donner un aspect professionnel et international à la plateforme.

---

## 3. Choix Technologiques

### Pourquoi Laravel ?
- C'est le framework PHP le plus populaire et le plus structuré (pattern MVC)
- Il offre **Eloquent ORM** pour manipuler la base de données sans écrire de SQL
- **Laravel Breeze** fournit un système d'authentification complet prêt à l'emploi
- Les **migrations** permettent de versionner la base de données
- Le système de **middleware**, **policies** et **validation** assure la sécurité

### Pourquoi MySQL ?
- SGBD relationnel robuste, parfait pour notre MCD avec clés étrangères
- Compatible avec le déploiement Railway
- Laravel le supporte nativement

### Pourquoi TailwindCSS + CSS custom ?
- **TailwindCSS** est utilisé pour les composants Breeze (auth, formulaires)
- Le **CSS custom** (dans `layouts/app.blade.php`) nous donne un contrôle total sur le design — on a créé notre propre **design system** avec des variables CSS

### Pourquoi Alpine.js ?
- Micro-framework JS léger (14 KB) intégré par défaut avec Breeze
- Permet des interactions dynamiques (menus dropdown, toggles) sans avoir besoin de Vue.js ou React

### Pourquoi Vite ?
- Bundler JavaScript moderne et rapide (remplace Webpack dans Laravel 12)
- Hot Module Replacement (rechargement automatique pendant le développement)

---

## 4. Les Images des Annonces

**Question : D'où viennent les photos des annonces ?**

Les images sont des **photos réelles collectées depuis des sites d'annonces marocains** (Avito, etc.) pour servir de **données de démonstration**. Elles sont stockées dans le dossier `Pictures/` du repo et copiées dans `storage/app/public/photos/` via le seeder.

Les descriptions des annonces sont également **inspirées d'annonces réelles** pour donner un aspect authentique à la plateforme.

---

## 5. Les Icônes

**Question : D'où viennent les icônes du menu ?**

On utilise **Lucide Icons** — une bibliothèque open source d'icônes SVG, chargée via CDN :
```html
<script src="https://unpkg.com/lucide@latest"></script>
```

Icônes utilisées dans le dropdown : `layout-list`, `message-square`, `settings`, `shield-check`, `users`, `log-out`, `edit-2`, `trash-2`, `mail`.

---

## 6. La Police d'Écriture

**Question : Quelle police utilisez-vous ?**

**Inter** — une police Google Fonts moderne, conçue spécifiquement pour les interfaces web. Chargée via :
```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
```

Choisie pour sa **lisibilité** et son aspect **professionnel** et **épuré**.

---

## 7. Architecture & Conception

### Le MCD (Modèle Conceptuel de Données)
Conçu avec **StarUML** (fichier `.mdj` dans `docs/MCD/`). 5 entités principales :
- **Utilisateur** (1,n) → **Annonce** : un utilisateur peut publier plusieurs annonces
- **Catégorie** (1,n) → **Annonce** : une catégorie contient plusieurs annonces
- **Annonce** (1,n) → **Photo** : une annonce peut avoir plusieurs photos
- **Annonce** (1,n) → **Message** : les messages sont liés à une annonce
- **Utilisateur** (1,n) → **Message** : en tant qu'expéditeur ou destinataire

### Le Diagramme de Cas d'Utilisation
3 acteurs : **Visiteur**, **Membre**, **Administrateur** + le système d'email.
Les cas d'utilisation suivent les relations **include** et **extend** du UML.

### Les Diagrammes de Séquence
5 diagrammes couvrant : S'inscrire/Se connecter, Publier une annonce, Gérer ses annonces, Rechercher/Modérer.

---

## 8. Sécurité — Comment on protège l'application

| Mécanisme | Comment |
|-----------|---------|
| **Hachage des mots de passe** | Bcrypt avec 12 rounds, via le cast Laravel `'hashed'` |
| **Protection CSRF** | Chaque formulaire contient `@csrf` |
| **Middleware `auth`** | Bloque l'accès aux routes protégées (publier, messagerie, profil) |
| **Middleware `IsAdmin`** | Vérifie le rôle admin pour les routes `/admin/*` |
| **Policy `AnnoncePolicy`** | Un membre ne peut modifier/supprimer QUE ses propres annonces |
| **Gate::authorize()** | Vérifie les droits avant chaque action sensible |
| **Rate Limiting** | 5 tentatives de connexion max par email+IP |
| **Validation** | Chaque formulaire est validé côté serveur (`$request->validate()`) |
| **HTTPS forcé** | En production, `URL::forceScheme('https')` |

---

## 9. Le Système de Modération

**Question : Comment fonctionne la modération ?**

1. Un membre publie une annonce → statut `en_attente`
2. L'admin accède au panel `/admin/moderation`
3. L'admin peut **approuver** (statut → `publiee`) ou **rejeter** (statut → `rejetee` + motif)
4. Seules les annonces `publiee` sont visibles par les visiteurs
5. Si un membre modifie son annonce, elle repasse en `en_attente`

---

## 10. Le Déploiement sur Railway

**Question : Comment avez-vous déployé ?**

- **Railway** est une plateforme cloud (PaaS) qui héberge notre application
- Le déploiement est **automatique** via GitHub — chaque `git push` déclenche un nouveau build
- On utilise une **base MySQL** fournie par Railway
- Les variables d'environnement (`.env`) sont configurées dans le dashboard Railway
- En production, on force **HTTPS** via `AppServiceProvider`

---

## 11. La Messagerie

**Question : Comment fonctionne la messagerie interne ?**

- Quand un membre clique "Contacter le vendeur" sur une annonce, il est redirigé vers la page de conversation
- Les messages sont **liés à une annonce** (on sait toujours de quelle annonce on parle)
- La boîte de réception groupe les messages **par conversation** (par utilisateur)
- Les messages non lus sont comptés et affichés avec un badge
- Quand on ouvre une conversation, les messages reçus sont **automatiquement marqués comme lus**

---

## 12. Le Système d'Email

**Question : Comment fonctionnent les emails ?**

- Configuré avec **Gmail SMTP** (port 587, TLS)
- Un email `AnnonceSoumise` est envoyé après la publication d'une annonce
- L'envoi est dans un `try/catch` — si ça échoue, l'erreur est loguée mais n'empêche pas l'utilisateur de continuer
- **Note :** Sur Railway, le SMTP Gmail peut être lent/bloqué, donc on a mis un timeout de 5 secondes

---

## 13. Répartition du Travail (Yazid & Nawar)

Si le prof demande qui a fait quoi :

| Tâche | Responsable |
|-------|-------------|
| Conception UML (MCD, cas d'utilisation, séquences) | Les deux ensemble |
| Mise en place du projet Laravel | Yazid |
| Base de données & Migrations | Yazid |
| Système d'authentification | Yazid |
| CRUD des annonces | Les deux |
| Système de messagerie | Les deux |
| Panel d'administration | Yazid |
| Design UI/UX | Yazid |
| Données de test & Seeders | Les deux |
| Déploiement Railway | Yazid |
| Rapport LaTeX | Nawar |

---

## 14. Questions Pièges Fréquentes

### "Pourquoi vous n'avez pas utilisé une API REST ?"
→ Pour un projet de cette taille, le rendu côté serveur avec Blade est plus simple et efficace. Une API REST + frontend SPA aurait ajouté de la complexité inutile.

### "Pourquoi les noms de colonnes sont en français ?"
→ C'est un choix délibéré pour que le code soit **cohérent avec le MCD** qui est en français. Ça facilite la traçabilité entre la conception et l'implémentation.

### "Comment vous gérez les images ?"
→ Les images sont uploadées via `Storage::disk('public')` de Laravel, stockées dans `storage/app/public/photos/`, et accessibles via le lien symbolique `public/storage`. On valide le type (jpeg, png, gif) et la taille (max 5 MB).

### "Qu'est-ce qui se passe si on supprime une annonce ?"
→ Grâce au `onDelete('cascade')` dans les migrations, la suppression d'une annonce **supprime automatiquement** ses photos et ses messages associés. On supprime aussi les fichiers physiques du serveur.

### "Pourquoi 3 rôles mais le visiteur n'est jamais utilisé ?"
→ Le rôle `visiteur` est prévu dans le MCD pour une future évolution (comptes limités), mais actuellement tous les inscrits sont des `membres` par défaut.

### "C'est quoi le `getAuthPassword()` dans le modèle User ?"
→ Laravel cherche par défaut un champ `password`. Comme notre colonne s'appelle `mot_de_passe`, on surcharge cette méthode pour dire à Laravel où trouver le mot de passe hashé.

### "Pourquoi `Auth::attempt` utilise 'password' et pas 'mot_de_passe' ?"
→ `Auth::attempt` attend toujours la clé `password` en interne. Dans le `LoginRequest`, on passe `'password' => $this->mot_de_passe` — le champ du formulaire s'appelle `mot_de_passe`, mais on le mappe vers `password` pour Laravel. Le `getAuthPassword()` du modèle fait le reste.

---

Bonne chance demain ! 💪🎓
