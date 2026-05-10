<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription — MarketAd World</title>
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
.auth-layout { flex: 1; display: flex; align-items: center; justify-content: center; padding: 5rem 2rem 2rem; }
.auth-card { background: var(--surface-color); width: 100%; max-width: 500px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); padding: 2.5rem; border: 1px solid var(--border); }

.auth-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main); letter-spacing: -0.5px; text-align: center; }
.auth-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; text-align: center; }

/* TABS */
.auth-tabs { display: flex; background: #f1f5f9; border-radius: var(--radius-md); padding: 0.25rem; margin-bottom: 2rem; }
.auth-tab { flex: 1; text-align: center; padding: 0.5rem; border-radius: 0.375rem; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s; color: var(--text-muted); text-decoration: none; }
.auth-tab.active { background: white; color: var(--text-main); box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.auth-tab:hover:not(.active) { color: var(--text-main); }

/* FORM */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
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
    <div class="auth-title">Créer un compte</div>
    <div class="auth-subtitle">Gratuit, rapide, visible immédiatement.</div>
 
    <div class="auth-tabs">
      <a href="{{ route('login') }}" class="auth-tab">Connexion</a>
      <a href="{{ route('register') }}" class="auth-tab active">Inscription</a>
    </div>
 
    @if($errors->any())
      <div class="alert-error">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif
 
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nom <span style="color:var(--danger);">*</span></label>
            <input type="text" name="nom" class="form-input {{ $errors->has('nom') ? 'error' : '' }}"
                   placeholder="Moussaoui" value="{{ old('nom') }}" required autofocus>
            @error('nom') <div class="form-error">{{ $message }}</div> @enderror
          </div>
    
          <div class="form-group">
            <label class="form-label">Prénom <span style="color:var(--danger);">*</span></label>
            <input type="text" name="prenom" class="form-input {{ $errors->has('prenom') ? 'error' : '' }}"
                   placeholder="Khalid" value="{{ old('prenom') }}" required>
            @error('prenom') <div class="form-error">{{ $message }}</div> @enderror
          </div>
      </div>
 
      <div class="form-group">
        <label class="form-label">Adresse e-mail <span style="color:var(--danger);">*</span></label>
        <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               placeholder="vous@exemple.com" value="{{ old('email') }}" required>
        @error('email') <div class="form-error">{{ $message }}</div> @enderror
      </div>
 
      <div class="form-group">
        <label class="form-label">Mot de passe <span style="color:var(--danger);">*</span></label>
        <input type="password" name="mot_de_passe" class="form-input {{ $errors->has('mot_de_passe') ? 'error' : '' }}"
               placeholder="8 caractères minimum" required>
        @error('mot_de_passe') <div class="form-error">{{ $message }}</div> @enderror
      </div>
 
      <div class="form-group">
        <label class="form-label">Confirmer le mot de passe <span style="color:var(--danger);">*</span></label>
        <input type="password" name="mot_de_passe_confirmation" class="form-input"
               placeholder="Répétez le mot de passe" required>
      </div>
 
      <button type="submit" class="btn-auth">Créer mon compte gratuit</button>
    </form>
  </div>
</div>
 
</body>
</html>