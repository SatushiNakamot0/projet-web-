<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — MarketAd World</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
:root {
  --navy: #091224; --navy2: #0B1B3D; --navy3: #0f2252;
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
body { background:var(--navy); color:var(--white); font-family:var(--font-body); font-size:15px; line-height:1.6; min-height:100vh; overflow-x:hidden; }
 
/* NAV */
.nav { background:rgba(9,18,36,0.82); backdrop-filter:blur(20px); border-bottom:1px solid rgba(0,178,255,0.12); position:sticky; top:0; z-index:100; padding:0 2.5rem; display:flex; align-items:center; justify-content:space-between; height:68px; }
.logo { font-family:var(--font-display); font-size:1.25rem; font-weight:800; color:var(--cyan); text-decoration:none; text-shadow:0 0 20px rgba(0,178,255,0.4); }
.nav-btn { background:none; border:none; color:var(--white-80); font-family:var(--font-body); font-size:0.875rem; font-weight:500; padding:0.4rem 0.9rem; border-radius:6px; cursor:pointer; text-decoration:none; display:inline-block; transition:all 0.2s; }
.nav-btn:hover { background:var(--white-10); color:var(--white); }
.btn-cyan { background:linear-gradient(135deg, var(--cyan) 0%, #0090d4 100%); color:var(--navy); font-family:var(--font-body); font-weight:700; font-size:0.875rem; border:none; padding:0.5rem 1.3rem; border-radius:8px; cursor:pointer; box-shadow:0 4px 14px rgba(0,178,255,0.3); text-decoration:none; display:inline-block; transition:all 0.25s; }
.btn-cyan:hover { background:linear-gradient(135deg, var(--cyan2) 0%, var(--cyan) 100%); transform:translateY(-2px); color:var(--navy); }
.btn-outline { background:rgba(0,178,255,0.05); color:var(--cyan); border:1px solid rgba(0,178,255,0.4); font-family:var(--font-body); font-size:0.875rem; font-weight:600; padding:0.5rem 1.3rem; border-radius:8px; cursor:pointer; text-decoration:none; display:inline-block; transition:all 0.25s; }
.btn-outline:hover { background:var(--cyan-dim); border-color:var(--cyan); color:var(--cyan); }
 
/* AUTH LAYOUT */
.auth-layout { min-height:calc(100vh - 68px); display:flex; align-items:stretch; }
 
/* Gauche décorative */
.auth-left {
  flex:1;
  background: linear-gradient(135deg, var(--navy2) 0%, #0a1a45 100%);
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  padding:3rem; border-right:1px solid var(--border);
  position:relative; overflow:hidden; text-align:center;
}
.auth-left::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 70% 60% at 50% 50%, rgba(0,178,255,0.07) 0%, transparent 70%); }
.auth-left-bg { position:absolute; inset:0; background-image:linear-gradient(rgba(0,178,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(0,178,255,0.04) 1px,transparent 1px); background-size:48px 48px; mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black 30%,transparent 80%); pointer-events:none; }
.auth-left-logo { font-family:var(--font-display); font-size:2rem; font-weight:800; color:var(--cyan); margin-bottom:0.5rem; position:relative; text-shadow:0 0 30px rgba(0,178,255,0.5); }
.auth-left-tagline { color:var(--white-50); font-size:0.9rem; font-weight:300; max-width:280px; position:relative; margin-bottom:2.5rem; }
.auth-feature { display:flex; align-items:center; gap:0.8rem; margin-bottom:1rem; position:relative; text-align:left; }
.auth-feature-icon { width:36px; height:36px; border-radius:8px; background:var(--cyan-dim); border:1px solid var(--border-cyan); display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.auth-feature-text { font-size:0.875rem; color:var(--white-80); }
 
/* Droite formulaire */
.auth-right { width:460px; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:3rem; }
.auth-title { font-family:var(--font-display); font-size:1.3rem; font-weight:800; margin-bottom:0.3rem; letter-spacing:-0.5px; width:100%; }
.auth-subtitle { color:var(--white-50); font-size:0.875rem; margin-bottom:1.5rem; font-weight:300; width:100%; }
 
/* Tabs */
.auth-tabs { display:flex; background:var(--white-06); border-radius:10px; padding:0.3rem; margin-bottom:2rem; width:100%; }
.auth-tab { flex:1; text-align:center; padding:0.55rem; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; transition:all 0.2s; color:var(--white-50); font-family:var(--font-body); border:none; background:none; text-decoration:none; display:block; }
.auth-tab.active { background:var(--cyan); color:var(--navy); }
.auth-tab:hover:not(.active) { color:var(--white); }
 
/* Form */
.auth-form { width:100%; }
.form-group { margin-bottom:1rem; }
.form-label { display:block; font-size:0.8rem; font-weight:600; color:var(--white-80); margin-bottom:0.4rem; letter-spacing:0.02em; }
.form-input {
  width:100%; background:var(--navy2); border:1px solid var(--border);
  color:var(--white); font-family:var(--font-body); font-size:0.9rem;
  padding:0.65rem 0.9rem; border-radius:8px; outline:none; transition:border-color 0.2s;
}
.form-input:focus { border-color:var(--border-cyan); box-shadow:0 0 0 3px rgba(0,178,255,0.08); }
.form-input::placeholder { color:var(--white-30); }
.form-input.error { border-color:rgba(239,68,68,0.5); }
.form-error { font-size:0.78rem; color:#ef4444; margin-top:0.3rem; }
 
.btn-auth { width:100%; background:var(--cyan); color:var(--navy); border:none; font-family:var(--font-display); font-weight:700; font-size:1rem; padding:0.85rem; border-radius:10px; cursor:pointer; transition:all 0.2s; margin-top:0.5rem; letter-spacing:0.02em; }
.btn-auth:hover { background:var(--cyan2); transform:translateY(-1px); }
 
.form-divider { display:flex; align-items:center; gap:0.8rem; margin:1.2rem 0; }
.form-divider::before, .form-divider::after { content:''; flex:1; height:1px; background:var(--border); }
.form-divider span { font-size:0.78rem; color:var(--white-40); }
 
.auth-helper { text-align:center; font-size:0.8rem; color:var(--white-50); margin-top:1.2rem; }
.auth-helper a { color:var(--cyan); text-decoration:none; }
.auth-helper a:hover { text-decoration:underline; }
 
/* Flash errors */
.alert-error { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#ef4444; padding:0.8rem 1rem; border-radius:8px; font-size:0.85rem; margin-bottom:1rem; width:100%; }
</style>
</head>
<body>
 
{{-- NAV --}}
<nav class="nav">
    <a href="/" class="logo">MarketAd World</a>
    <div style="display:flex;gap:0.5rem;align-items:center;">
    <a href="{{ route('login') }}" class="btn-outline">Connexion</a>
    <a href="{{ route('register') }}" class="btn-cyan">S'inscrire</a>
    </div>
</nav>
 
<div class="auth-layout">
 
  {{-- GAUCHE --}}
  <div class="auth-left">
    <div class="auth-left-bg"></div>
    <div class="auth-left-logo">MarketAd World</div>
    <p class="auth-left-tagline">La marketplace de référence au Maroc. Emploi, vente, services.</p>
    <div class="auth-feature">
      <div class="auth-feature-icon">🚀</div>
      <div class="auth-feature-text">Publiez gratuitement en moins de 2 minutes</div>
    </div>
    <div class="auth-feature">
      <div class="auth-feature-icon">📸</div>
      <div class="auth-feature-text">Photos HD, gestion complète de vos annonces</div>
    </div>
    <div class="auth-feature">
      <div class="auth-feature-icon">🛡️</div>
      <div class="auth-feature-text">Profils vérifiés, plateforme sécurisée</div>
    </div>
    <div class="auth-feature">
      <div class="auth-feature-icon">💬</div>
      <div class="auth-feature-text">Contactez les vendeurs directement</div>
    </div>
  </div>
 
  {{-- DROITE --}}
  <div class="auth-right">
    <div class="auth-title">Bienvenue !</div>
    <div class="auth-subtitle">Connectez-vous à votre compte.</div>
 
    {{-- Tabs --}}
    <div class="auth-tabs">
      <a href="{{ route('login') }}" class="auth-tab active">Connexion</a>
      <a href="{{ route('register') }}" class="auth-tab">Inscription</a>
    </div>
 
    {{-- Erreurs --}}
    @if($errors->any())
      <div class="alert-error">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif
 
    {{-- Formulaire --}}
    <form method="POST" action="{{ route('login') }}" class="auth-form">
      @csrf
 
      <div class="form-group">
        <label class="form-label">Adresse e-mail</label>
        <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               placeholder="vous@exemple.com" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="form-error">{{ $message }}</div> @enderror
      </div>
 
      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
               placeholder="••••••••" required>
        @error('password') <div class="form-error">{{ $message }}</div> @enderror
      </div>
 
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <label style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:var(--white-60);cursor:pointer;">
          <input type="checkbox" name="remember" style="accent-color:var(--cyan);"> Se souvenir de moi
        </label>
        @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" style="font-size:0.8rem;color:var(--cyan);text-decoration:none;">Mot de passe oublié ?</a>
        @endif
      </div>
 
      <button type="submit" class="btn-auth">Se connecter</button>
 
      <div class="form-divider"><span>ou</span></div>
 
      <div class="auth-helper">
        Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire gratuitement</a>
      </div>
    </form>
  </div>
</div>
 
</body>
</html>