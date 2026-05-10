@extends('layouts.app')

@section('title', $annonce->titre . ' — MarketAd World')

@push('styles')
<style>
.detail-container { max-width: 1100px; margin: 2rem auto; padding: 0 2rem; }
.breadcrumb { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 2rem; display: flex; gap: 0.5rem; align-items: center; }
.breadcrumb a { color: var(--text-main); text-decoration: none; font-weight: 500; }
.breadcrumb a:hover { color: var(--primary); }

.detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem; align-items: start; }

/* LEFT SIDE */
.detail-img { background: #f1f5f9; border-radius: var(--radius-lg); overflow: hidden; display: flex; align-items: center; justify-content: center; height: 500px; font-size: 5rem; border: 1px solid var(--border); margin-bottom: 2rem; }
.detail-img img { width: 100%; height: 100%; object-fit: contain; background: white; }

.detail-content { background: white; border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm); }
.detail-title { font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.3; color: var(--text-main); }
.detail-desc { font-size: 1rem; color: var(--text-muted); line-height: 1.7; white-space: pre-wrap; margin-bottom: 2rem; }
.detail-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); }
.meta-item { display: flex; flex-direction: column; gap: 0.25rem; }
.meta-label { font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
.meta-value { font-size: 1rem; color: var(--text-main); font-weight: 500; }

/* RIGHT SIDE (STICKY) */
.detail-sidebar { position: sticky; top: 100px; display: flex; flex-direction: column; gap: 1.5rem; }

.price-card { background: white; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: center; }
.price-value { font-size: 2rem; font-weight: 800; color: var(--primary); margin-bottom: 1.5rem; }
.btn-full { display: block; width: 100%; text-align: center; padding: 1rem; background: var(--primary); color: white; text-decoration: none; font-weight: 600; border-radius: var(--radius-md); transition: all 0.2s; box-shadow: var(--shadow-sm); }
.btn-full:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: var(--shadow-md); }

.user-card { background: white; padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border); display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-sm); }
.user-avatar { width: 48px; height: 48px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem; flex-shrink: 0; }
.user-info { flex: 1; }
.user-name { font-weight: 600; color: var(--text-main); font-size: 1rem; }
.user-date { font-size: 0.8rem; color: var(--text-muted); }

/* ACTIONS PROPRIO */
.owner-actions { background: #f8fafc; padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-focus); display: flex; gap: 1rem; }

/* SIMILAIRES */
.similaires-section { margin-top: 4rem; border-top: 1px solid var(--border); padding-top: 3rem; }
.similaires-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; color: var(--text-main); }
.sim-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }
.ad-card-sm { background: white; border-radius: var(--radius-md); overflow: hidden; text-decoration: none; border: 1px solid var(--border); transition: all 0.2s; }
.ad-card-sm:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.ad-card-sm-img { height: 160px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #cbd5e1; border-bottom: 1px solid var(--border); }
.ad-card-sm-img img { width: 100%; height: 100%; object-fit: cover; }
.ad-card-sm-content { padding: 1rem; }
.ad-card-sm-title { font-size: 0.95rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; }
.ad-card-sm-price { font-weight: 700; color: var(--primary); }

@media(max-width: 900px) {
  .detail-grid { grid-template-columns: 1fr; }
  .detail-sidebar { position: static; }
}
</style>
@endpush

@section('content')
<div class="detail-container">

  <div class="breadcrumb">
    <a href="{{ route('home') }}">Accueil</a>
    <span>›</span>
    <a href="{{ route('annonces.index', ['categorie' => $annonce->id_categorie]) }}">{{ $annonce->categorie->nom }}</a>
    <span>›</span>
    <span style="color:var(--text-muted);">{{ Str::limit($annonce->titre, 40) }}</span>
  </div>

  <div class="detail-grid">
    
    <div>
      <div class="detail-img">
        @if($annonce->photos->count() > 0)
          <img src="{{ asset($annonce->photos->first()->url) }}" alt="{{ $annonce->titre }}">
        @else
          {{ $annonce->categorie->icone ?? '📦' }}
        @endif
      </div>

      <div class="detail-content">
        <h1 class="detail-title">{{ $annonce->titre }}</h1>
        <div class="detail-desc">{{ $annonce->description }}</div>
        
        <div class="detail-meta-grid">
          <div class="meta-item">
            <span class="meta-label">Publié le</span>
            <span class="meta-value">{{ $annonce->date_publication->format('d/m/Y à H:i') }}</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Catégorie</span>
            <span class="meta-value">{{ $annonce->categorie->nom }}</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Localisation</span>
            <span class="meta-value">Maroc</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Référence</span>
            <span class="meta-value" style="font-family:monospace;">#{{ str_pad($annonce->id, 6, '0', STR_PAD_LEFT) }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="detail-sidebar">

      @auth
        @if(Auth::id() === $annonce->id_utilisateur)
          <div class="owner-actions">
            <a href="{{ route('annonces.edit', $annonce) }}" class="btn-outline" style="flex:1; text-align:center; display:flex; justify-content:center; align-items:center; gap:0.5rem;"><i data-lucide="edit-2" style="width:18px;height:18px;"></i> Modifier</a>
            <form method="POST" action="{{ route('annonces.destroy', $annonce) }}" onsubmit="return confirm('Supprimer cette annonce ?')" style="flex:1;">
              @csrf @method('DELETE')
              <button type="submit" class="btn-outline" style="width:100%; color:var(--danger); border-color:var(--danger-light); display:flex; justify-content:center; align-items:center; gap:0.5rem;"><i data-lucide="trash-2" style="width:18px;height:18px;"></i> Supprimer</button>
            </form>
          </div>
        @endif
      @endauth

      <div class="price-card">
        <div class="price-value">{{ $annonce->prixFormate() }}</div>
        @auth
          @if(Auth::id() !== $annonce->id_utilisateur)
            <a href="{{ route('messages.show', ['user' => $annonce->utilisateur->id, 'annonce_id' => $annonce->id]) }}" class="btn-full" style="display:flex; justify-content:center; align-items:center; gap:0.5rem;">
              <i data-lucide="mail" style="width:20px;height:20px;"></i> Contacter le vendeur
            </a>
          @endif
        @else
          <a href="{{ route('login') }}" class="btn-full">Connectez-vous pour contacter</a>
        @endauth
      </div>

      <div class="user-card">
        <div class="user-avatar">
          {{ strtoupper(substr($annonce->utilisateur->nom, 0, 2)) }}
        </div>
        <div class="user-info">
          <div class="user-name">{{ $annonce->utilisateur->prenom }} {{ $annonce->utilisateur->nom }}</div>
          <div class="user-date">Membre depuis {{ $annonce->utilisateur->created_at->format('M Y') }}</div>
        </div>
      </div>
    </div>

  </div>

  @if($similaires->isNotEmpty())
  <div class="similaires-section">
    <h2 class="similaires-title">Annonces similaires</h2>
    <div class="sim-grid">
      @foreach($similaires as $sim)
        <a href="{{ route('annonces.show', $sim) }}" class="ad-card-sm">
          <div class="ad-card-sm-img">
            @if($sim->photos->count() > 0)
              <img src="{{ asset($sim->photos->first()->url) }}" alt="{{ $sim->titre }}">
            @else
              {{ $sim->categorie->icone ?? '' }}
            @endif
          </div>
          <div class="ad-card-sm-content">
            <div class="ad-card-sm-title">{{ Str::limit($sim->titre, 35) }}</div>
            <div class="ad-card-sm-price">{{ $sim->prixFormate() }}</div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection