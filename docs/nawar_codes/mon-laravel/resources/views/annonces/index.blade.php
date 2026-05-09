@extends('layouts.app')
@section('title', 'Annonces — MarketAd World')

@push('styles')
<style>
.page-header { background:var(--navy2); border-bottom:1px solid var(--border); padding:1.5rem 2rem; }
.page-header h2 { font-family:var(--font-display); font-size:1.4rem; font-weight:700; letter-spacing:-0.5px; margin-bottom:0.8rem; }

/* Recherche inline dans le header */
.header-search {
  display:flex; gap:0.6rem; align-items:center;
  background: var(--navy);
  border: 1px solid var(--border-cyan);
  border-radius:10px;
  padding: 0.4rem 0.4rem 0.4rem 1rem;
  max-width:560px;
}
.header-search input {
  background:none; border:none; outline:none;
  color:var(--white); font-family:var(--font-body); font-size:0.9rem; flex:1;
}
.header-search input::placeholder { color:var(--white-30); }

/* LAYOUT */
.browse-layout { display:grid; grid-template-columns:260px 1fr; gap:0; min-height:calc(100vh - 200px); }

/* SIDEBAR */
.filter-sidebar {
  background: var(--navy2);
  border-right: 1px solid var(--border);
  padding: 1.5rem;
  position: sticky;
  top: 68px;
  height: fit-content;
}
.filter-title { font-family:var(--font-display); font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:var(--white-50); margin-bottom:1rem; }
.filter-group { margin-bottom:1.5rem; }
.filter-label { font-size:0.85rem; font-weight:600; color:var(--white-80); margin-bottom:0.6rem; display:block; }
.filter-input {
  width:100%;
  background: var(--navy);
  border: 1px solid var(--border);
  color: var(--white);
  font-family: var(--font-body);
  font-size: 0.875rem;
  padding: 0.55rem 0.8rem;
  border-radius: 8px;
  outline: none;
  transition: border-color 0.2s;
}
.filter-input:focus { border-color: var(--border-cyan); }
.filter-input option { background: var(--navy2); }
.filter-range { width:100%; accent-color:var(--cyan); }
.range-val { color:var(--cyan); font-weight:600; font-size:0.85rem; }

/* MAIN */
.ads-main { padding:1.5rem 2rem; }
.ads-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.2rem; }
.results-count { color:var(--white-50); font-size:0.875rem; }
.results-count strong { color:var(--white); }
.sort-select {
  background: var(--navy2);
  border: 1px solid var(--border);
  color: var(--white-80);
  font-family: var(--font-body);
  font-size: 0.8rem;
  padding: 0.4rem 0.7rem;
  border-radius: 6px;
  outline: none;
  cursor: pointer;
}
.sort-select option { background:var(--navy2); }

/* AD GRID 2 COL */
.ads-grid-2 { display:grid; grid-template-columns:repeat(2, 1fr); gap:1rem; }
.ad-card {
  background: linear-gradient(160deg,rgba(255,255,255,0.06) 0%,rgba(0,178,255,0.025) 100%);
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
  position: relative;
  text-decoration: none;
  display: block;
  color: var(--white);
}
.ad-card:hover { border-color:rgba(0,178,255,0.3); transform:translateY(-4px); box-shadow:0 16px 40px rgba(0,0,0,0.35),0 0 24px rgba(0,178,255,0.08); }
.ad-card-img { height:150px; background:linear-gradient(135deg,var(--navy3) 0%,var(--navy4) 100%); display:flex; align-items:center; justify-content:center; font-size:2.8rem; position:relative; overflow:hidden; }
.ad-card-img img { width:100%; height:100%; object-fit:cover; }
.ad-badge { position:absolute; top:0.6rem; left:0.6rem; font-size:0.68rem; font-weight:600; padding:0.2rem 0.55rem; border-radius:4px; letter-spacing:0.04em; text-transform:uppercase; z-index:1; }
.badge-vente { background:rgba(34,197,94,0.15); color:#22c55e; border:1px solid rgba(34,197,94,0.3); }
.badge-services { background:rgba(245,158,11,0.15); color:#f59e0b; border:1px solid rgba(245,158,11,0.3); }
.badge-emploi { background:rgba(0,178,255,0.2); color:var(--cyan); border:1px solid var(--border-cyan); }
.ad-body { padding:0.9rem 1rem 1rem; }
.ad-title { font-family:var(--font-display); font-size:0.9rem; font-weight:600; margin-bottom:0.35rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ad-desc { color:var(--white-50); font-size:0.78rem; margin-bottom:0.7rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.ad-footer { display:flex; justify-content:space-between; align-items:center; }
.ad-price { color:var(--cyan); font-weight:700; font-family:var(--font-display); font-size:0.95rem; }
.ad-loc { color:var(--white-40); font-size:0.75rem; }

/* PAGINATION */
.pagination { display:flex; justify-content:center; gap:0.4rem; margin-top:2rem; }
.page-num {
  width:36px; height:36px; border-radius:8px;
  border:1px solid var(--border);
  background: var(--white-06);
  color: var(--white-80);
  font-size:0.85rem; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  transition:all 0.2s; font-family:var(--font-body);
  text-decoration:none;
}
.page-num:hover { border-color:var(--border-cyan); color:var(--cyan); }
.page-num.active { background:var(--cyan); color:var(--navy); border-color:var(--cyan); font-weight:700; }

/* EMPTY */
.empty-state { grid-column:1/-1; text-align:center; padding:4rem 2rem; background:var(--white-06); border:1px solid var(--border); border-radius:16px; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="page-header">
  <h2>
    @if(request('q'))
      Résultats pour <em style="color:var(--cyan);">"{{ request('q') }}"</em>
    @else
      Toutes les annonces
    @endif
  </h2>
  <form action="{{ route('annonces.index') }}" method="GET" class="header-search" style="max-width:560px;">
    @if(request('categorie'))<input type="hidden" name="categorie" value="{{ request('categorie') }}">@endif
    @if(request('ville'))<input type="hidden" name="ville" value="{{ request('ville') }}">@endif
    @if(request('prix_max'))<input type="hidden" name="prix_max" value="{{ request('prix_max') }}">@endif
    @if(request('tri'))<input type="hidden" name="tri" value="{{ request('tri') }}">@endif
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--white-40);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <input type="text" name="q" placeholder="Rechercher une annonce, ville..." value="{{ request('q') }}">
    <button type="submit" class="btn-cyan" style="padding:0.45rem 1rem;font-size:0.82rem;">Chercher</button>
  </form>
</div>

{{-- Layout principal --}}
<div class="browse-layout">

  {{-- SIDEBAR FILTRES --}}
  <aside class="filter-sidebar">
    <form action="{{ route('annonces.index') }}" method="GET">
      @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif

      <div class="filter-title">Filtres</div>

      {{-- Catégorie --}}
      <div class="filter-group">
        <label class="filter-label">Catégorie</label>
        <select name="categorie" class="filter-input">
          <option value="">Toutes</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
              {{ $cat->icone }} {{ $cat->nom }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Ville --}}
      <div class="filter-group">
        <label class="filter-label">Ville</label>
        <select name="ville" class="filter-input">
          <option value="">Toutes les villes</option>
          @foreach($villes as $ville)
            <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>{{ $ville }}</option>
          @endforeach
        </select>
      </div>

      {{-- Prix max --}}
      <div class="filter-group">
        <label class="filter-label">
          Budget max — <span class="range-val" id="range-label">{{ request('prix_max', 50000) }} DH</span>
        </label>
        <input type="range" name="prix_max" class="filter-range"
               min="0" max="500000" step="1000"
               value="{{ request('prix_max', 50000) }}"
               oninput="document.getElementById('range-label').textContent = Number(this.value).toLocaleString('fr') + ' DH'">
      </div>

      {{-- Tri --}}
      <div class="filter-group">
        <label class="filter-label">Trier par</label>
        <select name="tri" class="filter-input">
          <option value="recent" {{ request('tri', 'recent') == 'recent' ? 'selected' : '' }}>Plus récent</option>
          <option value="prix_asc" {{ request('tri') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
          <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
        </select>
      </div>

      <button type="submit" class="btn-cyan" style="width:100%;padding:0.6rem;font-size:0.875rem;">Appliquer</button>

      @if(request()->hasAny(['ville','prix_max','tri','q','categorie']))
        <a href="{{ route('annonces.index') }}" style="display:block;text-align:center;margin-top:0.8rem;font-size:0.8rem;color:var(--white-40);">✕ Réinitialiser</a>
      @endif
    </form>
  </aside>

  {{-- LISTE --}}
  <div class="ads-main">
    <div class="ads-toolbar">
      <p class="results-count">
        <strong>{{ $annonces->total() }}</strong> annonce{{ $annonces->total() > 1 ? 's' : '' }}
        @if(request('q')) pour <em>"{{ request('q') }}"</em>@endif
      </p>
      @auth
        <a href="{{ route('annonces.create') }}" class="btn-cyan" style="font-size:0.82rem;padding:0.45rem 1rem;">+ Publier</a>
      @endauth
    </div>

    @if($annonces->isEmpty())
      <div class="empty-state">
        <div style="font-size:3rem;margin-bottom:1rem;">🔍</div>
        <div style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:0.5rem;">Aucune annonce trouvée</div>
        <p style="color:var(--white-50);font-size:0.875rem;margin-bottom:1.5rem;">Essayez avec d'autres mots-clés ou modifiez vos filtres.</p>
        <a href="{{ route('annonces.index') }}" class="btn-outline">Voir toutes les annonces</a>
      </div>
    @else
      <div class="ads-grid-2">
        @foreach($annonces as $annonce)
          <a href="{{ route('annonces.show', $annonce) }}" class="ad-card">
            <div class="ad-card-img">
              @if($annonce->image)
                <img src="{{ asset('storage/' . $annonce->image) }}" alt="{{ $annonce->titre }}">
              @else
                {{ $annonce->categorie->icone ?? '📦' }}
              @endif
              <span class="ad-badge badge-vente">{{ $annonce->categorie->nom ?? '' }}</span>
            </div>
            <div class="ad-body">
              <div class="ad-title">{{ $annonce->titre }}</div>
              <div class="ad-desc">{{ $annonce->description }}</div>
              <div class="ad-footer">
                <span class="ad-price">{{ $annonce->prixFormate() }}</span>
                <span class="ad-loc">📍 {{ $annonce->ville ?? 'Maroc' }} · {{ $annonce->created_at->diffForHumans() }}</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="pagination">
        @if($annonces->onFirstPage())
          <span class="page-num" style="opacity:0.3;">‹</span>
        @else
          <a href="{{ $annonces->previousPageUrl() }}" class="page-num">‹</a>
        @endif

        @foreach($annonces->getUrlRange(max(1,$annonces->currentPage()-2), min($annonces->lastPage(),$annonces->currentPage()+2)) as $page => $url)
          <a href="{{ $url }}" class="page-num {{ $page == $annonces->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach

        @if($annonces->hasMorePages())
          <a href="{{ $annonces->nextPageUrl() }}" class="page-num">›</a>
        @else
          <span class="page-num" style="opacity:0.3;">›</span>
        @endif
      </div>
    @endif
  </div>
</div>

@endsection