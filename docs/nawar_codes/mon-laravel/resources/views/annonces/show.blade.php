@extends('layouts.app')
@section('title', $annonce->titre . ' — MarketAd World')

@push('styles')
<style>
.detail-layout { display:grid; grid-template-columns:1fr 340px; gap:2rem; padding:2rem; max-width:1100px; margin:0 auto; }
.detail-img {
  background: linear-gradient(135deg, var(--navy3) 0%, var(--navy4) 100%);
  border-radius: 14px;
  border: 1px solid var(--border);
  height: 340px;
  display: flex; align-items: center; justify-content: center;
  font-size: 5rem;
  margin-bottom: 1.5rem;
  position: relative;
  overflow: hidden;
}
.detail-img img { width:100%; height:100%; object-fit:cover; }
.detail-imgs-row { display:flex; gap:0.7rem; margin-bottom:1.5rem; }
.thumb {
  width:72px; height:56px;
  background: var(--navy3);
  border-radius: 8px;
  border: 1px solid var(--border);
  display:flex; align-items:center; justify-content:center;
  font-size:1.4rem; cursor:pointer; transition:border-color 0.2s;
}
.thumb.active { border-color:var(--cyan); }
.detail-title { font-family:var(--font-display); font-size:1.6rem; font-weight:700; letter-spacing:-0.5px; margin-bottom:0.75rem; }
.detail-meta { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
.meta-chip { display:flex; align-items:center; gap:0.35rem; font-size:0.8rem; color:var(--white-50); }
.detail-price { font-family:var(--font-display); font-size:2.2rem; font-weight:800; color:var(--cyan); letter-spacing:-1px; margin-bottom:1.5rem; text-shadow:0 0 20px rgba(0,178,255,0.4); }
.detail-desc { color:var(--white-80); line-height:1.8; font-weight:300; margin-bottom:2rem; }
.detail-section-title { font-family:var(--font-display); font-size:1rem; font-weight:700; color:var(--white-50); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.8rem; margin-top:1.5rem; }

/* Contact card */
.contact-card { background:var(--navy2); border:1px solid var(--border); border-radius:14px; padding:1.4rem; position:sticky; top:80px; }
.contact-price { font-family:var(--font-display); font-size:2rem; font-weight:800; color:var(--cyan); letter-spacing:-1px; margin-bottom:0.3rem; text-shadow:0 0 20px rgba(0,178,255,0.4); }
.btn-full {
  display:block; width:100%;
  background:var(--cyan); color:var(--navy);
  border:none; font-family:var(--font-display); font-weight:700;
  font-size:1rem; padding:0.9rem; border-radius:10px;
  cursor:pointer; margin-top:1rem; transition:all 0.2s; letter-spacing:0.02em;
  text-align:center; text-decoration:none;
}
.btn-full:hover { background:var(--cyan2); transform:translateY(-1px); color:var(--navy); }
.btn-full-out {
  display:block; width:100%;
  background:transparent; color:var(--white-80);
  border:1px solid var(--border);
  font-family:var(--font-body); font-weight:500;
  font-size:0.9rem; padding:0.7rem; border-radius:10px;
  cursor:pointer; margin-top:0.6rem; transition:all 0.2s;
  text-align:center; text-decoration:none;
}
.btn-full-out:hover { border-color:var(--border-cyan); color:var(--cyan); }

/* Seller */
.seller-row { display:flex; align-items:center; gap:0.8rem; margin:1.2rem 0; padding:1rem; background:var(--white-06); border-radius:10px; }
.avatar { width:40px; height:40px; border-radius:50%; background:var(--cyan); color:var(--navy); font-weight:700; font-size:0.9rem; display:flex; align-items:center; justify-content:center; font-family:var(--font-display); flex-shrink:0; }
.seller-name { font-weight:600; font-size:0.9rem; }
.seller-since { font-size:0.78rem; color:var(--white-50); }
.trust-row { display:flex; gap:0.8rem; margin-top:1rem; }
.trust-chip { flex:1; background:var(--white-06); border:1px solid var(--border); border-radius:8px; padding:0.6rem; text-align:center; font-size:0.72rem; color:var(--white-50); font-weight:500; }
.trust-chip strong { display:block; color:var(--white); font-size:0.9rem; }

/* Similaires */
.similaires-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
.ad-card-sm { background:linear-gradient(160deg,rgba(255,255,255,0.06) 0%,rgba(0,178,255,0.025) 100%); border:1px solid var(--border); border-radius:12px; overflow:hidden; transition:all 0.3s; text-decoration:none; display:block; color:var(--white); }
.ad-card-sm:hover { border-color:rgba(0,178,255,0.3); transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.3); }
.ad-card-sm-img { height:110px; background:linear-gradient(135deg,var(--navy3),var(--navy4)); display:flex; align-items:center; justify-content:center; font-size:2rem; overflow:hidden; }
.ad-card-sm-img img { width:100%; height:100%; object-fit:cover; }
.ad-card-sm-body { padding:0.75rem; }

/* Breadcrumb */
.breadcrumb { display:flex; align-items:center; gap:0.5rem; padding:1rem 2rem; font-size:0.8rem; color:var(--white-40); }
.breadcrumb a { color:var(--white-50); text-decoration:none; transition:color 0.2s; }
.breadcrumb a:hover { color:var(--cyan); }

/* Statut badge */
.status-active { background:rgba(34,197,94,0.15); color:#22c55e; border:1px solid rgba(34,197,94,0.3); font-size:0.72rem; font-weight:600; padding:0.2rem 0.7rem; border-radius:100px; letter-spacing:0.04em; }
.status-vendue { background:rgba(239,68,68,0.12); color:#ef4444; border:1px solid rgba(239,68,68,0.3); font-size:0.72rem; font-weight:600; padding:0.2rem 0.7rem; border-radius:100px; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
  <a href="{{ route('home') }}">Accueil</a>
  <span>›</span>
  <a href="{{ route('annonces.index', ['categorie' => $annonce->categorie_id]) }}">{{ $annonce->categorie->nom }}</a>
  <span>›</span>
  <span style="color:var(--white-70);">{{ Str::limit($annonce->titre, 50) }}</span>
</div>

<div class="detail-layout">

  {{-- ── Colonne principale ────────────────────────────────────────── --}}
  <div>
    {{-- Image principale --}}
    <div class="detail-img">
      @if($annonce->image)
        <img src="{{ asset('storage/' . $annonce->image) }}" alt="{{ $annonce->titre }}">
      @else
        {{ $annonce->categorie->icone ?? '📦' }}
      @endif
      @if($annonce->statut !== 'active')
        <div style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;">
          <span style="background:rgba(239,68,68,0.9);color:white;font-family:var(--font-display);font-weight:700;font-size:1.2rem;padding:0.5rem 1.5rem;border-radius:8px;">
            {{ $annonce->statut === 'vendue' ? '✓ VENDUE' : 'INACTIVE' }}
          </span>
        </div>
      @endif
    </div>

    {{-- Miniatures --}}
    <div class="detail-imgs-row">
      <div class="thumb active">{{ $annonce->categorie->icone ?? '📦' }}</div>
      <div class="thumb" style="opacity:0.3;">📷</div>
      <div class="thumb" style="opacity:0.3;">📷</div>
    </div>

    {{-- Titre & meta --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:0.75rem;">
      <div>
        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.6rem;">
          <span style="background:var(--cyan-dim);color:var(--cyan);border:1px solid var(--border-cyan);font-size:0.75rem;font-weight:600;padding:0.2rem 0.7rem;border-radius:4px;letter-spacing:0.04em;">
            {{ $annonce->categorie->icone }} {{ $annonce->categorie->nom }}
          </span>
          @if($annonce->statut === 'active')
            <span class="status-active">● Disponible</span>
          @elseif($annonce->statut === 'vendue')
            <span class="status-vendue">✓ Vendue</span>
          @endif
        </div>
        <h1 class="detail-title">{{ $annonce->titre }}</h1>
      </div>
    </div>

    <div class="detail-meta">
      @if($annonce->ville)
        <div class="meta-chip">📍 {{ $annonce->ville }}</div>
      @endif
      <div class="meta-chip">🕐 {{ $annonce->created_at->diffForHumans() }}</div>
      <div class="meta-chip">👤 {{ $annonce->user->name }}</div>
    </div>

    <div class="detail-price">{{ $annonce->prixFormate() }}</div>

    {{-- Actions propriétaire --}}
    @auth
      @if(Auth::id() === $annonce->user_id)
        <div style="display:flex;gap:0.7rem;margin-bottom:2rem;padding:1rem;background:rgba(0,178,255,0.06);border:1px solid var(--border-cyan);border-radius:12px;">
          <a href="{{ route('annonces.edit', $annonce) }}" class="btn-outline" style="font-size:0.85rem;padding:0.5rem 1rem;">✏️ Modifier</a>
          <form method="POST" action="{{ route('annonces.destroy', $annonce) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
            @csrf @method('DELETE')
            <button type="submit" style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#ef4444;font-family:var(--font-body);font-size:0.85rem;font-weight:600;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;transition:all 0.2s;">
              🗑️ Supprimer
            </button>
          </form>
        </div>
      @endif
    @endauth

    {{-- Description --}}
    <div class="detail-section-title">Description</div>
    <div class="detail-desc">{{ $annonce->description }}</div>

    {{-- Localisation --}}
    @if($annonce->ville)
      <div class="detail-section-title">Localisation</div>
      <div style="background:var(--white-06);border:1px solid var(--border);border-radius:10px;padding:1rem;display:flex;align-items:center;gap:0.7rem;color:var(--white-80);">
        <span style="font-size:1.5rem;">📍</span>
        <div>
          <div style="font-weight:600;">{{ $annonce->ville }}, Maroc</div>
          <div style="font-size:0.78rem;color:var(--white-50);">Retrouvez le vendeur en ville</div>
        </div>
      </div>
    @endif
  </div>

  {{-- ── Contact Card ──────────────────────────────────────────────── --}}
  <div>
    <div class="contact-card">
      <div style="font-size:0.75rem;color:var(--white-50);text-transform:uppercase;letter-spacing:0.08em;font-weight:600;margin-bottom:0.5rem;">Prix demandé</div>
      <div class="contact-price">{{ $annonce->prixFormate() }}</div>
      <div style="font-size:0.78rem;color:var(--white-40);margin-bottom:1rem;">Prix à discuter directement avec le vendeur</div>

      @auth
        @if(Auth::id() !== $annonce->user_id)
          <a href="mailto:{{ $annonce->user->email }}?subject=Annonce : {{ $annonce->titre }}" class="btn-full">
            ✉️ Contacter le vendeur
          </a>
          <button class="btn-full-out">📞 Voir le numéro</button>
        @else
          <a href="{{ route('annonces.edit', $annonce) }}" class="btn-full">✏️ Modifier mon annonce</a>
        @endif
      @else
        <a href="{{ route('login') }}" class="btn-full">Connectez-vous pour contacter</a>
        <a href="{{ route('register') }}" class="btn-full-out">Créer un compte gratuit</a>
      @endauth

      {{-- Vendeur --}}
      <div class="seller-row">
        <div class="avatar">{{ strtoupper(substr($annonce->user->name, 0, 2)) }}</div>
        <div>
          <div class="seller-name">{{ $annonce->user->name }}</div>
          <div class="seller-since">Membre depuis {{ $annonce->user->created_at->format('M Y') }}</div>
        </div>
      </div>

      <div class="trust-row">
        <div class="trust-chip"><strong>✓</strong>Profil vérifié</div>
        <div class="trust-chip"><strong>{{ $annonce->user->annonces()->count() }}</strong>Annonce(s)</div>
        <div class="trust-chip"><strong>⭐</strong>Fiable</div>
      </div>

      <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);text-align:center;">
        <button style="background:none;border:none;color:var(--white-40);font-size:0.78rem;cursor:pointer;font-family:var(--font-body);transition:color 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--white-40)'">
          🚩 Signaler cette annonce
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── Annonces similaires ─────────────────────────────────────────── --}}
@if($similaires->isNotEmpty())
  <div style="max-width:1100px;margin:0 auto;padding:0 2rem 3rem;">
    <div style="font-family:var(--font-display);font-size:1.3rem;font-weight:700;margin-bottom:1.2rem;letter-spacing:-0.5px;">
      Annonces similaires
    </div>
    <div class="similaires-grid">
      @foreach($similaires as $sim)
        <a href="{{ route('annonces.show', $sim) }}" class="ad-card-sm">
          <div class="ad-card-sm-img">
            @if($sim->image)
              <img src="{{ asset('storage/' . $sim->image) }}" alt="{{ $sim->titre }}">
            @else
              {{ $sim->categorie->icone ?? '📦' }}
            @endif
          </div>
          <div class="ad-card-sm-body">
            <div style="font-family:var(--font-display);font-size:0.85rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:0.3rem;">{{ $sim->titre }}</div>
            <div style="color:var(--cyan);font-family:var(--font-display);font-size:0.9rem;font-weight:700;">{{ $sim->prixFormate() }}</div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
@endif

@endsection