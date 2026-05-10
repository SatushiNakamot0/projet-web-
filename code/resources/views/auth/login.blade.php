<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — MarketAd World</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
:root {
  --bg-color: #f8fafc;
  --surface-color: #ffffff;
  --text-main: #0f172a;
  --text-muted: #64748b;
  --primary: #4f46e5;
  --primary-hover: #4338ca;
  --border: #e2e8f0;
  --border-focus: #818cf8;
  --danger: #ef4444;
  --danger-light: #fef2f2;
  --font-main: 'Inter', sans-serif;
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  --radius-lg: 1rem;
  --radius-md: 0.5rem;
}
* { margin:0; padding:0; box-sizing:border-box; }
body { background:var(--bg-color); color:var(--text-main); font-family:var(--font-main); font-size:15px; line-height:1.6; min-height:100vh; display: flex; flex-direction: column; }

/* NAV */
.nav { padding: 1.5rem 2rem; position: absolute; width: 100%; top: 0; }
.logo { font-size: 1.25rem; font-weight: 800; color: var(--primary); text-decoration: none; letter-spacing: -0.5px; display: flex; align-items: center; gap: 0; }
.logo img { height: 52px; width: auto; margin: -10px -8px -10px -12px; }

/* LAYOUT */
.auth-layout { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
.auth-card { background: var(--surface-color); width: 100%; max-width: 440px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); padding: 2.5rem; border: 1px solid var(--border); }

.auth-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main); letter-spacing: -0.5px; text-align: center; }
.auth-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; text-align: center; }

/* TABS */
.auth-tabs { display: flex; background: #f1f5f9; border-radius: var(--radius-md); padding: 0.25rem; margin-bottom: 2rem; }
.auth-tab { flex: 1; text-align: center; padding: 0.5rem; border-radius: 0.375rem; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s; color: var(--text-muted); text-decoration: none; }
.auth-tab.active { background: white; color: var(--text-main); box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.auth-tab:hover:not(.active) { color: var(--text-main); }

/* FORM */
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; }
.form-input { width: 100%; background: white; border: 1px solid var(--border); color: var(--text-main); font-family: var(--font-main); font-size: 0.95rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); outline: none; transition: border-color 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.form-input:focus { border-color: var(--border-focus); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
.form-input.error { border-color: var(--danger); }
.form-error { font-size: 0.8rem; color: var(--danger); margin-top: 0.4rem; }

.btn-auth { width: 100%; background: var(--primary); color: white; border: none; font-family: var(--font-main); font-weight: 600; font-size: 1rem; padding: 0.85rem; border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; margin-top: 0.5rem; box-shadow: 0 2px 4px rgba(79,70,229,0.3); }
.btn-auth:hover { background: var(--primary-hover); transform: translateY(-1px); }

.alert-error { background: var(--danger-light); border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: var(--radius-md); font-size: 0.9rem; margin-bottom: 1.5rem; }
</style>
</head>
<body>
 
<nav class="nav">
    <a href="/" class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="MarketAd World Logo">
        MarketAd World
    </a>
</nav>
 
<div class="auth-layout">
  <div class="auth-card">
    <div class="auth-title">Bon retour !</div>
    <div class="auth-subtitle">Connectez-vous pour gérer vos annonces.</div>
 
    <div class="auth-tabs">
      <a href="{{ route('login') }}" class="auth-tab active">Connexion</a>
      <a href="{{ route('register') }}" class="auth-tab">Inscription</a>
    </div>
 
    @if($errors->any())
      <div class="alert-error">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif
 
    <form method="POST" action="{{ route('login') }}">
      @csrf
 
      <div class="form-group">
        <label class="form-label">Adresse e-mail</label>
        <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               placeholder="vous@exemple.com" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="form-error">{{ $message }}</div> @enderror
      </div>
 
      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="mot_de_passe" class="form-input {{ $errors->has('mot_de_passe') ? 'error' : '' }}"
               placeholder="••••••••" required>
        @error('mot_de_passe') <div class="form-error">{{ $message }}</div> @enderror
      </div>
 
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:var(--text-muted);cursor:pointer;">
          <input type="checkbox" name="remember" style="accent-color:var(--primary); width:16px; height:16px;"> Se souvenir de moi
        </label>
        @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" style="font-size:0.875rem;color:var(--primary);text-decoration:none;font-weight:500;">Mot de passe oublié ?</a>
        @endif
      </div>
 
      <button type="submit" class="btn-auth">Se connecter</button>
    </form>
  </div>
</div>
 
</body>
</html>