<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'MarketAd World')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
:root {
  --navy: #091224; --navy2: #0B1B3D; --navy3: #0f2252; --navy4: #142d6e;
  --cyan: #00B2FF; --cyan2: #00d4ff;
  --cyan-dim: rgba(0,178,255,0.12); --cyan-dim2: rgba(0,178,255,0.06);
  --white: #ffffff; --white-80: rgba(255,255,255,0.8);
  --white-60: rgba(255,255,255,0.6); --white-50: rgba(255,255,255,0.5);
  --white-40: rgba(255,255,255,0.4); --white-30: rgba(255,255,255,0.3);
  --white-10: rgba(255,255,255,0.1); --white-06: rgba(255,255,255,0.06);
  --border: rgba(255,255,255,0.08); --border-cyan: rgba(0,178,255,0.3);
  --font-display: 'Syne', sans-serif; --font-body: 'Outfit', sans-serif;
}
* { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; }
body { background:var(--navy); color:var(--white); font-family:var(--font-body); font-size:15px; line-height:1.6; min-height:100vh; overflow-x:hidden; }

.nav { background:rgba(9,18,36,0.82); backdrop-filter:blur(20px) saturate(180%); border-bottom:1px solid rgba(0,178,255,0.12); box-shadow:0 1px 40px rgba(0,0,0,0.4); position:sticky; top:0; z-index:100; padding:0 2.5rem; display:flex; align-items:center; justify-content:space-between; height:68px; }
.logo { font-family:var(--font-display); font-size:1.25rem; font-weight:800; color:var(--cyan); text-decoration:none; text-shadow:0 0 20px rgba(0,178,255,0.4); }
.nav-btn { background:none; border:none; color:var(--white-80); font-family:var(--font-body); font-size:0.875rem; font-weight:500; padding:0.4rem 0.9rem; border-radius:6px; cursor:pointer; transition:all 0.2s; text-decoration:none; display:inline-block; }
.nav-btn:hover { background:var(--white-10); color:var(--white); }
.btn-cyan { background:linear-gradient(135deg,var(--cyan) 0%,#0090d4 100%); color:var(--navy); font-family:var(--font-body); font-weight:700; font-size:0.875rem; border:none; padding:0.5rem 1.3rem; border-radius:8px; cursor:pointer; box-shadow:0 4px 14px rgba(0,178,255,0.3); text-decoration:none; display:inline-block; transition:all 0.25s; position:relative; overflow:hidden; }
.btn-cyan:hover { background:linear-gradient(135deg,var(--cyan2) 0%,var(--cyan) 100%); transform:translateY(-2px) scale(1.02); box-shadow:0 8px 24px rgba(0,178,255,0.45); color:var(--navy); }
.btn-outline { background:rgba(0,178,255,0.05); color:var(--cyan); border:1px solid rgba(0,178,255,0.4); font-family:var(--font-body); font-size:0.875rem; font-weight:600; padding:0.5rem 1.3rem; border-radius:8px; cursor:pointer; transition:all 0.25s; text-decoration:none; display:inline-block; }
.btn-outline:hover { background:var(--cyan-dim); border-color:var(--cyan); color:var(--cyan); transform:translateY(-1px); }

.user-dropdown { position:relative; }
.user-dropdown-toggle { display:flex; align-items:center; gap:0.5rem; background:var(--white-06); border:1px solid var(--border); color:var(--white-80); font-family:var(--font-body); font-size:0.875rem; font-weight:500; padding:0.35rem 0.8rem; border-radius:8px; cursor:pointer; transition:all 0.2s; }
.user-dropdown-toggle:hover { border-color:var(--border-cyan); color:var(--cyan); }
.avatar-sm { width:30px; height:30px; border-radius:50%; background:var(--cyan); color:var(--navy); font-weight:700; font-size:0.75rem; display:flex; align-items:center; justify-content:center; font-family:var(--font-display); flex-shrink:0; }
.dropdown-menu { display:none; position:absolute; right:0; top:calc(100% + 8px); background:var(--navy2); border:1px solid var(--border-cyan); border-radius:12px; min-width:190px; padding:0.4rem; box-shadow:0 16px 48px rgba(0,0,0,0.5); z-index:200; }
.user-dropdown:hover .dropdown-menu { display:block; }
.dropdown-item { display:flex; align-items:center; gap:0.6rem; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.875rem; color:var(--white-80); text-decoration:none; cursor:pointer; transition:all 0.15s; background:none; border:none; width:100%; text-align:left; font-family:var(--font-body); }
.dropdown-item:hover { background:var(--cyan-dim); color:var(--cyan); }
.dropdown-item.danger:hover { background:rgba(239,68,68,0.1); color:#ef4444; }
.dropdown-sep { height:1px; background:var(--border); margin:0.3rem 0; }

.flash-success { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3); color:#22c55e; padding:0.8rem 1.2rem; border-radius:10px; font-size:0.875rem; margin:1rem 2rem 0; }
.flash-error { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#ef4444; padding:0.8rem 1.2rem; border-radius:10px; font-size:0.875rem; margin:1rem 2rem 0; }

.footer { background:var(--navy2); border-top:1px solid var(--border); padding:2.5rem 2rem; display:grid; grid-template-columns:1fr auto; gap:2rem; align-items:center; }
.footer-logo { font-family:var(--font-display); font-size:1.1rem; font-weight:800; color:var(--cyan); margin-bottom:0.4rem; }
.footer-copy { font-size:0.78rem; color:var(--white-40); }
.footer-links { display:flex; gap:1.5rem; }
.footer-links a { color:var(--white-50); font-size:0.8rem; text-decoration:none; transition:color 0.2s; }
.footer-links a:hover { color:var(--cyan); }
</style>
@stack('styles')
</head>
<body>

<nav class="nav">
  <a href="{{ route('home') }}" class="logo">MarketAd World</a>
  <div style="display:flex;gap:0.5rem;align-items:center;">
    <a href="{{ route('annonces.index') }}" class="nav-btn">Annonces</a>
    @auth
      <a href="{{ route('annonces.create') }}" class="btn-cyan" style="margin-left:0.5rem;">+ Publier</a>
      <div class="user-dropdown" style="margin-left:0.5rem;">
        <button class="user-dropdown-toggle">
          <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
          <span>{{ Auth::user()->name }}</span>
        </button>
        <div class="dropdown-menu">
          <a href="{{ route('annonces.mes') }}" class="dropdown-item">📋 Mes annonces</a>
          <a href="{{ route('profile.edit') }}" class="dropdown-item">⚙️ Mon profil</a>
          <div class="dropdown-sep"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item danger">🚪 Déconnexion</button>
          </form>
        </div>
      </div>
    @else
      <a href="{{ route('login') }}" class="nav-btn">Connexion</a>
      <a href="{{ route('register') }}" class="btn-cyan" style="margin-left:0.25rem;">S'inscrire</a>
    @endauth
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
  <div>
    <div class="footer-logo">MarketAd World</div>
    <div class="footer-copy">© {{ date('Y') }} MarketAd World. Tous droits réservés.</div>
  </div>
  <div class="footer-links">
    <a href="{{ route('home') }}">Accueil</a>
    <a href="{{ route('annonces.index') }}">Annonces</a>
    @auth
      <a href="{{ route('annonces.create') }}">Publier</a>
    @endauth
  </div>
</footer>

@stack('scripts')
</body>
</html>