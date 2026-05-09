@extends('layouts.app')

@section('title', 'Toutes les annonces — MarketAd World')

@push('styles')
<style>
.page-container { max-width: 1280px; margin: 2rem auto; padding: 0 2rem; display: grid; grid-template-columns: 260px 1fr; gap: 2.5rem; align-items: start; }

/* FILTERS SIDEBAR */
.filter-sidebar { background: white; border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid var(--border); position: sticky; top: 100px; box-shadow: var(--shadow-sm); }
.filter-title { font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
.filter-group { margin-bottom: 1.5rem; }
.filter-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }

.form-input, .form-select { width: 100%; background: #f8fafc; border: 1px solid var(--border); color: var(--text-main); font-family: var(--font-main); font-size: 0.95rem; padding: 0.6rem 0.8rem; border-radius: var(--radius-md); outline: none; transition: all 0.2s; }
.form-input:focus, .form-select:focus { border-color: var(--primary); background: white; box-shadow: 0 0 0 3px var(--primary-light); }

.btn-filter { width: 100%; background: var(--primary); color: white; border: none; font-weight: 600; padding: 0.75rem; border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; margin-top: 1rem; }
.btn-filter:hover { background: var(--primary-hover); }

/* MAIN CONTENT */
.results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.results-title { font-size: 1.5rem; font-weight: 700; color: var(--text-main); }
.results-count { color: var(--text-muted); font-weight: 500; }

.ad-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; }

/* AD CARDS (Shared styles) */
.ad-card { background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; text-decoration: none; color: var(--text-main); transition: all 0.2s; display: flex; flex-direction: column; }
.ad-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--primary-light); }
.ad-card-img { width: 100%; height: 180px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #cbd5e1; position: relative; border-bottom: 1px solid var(--border); }
.ad-card-img img { width: 100%; height: 100%; object-fit: cover; }
.ad-badge { position: absolute; top: 0.75rem; left: 0.75rem; background: rgba(255, 255, 255, 0.95); padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; color: var(--text-main); box-shadow: var(--shadow-sm); }
.ad-card-content { padding: 1rem; display: flex; flex-direction: column; flex: 1; }
.ad-card-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.4; color: var(--text-main); }
.ad-card-price { font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-top: auto; margin-bottom: 0.75rem; }
.ad-card-meta { display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 0.75rem; }

@media(max-width: 900px) {
  .page-container { grid-template-columns: 1fr; }
  .filter-sidebar { position: static; }
}
</style>
@endpush

@section('content')
<div class="page-container">

  {{-- FILTERS --}}
  <aside class="filter-sidebar">
    <div class="filter-title">🔍 Filtrer les annonces</div>
    <form method="GET" action="{{ route('annonces.index') }}">
      
      <div class="filter-group">
        <label class="filter-label">Recherche</label>
        <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Que cherchez-vous ?">
      </div>

      <div class="filter-group">
        <label class="filter-label">Catégorie</label>
        <select name="categorie" class="form-select">
          <option value="">Toutes catégories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
              {{ $cat->nom }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="filter-group">
        <label class="filter-label">Prix maximum (DH)</label>
        <input type="number" name="prix_max" value="{{ request('prix_max') }}" class="form-input" placeholder="Ex: 5000">
      </div>

      <div class="filter-group">
        <label class="filter-label">Trier par</label>
        <select name="tri" class="form-select">
          <option value="recent" {{ request('tri') == 'recent' ? 'selected' : '' }}>Plus récentes</option>
          <option value="prix_asc" {{ request('tri') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
          <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
        </select>
      </div>

      <button type="submit" class="btn-filter">Appliquer les filtres</button>
      
      @if(request()->anyFilled(['q', 'categorie', 'prix_max', 'tri']))
        <a href="{{ route('annonces.index') }}" style="display:block; text-align:center; margin-top:1rem; color:var(--text-muted); font-size:0.85rem; text-decoration:none;">Réinitialiser</a>
      @endif
    </form>
  </aside>

  {{-- MAIN RESULTS --}}
  <div class="main-content">
    
    <div class="results-header">
      <h1 class="results-title">Annonces</h1>
      <div class="results-count">{{ $annonces->total() }} résultat(s)</div>
    </div>

    @if($annonces->isEmpty())
      <div style="text-align:center; padding: 4rem; background: white; border-radius: var(--radius-lg); border: 1px dashed var(--border);">
        <p style="color:var(--text-muted); margin-bottom: 1rem; font-size:1.1rem;">Aucune annonce ne correspond à vos critères.</p>
        <a href="{{ route('annonces.index') }}" class="btn-primary">Effacer les filtres</a>
      </div>
    @else
      <div class="ad-grid">
        @foreach($annonces as $annonce)
          <a href="{{ route('annonces.show', $annonce) }}" class="ad-card">
            <div class="ad-card-img">
              @if($annonce->photos->count() > 0)
                <img src="{{ asset($annonce->photos->first()->url) }}" alt="{{ $annonce->titre }}">
              @else
                {{ $annonce->categorie->icone ?? '📦' }}
              @endif
              <div class="ad-badge">{{ $annonce->categorie->nom }}</div>
            </div>
            <div class="ad-card-content">
              <div class="ad-card-title">{{ Str::limit($annonce->titre, 50) }}</div>
              <div class="ad-card-price">{{ $annonce->prixFormate() }}</div>
              <div class="ad-card-meta">
                <span>📍 Maroc</span>
                <span>{{ $annonce->date_publication->format('d/m/Y') }}</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>

      <div style="margin-top: 3rem;">
        {{ $annonces->links() }}
      </div>
    @endif

  </div>

</div>
@endsection