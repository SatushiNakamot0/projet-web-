@extends('layouts.app')

@section('title', 'Mes Annonces — MarketAd World')

@push('styles')
<style>
.dash-container { max-width: 1280px; margin: 2rem auto; padding: 0 2rem; display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start; }

/* SIDEBAR */
.dash-sidebar { background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow-sm); }
.dash-user { padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid var(--border); background: var(--bg-color); }
.avatar { width: 48px; height: 48px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem; }
.dash-user-name { font-weight: 600; font-size: 1.05rem; color: var(--text-main); }
.dash-user-role { font-size: 0.8rem; color: var(--text-muted); }

.dash-nav { padding: 1rem 0; }
.dash-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.5rem; color: var(--text-muted); text-decoration: none; font-weight: 500; border-left: 3px solid transparent; transition: all 0.2s; }
.dash-link:hover { background: #f8fafc; color: var(--primary); }
.dash-link.active { background: var(--primary-light); color: var(--primary); border-left-color: var(--primary); }

/* CONTENT */
.dash-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.dash-title { font-size: 1.5rem; font-weight: 700; color: var(--text-main); }

.stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
.stat-card { background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; box-shadow: var(--shadow-sm); }
.stat-value { font-size: 2rem; font-weight: 800; color: var(--primary); margin-bottom: 0.25rem; }
.stat-label { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }

/* TABLE LIST */
.annonce-list { display: flex; flex-direction: column; gap: 1rem; }
.annonce-row { background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1rem; display: flex; gap: 1.5rem; align-items: center; transition: all 0.2s; box-shadow: var(--shadow-sm); }
.annonce-row:hover { box-shadow: var(--shadow-md); border-color: var(--border-focus); }
.annonce-thumb { width: 100px; height: 100px; background: #f1f5f9; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 2rem; overflow: hidden; border: 1px solid var(--border); flex-shrink: 0; }
.annonce-thumb img { width: 100%; height: 100%; object-fit: cover; }
.annonce-details { flex: 1; }
.annonce-title { font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem; text-decoration: none; }
.annonce-title:hover { color: var(--primary); }
.annonce-meta { font-size: 0.85rem; color: var(--text-muted); display: flex; gap: 1rem; margin-bottom: 0.5rem; }
.annonce-price { font-weight: 700; color: var(--primary); font-size: 1rem; }

/* STATUT BADGES */
.badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.badge-publiee { background: var(--success-light); color: #065f46; }
.badge-attente { background: #fef3c7; color: #92400e; }
.badge-rejetee { background: var(--danger-light); color: #991b1b; }

.annonce-actions { display: flex; gap: 0.5rem; }
.action-btn { width: 36px; height: 36px; border-radius: var(--radius-md); background: #f8fafc; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--text-muted); transition: all 0.2s; }
.action-btn:hover { background: white; color: var(--primary); border-color: var(--primary); box-shadow: var(--shadow-sm); }
.action-btn.delete:hover { color: var(--danger); border-color: var(--danger); }

@media(max-width: 900px) {
  .dash-container { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="dash-container">

  {{-- SIDEBAR --}}
  <aside class="dash-sidebar">
    <div class="dash-user">
      <div class="avatar">{{ strtoupper(substr(Auth::user()->nom, 0, 2)) }}</div>
      <div>
        <div class="dash-user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
        <div class="dash-user-role">Membre actif</div>
      </div>
    </div>
    <nav class="dash-nav">
      <a href="{{ route('annonces.mine') }}" class="dash-link active">📋 Mes annonces</a>
      <a href="{{ route('annonces.create') }}" class="dash-link">➕ Nouvelle annonce</a>
      <a href="{{ route('profile.edit') }}" class="dash-link">⚙️ Mon profil</a>
    </nav>
  </aside>

  {{-- CONTENT --}}
  <div class="dash-content">
    
    <div class="dash-header">
      <h1 class="dash-title">Mes annonces</h1>
      <a href="{{ route('annonces.create') }}" class="btn-primary">+ Publier une annonce</a>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" style="color:var(--success);">{{ $stats['actives'] }}</div>
        <div class="stat-label">Actives</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" style="color:var(--text-muted);">{{ $stats['vendues'] }}</div>
        <div class="stat-label">Vendues</div>
      </div>
    </div>

    @if($annonces->isEmpty())
      <div style="text-align:center; padding: 4rem; background: white; border-radius: var(--radius-lg); border: 1px dashed var(--border);">
        <p style="color:var(--text-muted); margin-bottom: 1rem;">Vous n'avez pas encore publié d'annonce.</p>
        <a href="{{ route('annonces.create') }}" class="btn-outline">Publier maintenant</a>
      </div>
    @else
      <div class="annonce-list">
        @foreach($annonces as $annonce)
          <div class="annonce-row">
            <div class="annonce-thumb">
              @if($annonce->photos->count() > 0)
                <img src="{{ asset($annonce->photos->first()->url) }}" alt="{{ $annonce->titre }}">
              @else
                {{ $annonce->categorie->icone ?? '📦' }}
              @endif
            </div>
            
            <div class="annonce-details">
              <a href="{{ route('annonces.show', $annonce) }}" class="annonce-title" style="display:block;">{{ $annonce->titre }}</a>
              <div class="annonce-meta">
                <span>{{ $annonce->categorie->nom }}</span>
                <span>•</span>
                <span>{{ $annonce->date_publication->format('d/m/Y') }}</span>
              </div>
              <div style="display:flex; align-items:center; gap:1rem;">
                <span class="annonce-price">{{ $annonce->prixFormate() }}</span>
                <span class="badge badge-{{ str_replace('_', '-', $annonce->statut) }}">
                  {{ ucfirst(str_replace('_', ' ', $annonce->statut)) }}
                </span>
              </div>
            </div>

            <div class="annonce-actions">
              <a href="{{ route('annonces.show', $annonce) }}" class="action-btn" title="Voir">👁️</a>
              <a href="{{ route('annonces.edit', $annonce) }}" class="action-btn" title="Modifier">✏️</a>
              <form method="POST" action="{{ route('annonces.destroy', $annonce) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn delete" title="Supprimer">🗑️</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>

      <div style="margin-top:2rem;">
        {{ $annonces->links() }}
      </div>
    @endif

  </div>
</div>
@endsection