@extends('layouts.app')

@section('title', 'Mon profil — MarketAd World')

@push('styles')
<style>
.profile-container { max-width: 700px; margin: 2.5rem auto; padding: 0 2rem; }
.profile-title { font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main); letter-spacing: -0.5px; }
.profile-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2.5rem; }

.profile-card { background: var(--surface-color); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-sm); }
.card-title { font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem; }
.card-desc { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; }
.form-input { width: 100%; background: white; border: 1px solid var(--border); color: var(--text-main); font-family: var(--font-main); font-size: 0.95rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); outline: none; transition: border-color 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.form-input:focus { border-color: var(--border-focus); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
.form-error { font-size: 0.8rem; color: var(--danger); margin-top: 0.4rem; }

.btn-save { background: var(--primary); color: white; border: none; font-family: var(--font-main); font-weight: 600; font-size: 0.95rem; padding: 0.7rem 1.5rem; border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; box-shadow: var(--shadow-sm); }
.btn-save:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: var(--shadow-md); }

.btn-danger { background: var(--danger); color: white; border: none; font-family: var(--font-main); font-weight: 600; font-size: 0.95rem; padding: 0.7rem 1.5rem; border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; }
.btn-danger:hover { background: #dc2626; }

.saved-msg { font-size: 0.875rem; color: var(--success); font-weight: 500; }
</style>
@endpush

@section('content')
<div class="profile-container">

  <h1 class="profile-title">Mon profil</h1>
  <p class="profile-subtitle">Gérez vos informations personnelles et votre mot de passe.</p>

  {{-- SECTION 1 : Informations personnelles --}}
  <div class="profile-card">
    <div class="card-title">Informations personnelles</div>
    <div class="card-desc">Mettez à jour votre nom, prénom et adresse e-mail.</div>

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      @method('patch')

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nom</label>
          <input type="text" name="nom" class="form-input" value="{{ old('nom', $user->nom) }}" required>
          @error('nom') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Prénom</label>
          <input type="text" name="prenom" class="form-input" value="{{ old('prenom', $user->prenom) }}" required>
          @error('prenom') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Adresse e-mail</label>
        <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
        @error('email') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div style="display:flex;align-items:center;gap:1rem;">
        <button type="submit" class="btn-save">Enregistrer</button>
        @if (session('status') === 'profile-updated')
          <span class="saved-msg">✅ Sauvegardé !</span>
        @endif
      </div>
    </form>
  </div>

  {{-- SECTION 2 : Changer le mot de passe --}}
  <div class="profile-card">
    <div class="card-title">Changer le mot de passe</div>
    <div class="card-desc">Utilisez un mot de passe long et unique pour sécuriser votre compte.</div>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      @method('put')

      <div class="form-group">
        <label class="form-label">Mot de passe actuel</label>
        <input type="password" name="current_mot_de_passe" class="form-input" placeholder="••••••••">
        @error('current_mot_de_passe', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nouveau mot de passe</label>
          <input type="password" name="mot_de_passe" class="form-input" placeholder="8 caractères minimum">
          @error('mot_de_passe', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Confirmer</label>
          <input type="password" name="mot_de_passe_confirmation" class="form-input" placeholder="••••••••">
        </div>
      </div>

      <button type="submit" class="btn-save">Mettre à jour le mot de passe</button>
    </form>
  </div>

  {{-- SECTION 3 : Supprimer le compte --}}
  <div class="profile-card" style="border-color: #fecaca;">
    <div class="card-title" style="color: var(--danger);">Supprimer le compte</div>
    <div class="card-desc">Cette action est irréversible. Toutes vos annonces et données seront supprimées.</div>

    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
      @csrf
      @method('delete')

      <div class="form-group">
        <label class="form-label">Confirmez avec votre mot de passe</label>
        <input type="password" name="mot_de_passe" class="form-input" placeholder="Votre mot de passe actuel" required>
        @error('mot_de_passe', 'userDeletion') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn-danger">Supprimer mon compte</button>
    </form>
  </div>

</div>
@endsection
