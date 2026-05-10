@extends('layouts.app')

@section('title', 'MarketAd World — Petites annonces au Maroc')

@push('styles')
<style>
/* HERO SECTION */
.hero {
  background-color: var(--primary-light);
  background-image: radial-gradient(at 0% 0%, hsla(230,100%,74%,0.15) 0, transparent 50%), radial-gradient(at 100% 100%, hsla(240,100%,70%,0.1) 0, transparent 50%);
  padding: 6rem 2rem;
  text-align: center;
  border-bottom: 1px solid var(--border);
}
.hero-container {
  max-width: 800px;
  margin: 0 auto;
}
.hero-title {
  font-size: 3rem;
  font-weight: 800;
  color: var(--text-main);
  letter-spacing: -1px;
  line-height: 1.2;
  margin-bottom: 1.5rem;
}
.hero-title span {
  color: var(--primary);
}
.hero-subtitle {
  font-size: 1.125rem;
  color: var(--text-muted);
  margin-bottom: 3rem;
  font-weight: 400;
}

/* SEARCH BOX */
.search-box {
  background: white;
  padding: 0.5rem;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  display: flex;
  gap: 0.5rem;
  max-width: 700px;
  margin: 0 auto;
  border: 1px solid var(--border);
}
.search-input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 1rem 1.5rem;
  font-size: 1rem;
  font-family: var(--font-main);
  outline: none;
  color: var(--text-main);
}
.search-select {
  border: none;
  background: #f8fafc;
  padding: 0 1.5rem;
  font-family: var(--font-main);
  font-size: 0.95rem;
  color: var(--text-main);
  border-radius: var(--radius-md);
  outline: none;
  border-left: 1px solid var(--border);
  cursor: pointer;
}
.search-btn {
  background: var(--primary);
  color: white;
  border: none;
  padding: 0 2rem;
  border-radius: var(--radius-md);
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s;
}
.search-btn:hover {
  background: var(--primary-hover);
}

/* SECTION STRUCTURE */
.section {
  padding: 5rem 2rem;
}
.section-container {
  max-width: 1280px;
  margin: 0 auto;
}
.section-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2.5rem;
}
.section-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--text-main);
  letter-spacing: -0.5px;
}
.section-link {
  color: var(--primary);
  font-weight: 500;
  text-decoration: none;
  font-size: 0.95rem;
}
.section-link:hover {
  text-decoration: underline;
}

/* CATEGORIES */
.cat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 1rem;
}
.cat-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem 1rem;
  text-align: center;
  text-decoration: none;
  color: var(--text-main);
  transition: all 0.2s;
  box-shadow: var(--shadow-sm);
}
.cat-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
  border-color: var(--primary-light);
}
.cat-icon {
  font-size: 2rem;
  margin-bottom: 0.75rem;
}
.cat-name {
  font-weight: 600;
  font-size: 0.95rem;
}

/* AD CARDS */
.ad-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
}
.ad-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  text-decoration: none;
  color: var(--text-main);
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
}
.ad-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}
.ad-card-img {
  width: 100%;
  height: 200px;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  color: #cbd5e1;
  position: relative;
  border-bottom: 1px solid var(--border);
}
.ad-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.ad-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(4px);
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--text-main);
  box-shadow: var(--shadow-sm);
}
.ad-card-content {
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.ad-card-title {
  font-size: 1.05rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  line-height: 1.4;
  color: var(--text-main);
}
.ad-card-price {
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--primary);
  margin-top: auto;
  margin-bottom: 1rem;
}
.ad-card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
  color: var(--text-muted);
  border-top: 1px solid var(--border);
  padding-top: 1rem;
}
</style>
@endpush

@section('content')

{{-- HERO SECTION --}}
<section class="hero">
  <div class="hero-container">
    <h1 class="hero-title">Trouvez la bonne affaire <span>près de chez vous</span></h1>
    <p class="hero-subtitle">Des milliers d'annonces au Maroc. Achetez et vendez en toute simplicité.</p>
    
    <form action="{{ route('annonces.index') }}" method="GET" class="search-box">
      <input type="text" name="q" placeholder="Que recherchez-vous ? (ex: iPhone, Voiture...)" class="search-input">
      <select name="categorie" class="search-select">
        <option value="">Toutes catégories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
        @endforeach
      </select>
      <button type="submit" class="search-btn">Rechercher</button>
    </form>
  </div>
</section>

{{-- CATEGORIES --}}
<section class="section">
  <div class="section-container">
    <div class="section-header">
      <h2 class="section-title">Parcourir par catégories</h2>
    </div>
    
    <div class="cat-grid">
      @php
        $lucideIcons = [
          'Immobilier' => 'home',
          'Véhicules' => 'car',
          'Électronique' => 'smartphone',
          'Emploi' => 'briefcase',
          'Habillement' => 'shirt',
          'Maison & Jardin' => 'armchair',
          'Sports & Loisirs' => 'bike',
          'Autres' => 'package'
        ];
      @endphp
      @foreach($categories as $cat)
        <a href="{{ route('annonces.index', ['categorie' => $cat->id]) }}" class="cat-card">
          <div class="cat-icon" style="display:flex;align-items:center;justify-content:center;">
            <i data-lucide="{{ $lucideIcons[$cat->nom] ?? 'package' }}" style="width:36px;height:36px;stroke-width:1.5;color:var(--primary);"></i>
          </div>
          <div class="cat-name">{{ $cat->nom }}</div>
        </a>
      @endforeach
    </div>
  </div>
</section>

{{-- LATEST ADS --}}
<section class="section" style="background: white; border-top: 1px solid var(--border);">
  <div class="section-container">
    <div class="section-header">
      <h2 class="section-title">Dernières annonces publiées</h2>
      <a href="{{ route('annonces.index') }}" class="section-link">Voir tout →</a>
    </div>

    @if($annonces->isEmpty())
      <div style="text-align:center; padding: 4rem; color: var(--text-muted); background: var(--bg-color); border-radius: var(--radius-lg); border: 1px dashed var(--border);">
        <p style="font-size:1.1rem; margin-bottom:1rem;">Aucune annonce pour le moment.</p>
        @auth
          <a href="{{ route('annonces.create') }}" class="btn-primary">Publier la première annonce</a>
        @endauth
      </div>
    @else
      <div class="ad-grid">
        @foreach($annonces as $annonce)
        <a href="{{ route('annonces.show', $annonce) }}" class="ad-card">
            <div class="ad-card-img">
            @if($annonce->photos->count() > 0)
                <img src="{{ asset($annonce->photos->first()->url) }}" alt="{{ $annonce->titre }}">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--bg-color);color:var(--text-light);">
                  <i data-lucide="{{ $lucideIcons[$annonce->categorie->nom] ?? 'image' }}" style="width:48px;height:48px;stroke-width:1;"></i>
                </div>
            @endif
            <div class="ad-badge">{{ $annonce->categorie->nom }}</div>
            </div>
            <div class="ad-card-content">
              <div class="ad-card-title">{{ Str::limit($annonce->titre, 50) }}</div>
              <div class="ad-card-price">{{ $annonce->prixFormate() }}</div>
              <div class="ad-card-meta">
                  <span>📍 Maroc</span>
                  <span>🗓️ {{ $annonce->date_publication->diffForHumans() }}</span>
              </div>
            </div>
        </a>
        @endforeach
      </div>
    @endif
  </div>
</section>

@endsection