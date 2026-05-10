<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'MarketAd World')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
:root {
  --bg-color: #f8fafc;
  --surface-color: #ffffff;
  --text-main: #0f172a;
  --text-muted: #64748b;
  --text-light: #94a3b8;
  
  --primary: #4f46e5;
  --primary-hover: #4338ca;
  --primary-light: #e0e7ff;
  
  --border: #e2e8f0;
  --border-focus: #c7d2fe;
  
  --danger: #ef4444;
  --danger-light: #fee2e2;
  --success: #10b981;
  --success-light: #d1fae5;
  
  --font-main: 'Inter', sans-serif;
  
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  
  --radius-sm: 0.375rem;
  --radius-md: 0.5rem;
  --radius-lg: 0.75rem;
  --radius-xl: 1rem;
}

* { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; }
body { 
    background-color: var(--bg-color); 
    color: var(--text-main); 
    font-family: var(--font-main); 
    font-size: 15px; 
    line-height: 1.6; 
    min-height: 100vh; 
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

/* NAVIGATION */
.nav { 
    background-color: rgba(255, 255, 255, 0.95); 
    backdrop-filter: blur(8px);
    border-bottom: 1px solid var(--border); 
    position: sticky; 
    top: 0; 
    z-index: 100; 
    padding: 0 2rem; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    height: 72px; 
}
.nav-container {
    max-width: 1280px;
    margin: 0 auto;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.logo { 
    font-family: var(--font-main); 
    font-size: 1.25rem; 
    font-weight: 800; 
    color: var(--primary); 
    text-decoration: none; 
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 0;
}
.logo-img {
    height: 52px;
    width: auto;
    object-fit: contain;
    margin: -10px -8px -10px -12px;
}
.nav-btn { 
    background: none; 
    border: none; 
    color: var(--text-muted); 
    font-family: var(--font-main); 
    font-size: 0.9rem; 
    font-weight: 500; 
    padding: 0.5rem 1rem; 
    border-radius: var(--radius-md); 
    cursor: pointer; 
    transition: all 0.2s; 
    text-decoration: none; 
    display: inline-block; 
}
.nav-btn:hover { 
    background-color: #f1f5f9; 
    color: var(--text-main); 
}
.btn-primary { 
    background-color: var(--primary); 
    color: white; 
    font-family: var(--font-main); 
    font-weight: 600; 
    font-size: 0.9rem; 
    border: none; 
    padding: 0.6rem 1.2rem; 
    border-radius: var(--radius-md); 
    cursor: pointer; 
    text-decoration: none; 
    display: inline-block; 
    transition: all 0.2s; 
    box-shadow: var(--shadow-sm);
}
.btn-primary:hover { 
    background-color: var(--primary-hover); 
    transform: translateY(-1px); 
    box-shadow: var(--shadow-md); 
}
.btn-outline { 
    background: transparent; 
    color: var(--text-main); 
    border: 1px solid var(--border); 
    font-family: var(--font-main); 
    font-size: 0.9rem; 
    font-weight: 500; 
    padding: 0.6rem 1.2rem; 
    border-radius: var(--radius-md); 
    cursor: pointer; 
    transition: all 0.2s; 
    text-decoration: none; 
    display: inline-block; 
}
.btn-outline:hover { 
    background-color: #f8fafc; 
    border-color: #cbd5e1; 
}

/* USER DROPDOWN */
.user-dropdown { position: relative; margin-left: 0.5rem; }
.user-dropdown-toggle { 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
    background: transparent; 
    border: 1px solid transparent; 
    color: var(--text-main); 
    font-family: var(--font-main); 
    font-size: 0.9rem; 
    font-weight: 500; 
    padding: 0.35rem 0.5rem; 
    border-radius: var(--radius-md); 
    cursor: pointer; 
    transition: all 0.2s; 
}
.user-dropdown-toggle:hover { 
    background-color: #f1f5f9; 
}
.avatar-sm { 
    width: 32px; 
    height: 32px; 
    border-radius: 50%; 
    background-color: var(--primary-light); 
    color: var(--primary); 
    font-weight: 600; 
    font-size: 0.8rem; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    flex-shrink: 0; 
}
.dropdown-menu { 
    display: none; 
    position: absolute; 
    right: 0; 
    top: 100%; 
    padding-top: 8px;
    z-index: 200; 
}
.dropdown-menu-inner {
    background-color: var(--surface-color); 
    border: 1px solid var(--border); 
    border-radius: var(--radius-md); 
    min-width: 200px; 
    padding: 0.5rem; 
    box-shadow: var(--shadow-lg); 
}
.user-dropdown:hover .dropdown-menu { display: block; }
.dropdown-item { 
    display: flex; 
    align-items: center; 
    gap: 0.75rem; 
    padding: 0.6rem 0.8rem; 
    border-radius: var(--radius-sm); 
    font-size: 0.9rem; 
    font-weight: 500;
    color: var(--text-muted); 
    text-decoration: none; 
    cursor: pointer; 
    transition: all 0.15s; 
    background: none; 
    border: none; 
    width: 100%; 
    text-align: left; 
    font-family: var(--font-main); 
}
.dropdown-item:hover { 
    background-color: #f8fafc; 
    color: var(--text-main); 
}
.dropdown-item.danger:hover { 
    background-color: var(--danger-light); 
    color: var(--danger); 
}
.dropdown-sep { 
    height: 1px; 
    background-color: var(--border); 
    margin: 0.5rem 0; 
}

/* FLASH MESSAGES */
.flash-success { 
    background-color: var(--success-light); 
    border: 1px solid #a7f3d0; 
    color: #065f46; 
    padding: 1rem 1.5rem; 
    border-radius: var(--radius-md); 
    font-size: 0.9rem; 
    font-weight: 500;
    margin: 1.5rem auto 0; 
    max-width: 1280px;
    width: calc(100% - 4rem);
}
.flash-error { 
    background-color: var(--danger-light); 
    border: 1px solid #fecaca; 
    color: #991b1b; 
    padding: 1rem 1.5rem; 
    border-radius: var(--radius-md); 
    font-size: 0.9rem; 
    font-weight: 500;
    margin: 1.5rem auto 0; 
    max-width: 1280px;
    width: calc(100% - 4rem);
}

/* FOOTER */
.footer { 
    background-color: var(--surface-color); 
    border-top: 1px solid var(--border); 
    padding: 4rem 2rem; 
    margin-top: 4rem;
}
.footer-container {
    max-width: 1280px;
    margin: 0 auto;
    display: grid; 
    grid-template-columns: 1fr auto; 
    gap: 2rem; 
    align-items: start;
}
.footer-logo { 
    font-family: var(--font-main); 
    font-size: 1.25rem; 
    font-weight: 800; 
    color: var(--primary); 
    margin-bottom: 0.5rem; 
    letter-spacing: -0.5px;
}
.footer-copy { 
    font-size: 0.875rem; 
    color: var(--text-muted); 
}
.footer-links { 
    display: flex; 
    gap: 2rem; 
}
.footer-links a { 
    color: var(--text-muted); 
    font-size: 0.9rem; 
    font-weight: 500;
    text-decoration: none; 
    transition: color 0.2s; 
}
.footer-links a:hover { 
    color: var(--primary); 
}
</style>
@stack('styles')
</head>
<body>

<nav class="nav">
  <div class="nav-container">
      <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="MarketAd World Logo" class="logo-img">
        MarketAd World
      </a>
      <div style="display:flex;gap:0.5rem;align-items:center;">
        <a href="{{ route('annonces.index') }}" class="nav-btn">Toutes les annonces</a>
        @auth
          <a href="{{ route('annonces.create') }}" class="btn-primary" style="margin-left:1rem;">Publier une annonce</a>
          <div class="user-dropdown">
            <button class="user-dropdown-toggle">
              <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->nom, 0, 2)) }}</div>
              <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
            </button>
            <div class="dropdown-menu">
              <div class="dropdown-menu-inner">
                <a href="{{ route('annonces.mine') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="layout-list" style="width:18px;height:18px;"></i> Mes annonces</a>
                <a href="{{ route('messages.index') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="message-square" style="width:18px;height:18px;"></i> Messagerie</a>
                <a href="{{ route('profile.edit') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="settings" style="width:18px;height:18px;"></i> Mon profil</a>
                @if(Auth::user()->isAdmin())
                  <div class="dropdown-sep"></div>
                  <a href="{{ route('admin.moderation.index') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="shield-check" style="width:18px;height:18px;"></i> Gérer les annonces</a>
                  <a href="{{ route('admin.users.index') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="users" style="width:18px;height:18px;"></i> Gérer les utilisateurs</a>
                @endif
                <div class="dropdown-sep"></div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item danger" style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="log-out" style="width:18px;height:18px;"></i> Déconnexion</button>
                </form>
              </div>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="nav-btn">Connexion</a>
          <a href="{{ route('register') }}" class="btn-primary" style="margin-left:0.5rem;">S'inscrire</a>
        @endauth
      </div>
  </div>
</nav>

@if(session('success'))
  <div class="flash-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="flash-error">{{ session('error') }}</div>
@endif

<main>
  @yield('content')
</main>

<footer class="footer">
  <div class="footer-container">
      <div>
        <div class="footer-logo" style="display:flex;align-items:center;gap:0;">
          <img src="{{ asset('images/logo.png') }}" alt="MarketAd World Logo" style="height:44px;width:auto;margin:-8px -6px -8px -10px;">
          MarketAd World
        </div>
        <div class="footer-copy">© {{ date('Y') }} MarketAd World. Tous droits réservés à yazid et nawar.</div>
      </div>
      <div class="footer-links">
        <a href="{{ route('home') }}">Accueil</a>
        <a href="{{ route('annonces.index') }}">Annonces</a>
        @auth
          <a href="{{ route('annonces.create') }}">Publier</a>
        @endauth
      </div>
  </div>
</footer>

@stack('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>
</body>
</html>