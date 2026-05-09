@extends('layouts.app')
@section('title', 'MarketAd World — Trouvez, vendez, échangez au Maroc')


@push('styles')
<style>
/* ── HERO ─────────────────────────────────────────────────────────── */
.hero {
  min-height: 90vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 5rem 2rem 4rem;
  position: relative;
  overflow: hidden;
}
.hero-bg {
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 100% 70% at 50% -10%, rgba(0,178,255,0.13) 0%, transparent 65%),
    radial-gradient(ellipse 50% 50% at 10% 90%, rgba(0,178,255,0.07) 0%, transparent 55%),
    radial-gradient(ellipse 40% 40% at 90% 80%, rgba(0,100,200,0.06) 0%, transparent 50%);
  pointer-events: none;
  animation: bgPulse 8s ease-in-out infinite;
}
@keyframes bgPulse { 0%,100%{opacity:1} 50%{opacity:.7} }
.hero-grid {
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(0,178,255,0.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0,178,255,0.06) 1px, transparent 1px);
  background-size: 64px 64px;
  mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 80%);
  -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 80%);
  pointer-events: none;
  animation: gridDrift 20s linear infinite;
}
@keyframes gridDrift { from{background-position:0 0} to{background-position:64px 64px} }
.badge-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  background: var(--cyan-dim);
    border: 1px solid var(--border-cyan);
    color: var(--cyan);
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.3rem 0.9rem;
    border-radius: 100px;
    margin-bottom: 1.5rem;
    position: relative;
    backdrop-filter: blur(8px);
    box-shadow: 0 0 20px rgba(0,178,255,0.15);
}
.badge-dot { width:6px; height:6px; background:var(--cyan); border-radius:50%; animation:pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
.hero h1 {
    font-family: var(--font-display);
    font-size: clamp(2.4rem, 6vw, 4.2rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1.5px;
    margin-bottom: 1.2rem;
    position: relative;
}
.hero h1 em { color: var(--cyan); font-style: normal; }
.hero-sub {
    font-size: 1.1rem;
    color: var(--white-50);
    max-width: 520px;
    margin-bottom: 2.5rem;
    font-weight: 300;
    position: relative;
}
/* SEARCH BAR */
.search-bar {
    background: rgba(11,27,61,0.9);
    border: 1px solid rgba(0,178,255,0.4);
    border-radius: 16px;
    backdrop-filter: blur(12px);
    display: flex;
    align-items: center;
    padding: 0.4rem 0.4rem 0.4rem 1.2rem;
    gap: 0.75rem;
    width: 100%;
    max-width: 680px;
    position: relative;
    animation: glowPulse 4s ease-in-out infinite;
}
@keyframes glowPulse {
    0%,100%{box-shadow:0 0 8px rgba(0,178,255,0.2)}
    50%{box-shadow:0 0 22px rgba(0,178,255,0.5)}
}
.search-bar input {
    background: none; border: none; outline: none;
    color: var(--white);
    font-family: var(--font-body);
    font-size: 1rem;
    flex: 1; min-width: 0;
}
.search-bar input::placeholder { color: var(--white-30); }
.search-divider { width:1px; height:28px; background:var(--border); flex-shrink:0; }
.search-bar select {
    background: none; border: none; outline: none;
    color: var(--white-80);
    font-family: var(--font-body);
    font-size: 0.875rem;
    cursor: pointer;
    padding: 0 0.5rem;
    -webkit-appearance: none;
}
.search-bar select option { background: var(--navy2); color:var(--white); }
.search-bar button {
    background: var(--cyan);
    color: var(--navy);
    border: none;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.9rem;
    padding: 0.7rem 1.6rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    letter-spacing: 0.02em;
    flex-shrink: 0;
}
.search-bar button:hover { background: var(--cyan2); }
.search-tags { display:flex; gap:0.6rem; flex-wrap:wrap; justify-content:center; margin-top:1rem; position:relative; }
.tag {
    background: var(--white-06);
    border: 1px solid var(--border);
    color: var(--white-50);
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
    border-radius: 100px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}
.tag:hover { border-color: var(--border-cyan); color: var(--cyan); background: var(--cyan-dim2); }

/* ── STATS BAR ────────────────────────────────────────────────────── */
.stats-bar {
    background: linear-gradient(180deg, var(--navy2) 0%, rgba(11,27,61,0.8) 100%);
    border-top: 1px solid rgba(0,178,255,0.1);
    border-bottom: 1px solid rgba(0,178,255,0.1);
    padding: 2.5rem 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.stats-bar::before {
    content:''; position:absolute; inset:0;
    background: radial-gradient(ellipse 60% 100% at 50% 0%, rgba(0,178,255,0.06) 0%, transparent 70%);
    pointer-events:none;
}
.stats-bar > div { position:relative; }
.stats-bar > div + div::before {
    content:''; position:absolute; left:0; top:20%; bottom:20%;
    width:1px;
    background: linear-gradient(to bottom, transparent, rgba(0,178,255,0.2), transparent);
}
.stat-num {
    font-family: var(--font-display);
    font-size: 2.4rem;
    font-weight: 800;
    color: var(--cyan);
    letter-spacing: -2px;
    display: block;
    text-shadow: 0 0 20px rgba(0,178,255,0.4);
}
.stat-label { color:var(--white-50); font-size:0.82rem; font-weight:400; margin-top:0.2rem; }

/* ── SECTIONS ─────────────────────────────────────────────────────── */
.section { padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
.section-title { font-family:var(--font-display); font-size:1.6rem; font-weight:700; margin-bottom:0.4rem; letter-spacing:-0.5px; }
.section-sub { color:var(--white-50); font-size:0.9rem; margin-bottom:2rem; font-weight:300; }

/* CATÉGORIES */
.cat-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:1.2rem; }
.cat-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(0,178,255,0.03) 100%);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 2rem 1.7rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(4px);
    text-decoration: none;
    display: block;
    color: var(--white);
}
.cat-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background: linear-gradient(90deg, var(--cyan), #005fa3);
    transform: scaleX(0); transform-origin: left;
    transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
    border-radius: 2px 2px 0 0;
}
.cat-card::after {
    content:''; position:absolute; inset:0;
    background: radial-gradient(ellipse 60% 60% at 50% 120%, rgba(0,178,255,0.08) 0%, transparent 70%);
    opacity:0; transition: opacity 0.3s;
}
.cat-card:hover { border-color:rgba(0,178,255,0.35); background:linear-gradient(135deg,rgba(0,178,255,0.07) 0%,rgba(0,178,255,0.03) 100%); transform:translateY(-4px) scale(1.01); box-shadow:0 12px 36px rgba(0,0,0,0.3),0 0 0 1px rgba(0,178,255,0.1); }
.cat-card:hover::before { transform:scaleX(1); }
.cat-card:hover::after { opacity:1; }
.cat-icon { font-size:2rem; margin-bottom:1rem; display:block; }
.cat-name { font-family:var(--font-display); font-size:1.1rem; font-weight:700; margin-bottom:0.3rem; }
.cat-count { color:var(--cyan); font-size:0.8rem; font-weight:500; }

/* ANNONCE CARDS */
.ads-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:1.3rem; }
.ad-card {
    background: linear-gradient(160deg, rgba(255,255,255,0.06) 0%, rgba(0,178,255,0.025) 100%);
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
.ad-card::after {
    content:''; position:absolute; inset:0; border-radius:16px;
    box-shadow:0 0 0 1px rgba(0,178,255,0) inset;
    transition:box-shadow 0.3s; pointer-events:none;
}
.ad-card:hover { border-color:rgba(0,178,255,0.3); transform:translateY(-5px) scale(1.01); box-shadow:0 16px 40px rgba(0,0,0,0.35),0 0 24px rgba(0,178,255,0.08); }
.ad-card:hover::after { box-shadow:0 0 0 1px rgba(0,178,255,0.15) inset; }
.ad-card-img {
    height: 170px;
    background: linear-gradient(135deg, var(--navy3) 0%, var(--navy4) 100%);
    display: flex; align-items: center; justify-content: center;
    font-size: 3.2rem;
    position: relative;
    overflow: hidden;
}
.ad-card-img img { width:100%; height:100%; object-fit:cover; }
.ad-card-img::after {
    content:''; position:absolute; bottom:0; left:0; right:0;
    height:40px;
    background: linear-gradient(to top, rgba(9,18,36,0.6), transparent);
}
.ad-badge {
    position:absolute; top:0.7rem; left:0.7rem;
    font-size:0.7rem; font-weight:600;
    padding:0.2rem 0.6rem; border-radius:4px;
    letter-spacing:0.04em; text-transform:uppercase; z-index:1;
}
.badge-vente { background:rgba(34,197,94,0.15); color:#22c55e; border:1px solid rgba(34,197,94,0.3); }
.badge-services { background:rgba(245,158,11,0.15); color:#f59e0b; border:1px solid rgba(245,158,11,0.3); }
.badge-emploi { background:rgba(0,178,255,0.2); color:var(--cyan); border:1px solid var(--border-cyan); }
.ad-body { padding:1rem 1.1rem 1.1rem; }
.ad-title { font-family:var(--font-display); font-size:0.95rem; font-weight:600; margin-bottom:0.4rem; color:var(--white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ad-desc { color:var(--white-50); font-size:0.8rem; margin-bottom:0.8rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.ad-footer { display:flex; justify-content:space-between; align-items:center; }
.ad-price { color:var(--cyan); font-weight:700; font-family:var(--font-display); font-size:1rem; }
.ad-loc { color:var(--white-40); font-size:0.78rem; }
</style>
@endpush

@section('content')

{{-- ── HERO ─────────────────────────────────────────────────────────── --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>

    <div class="badge-pill" style="position:relative;">
    <span class="badge-dot"></span>
    Marketplace #1 au Maroc
    </div>

    <h1>
    Achetez, vendez,<br>
    <em>connectez</em> partout<br>au Maroc.
    </h1>

    <p class="hero-sub">
    Des milliers d'annonces vérifiées — immobilier, véhicules, emploi, services et plus. Publiez gratuitement en 2 minutes.
    </p>

    {{-- Barre de recherche --}}
    <form action="{{ route('annonces.index') }}" method="GET" class="search-bar">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--white-30);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <input type="text" name="q" placeholder="Que recherchez-vous ?" value="{{ request('q') }}">
    <div class="search-divider"></div>
    <select name="categorie">
        <option value="">Toutes catégories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}">{{ $cat->icone }} {{ $cat->nom }}</option>
        @endforeach
    </select>
    <button type="submit">Rechercher</button>
    </form>

    <div class="search-tags" style="position:relative;">
    <span style="color:var(--white-40);font-size:0.78rem;">Tendances :</span>
    @foreach($categories->take(5) as $cat)
        <a href="{{ route('annonces.index', ['categorie' => $cat->id]) }}" class="tag">
        {{ $cat->icone }} {{ $cat->nom }}
        </a>
    @endforeach
    </div>
</section>

{{-- ── STATS BAR ──────────────────────────────────────────────────────── --}}
<div class="stats-bar">
    <div>
    <span class="stat-num">48 K+</span>
    <div class="stat-label">Annonces actives</div>
    </div>
    <div>
    <span class="stat-num">12 K+</span>
    <div class="stat-label">Membres inscrits</div>
    </div>
    <div>
    <span class="stat-num">{{ $categories->count() }}</span>
    <div class="stat-label">Catégories</div>
    </div>
    <div>
    <span class="stat-num">100%</span>
    <div class="stat-label">Gratuit</div>
    </div>
</div>

{{-- ── CATÉGORIES ─────────────────────────────────────────────────────── --}}
<div class="section">
    <div class="section-title">Parcourir par catégorie</div>
    <div class="section-sub">Trouvez exactement ce que vous cherchez parmi nos catégories.</div>
    <div class="cat-grid">
    @foreach($categories as $cat)
        <a href="{{ route('annonces.index', ['categorie' => $cat->id]) }}" class="cat-card">
        <span class="cat-icon">{{ $cat->icone }}</span>
        <div class="cat-name">{{ $cat->nom }}</div>
        <div class="cat-count">{{ $cat->annonces()->active()->count() }} annonces</div>
        </a>
    @endforeach
    </div>
</div>

{{-- ── ANNONCES RÉCENTES ──────────────────────────────────────────────── --}}
<div class="section" style="padding-top:0;">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:2rem;">
    <div>
        <div class="section-title">Annonces récentes</div>
        <div class="section-sub" style="margin-bottom:0;">Les dernières publications sur la plateforme.</div>
    </div>
    <a href="{{ route('annonces.index') }}" class="btn-outline" style="font-size:0.82rem;padding:0.4rem 1rem;">Voir tout →</a>
    </div>

    @if($annonces->isEmpty())
    <div style="text-align:center;padding:4rem 2rem;background:var(--white-06);border:1px solid var(--border);border-radius:16px;">
        <div style="font-size:3rem;margin-bottom:1rem;">📭</div>
        <div style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:0.5rem;">Aucune annonce pour le moment</div>
        <p style="color:var(--white-50);font-size:0.875rem;margin-bottom:1.5rem;">Soyez le premier à publier !</p>
        @auth
        <a href="{{ route('annonces.create') }}" class="btn-cyan">+ Publier une annonce</a>
        @else
        <a href="/register" class="btn-cyan">S'inscrire gratuitement</a>
        @endauth
    </div>
    @else
    <div class="ads-grid">
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
        <span class="ad-loc">📍 {{ $annonce->ville ?? 'Maroc' }}</span>
            </div>
            </div>
        </a>
    @endforeach
    </div>

    <div style="text-align:center;margin-top:2.5rem;">
        <a href="{{ route('annonces.index') }}" class="btn-outline">Voir toutes les annonces →</a>
    </div>
@endif
</div>

@endsection