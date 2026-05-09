@extends('layouts.app')
@section('title', 'Mes annonces — MarketAd World')

@push('styles')
<style>
.dash-layout { display:grid; grid-template-columns:240px 1fr; min-height:calc(100vh - 130px); }
.dash-sidebar { background:var(--navy2); border-right:1px solid var(--border); padding:1.5rem; }
.dash-user { display:flex; align-items:center; gap:0.8rem; margin-bottom:2rem; padding-bottom:1.5rem; border-bottom:1px solid var(--border); }
.dash-user .avatar { width:44px; height:44px; font-size:1rem; background:var(--cyan); color:var(--navy); font-weight:700; font-family:var(--font-display); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.dash-user-name { font-weight:600; font-size:0.9rem; }
.dash-user-role { font-size:0.75rem; color:var(--cyan); font-weight:500; }
.sidebar-nav { display:flex; flex-direction:column; gap:0.3rem; }
.sidebar-item {
  display:flex; align-items:center; gap:0.7rem;
  padding:0.65rem 0.85rem; border-radius:8px;
  font-size:0.875rem; font-weight:500; cursor:pointer;
  color:var(--white-60); transition:all 0.2s;
  border:none; background:none; width:100%; text-align:left;
  font-family:var(--font-body); text-decoration:none;
}
.sidebar-item:hover { background:var(--white-06); color:var(--white); }
.sidebar-item.active { background:var(--cyan-dim); color:var(--cyan); }
.sidebar-icon { font-size:1rem; opacity:0.9; }
.sidebar-badge { margin-left:auto; background:var(--cyan); color:var(--navy); font-size:0.7rem; font-weight:700; padding:0.1rem 0.45rem; border-radius:100px; }

/* MAIN */
.dash-main { padding:2rem; }
.dash-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:2rem; }
.dash-title { font-family:var(--font-display); font-size:1.4rem; font-weight:700; letter-spacing:-0.5px; }
.dash-subtitle { color:var(--white-50); font-size:0.875rem; margin-top:0.2rem; font-weight:300; }

/* Metrics */
.metrics-row { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2rem; }
.metric-card { background:var(--white-06); border:1px solid var(--border); border-radius:12px; padding:1.2rem; border-top:2px solid var(--cyan); }
.metric-card.success-top { border-top-color:var(--success); }
.metric-card.danger-top { border-top-color:var(--danger); }
.metric-label { font-size:0.75rem; color:var(--white-50); text-transform:uppercase; letter-spacing:0.08em; font-weight:600; margin-bottom:0.5rem; }
.metric-val { font-family:var(--font-display); font-size:1.8rem; font-weight:800; color:var(--white); letter-spacing:-1px; }
.metric-val.cyan { color:var(--cyan); }
.metric-val.success { color:var(--success); }
.metric-val.danger { color:var(--danger); }

/* Filtre statut pills */
.status-filters { display:flex; gap:0.5rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.status-pill-btn {
  padding:0.3rem 0.9rem; border-radius:100px;
  font-size:0.78rem; font-weight:600; cursor:pointer;
  border:1px solid var(--border); background:var(--white-06);
  color:var(--white-60); transition:all 0.2s; text-decoration:none;
  display:inline-block;
}
.status-pill-btn:hover { border-color:var(--border-cyan); color:var(--cyan); }
.status-pill-btn.active { background:var(--cyan-dim); border-color:var(--cyan); color:var(--cyan); }

/* Table card */
.table-card { background:var(--white-06); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
.table-card-header { padding:1rem 1.2rem; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
.table-card-title { font-family:var(--font-display); font-size:0.95rem; font-weight:700; }

/* Annonce row */
.annonce-row {
  display:flex; align-items:center; gap:1rem;
  padding:1rem 1.2rem;
  border-bottom:1px solid var(--border);
  transition:background 0.15s;
}
.annonce-row:last-child { border-bottom:none; }
.annonce-row:hover { background:var(--white-06); }
.annonce-thumb { width:56px; height:48px; background:var(--navy3); border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:1.4rem; overflow:hidden; }
.annonce-thumb img { width:100%; height:100%; object-fit:cover; }
.annonce-info { flex:1; min-width:0; }
.annonce-title { font-weight:600; font-size:0.9rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.annonce-meta { color:var(--white-50); font-size:0.78rem; margin-top:0.2rem; }
.annonce-actions { display:flex; gap:0.5rem; flex-shrink:0; }

/* Status pills */
.status-pill { display:inline-block; font-size:0.7rem; font-weight:600; padding:0.2rem 0.6rem; border-radius:100px; letter-spacing:0.04em; }
.status-active { background:rgba(34,197,94,0.15); color:#22c55e; border:1px solid rgba(34,197,94,0.3); }
.status-inactive { background:rgba(255,255,255,0.06); color:var(--white-50); border:1px solid var(--border); }
.status-vendue { background:rgba(239,68,68,0.12); color:#ef4444; border:1px solid rgba(239,68,68,0.3); }

/* Btns actions */
.action-btn-sm {
  background:none; border:1px solid var(--border);
  color:var(--white-60); font-size:0.75rem;
  padding:0.25rem 0.6rem; border-radius:5px;
  cursor:pointer; font-family:var(--font-body);
  transition:all 0.15s; text-decoration:none; display:inline-block;
}
.action-btn-sm:hover { border-color:var(--border-cyan); color:var(--cyan); }
.action-btn-sm.danger:hover { border-color:rgba(239,68,68,0.5); color:#ef4444; }

/* Empty */
.empty-state { text-align:center; padding:4rem 2rem; }
</style>
@endpush

@section('content')

<div class="dash-layout">

  {{-- SIDEBAR --}}
  <aside class="dash-sidebar">
    <div class="dash-user">
      <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
      <div>
        <div class="dash-user-name">{{ Auth::user()->name }}</div>
        <div class="dash-user-role">Membre actif</div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <a href="{{ route('annonces.mes') }}" class="sidebar-item active">
        <span class="sidebar-icon">📋</span> Mes annonces
        @if($stats['actives'] > 0)
          <span class="sidebar-badge">{{ $stats['actives'] }}</span>
        @endif
      </a>
      <a href="{{ route('annonces.create') }}" class="sidebar-item">
        <span class="sidebar-icon">➕</span> Publier
      </a>
      <a href="{{ route('profile.edit') }}" class="sidebar-item">
        <span class="sidebar-icon">⚙️</span> Mon profil
      </a>
      <div style="height:1px;background:var(--border);margin:0.8rem 0;"></div>
      <a href="{{ route('home') }}" class="sidebar-item">
        <span class="sidebar-icon">🌐</span> Voir le site
      </a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-item" style="color:rgba(239,68,68,0.7);">
          <span class="sidebar-icon">🚪</span> Déconnexion
        </button>
      </form>
    </nav>
  </aside>

  {{-- MAIN --}}
  <main class="dash-main">
    <div class="dash-header">
      <div>
        <div class="dash-title">Mes annonces</div>
        <div class="dash-subtitle">Gérez et suivez vos publications sur MarketAd World.</div>
      </div>
      <a href="{{ route('annonces.create') }}" class="btn-cyan" style="font-size:0.875rem;">+ Nouvelle annonce</a>
    </div>

    {{-- Métriques --}}
    <div class="metrics-row">
      <div class="metric-card">
        <div class="metric-label">Total</div>
        <div class="metric-val cyan">{{ $stats['total'] }}</div>
      </div>
      <div class="metric-card success-top">
        <div class="metric-label">Actives</div>
        <div class="metric-val success">{{ $stats['actives'] }}</div>
      </div>
      <div class="metric-card danger-top">
        <div class="metric-label">Vendues</div>
        <div class="metric-val danger">{{ $stats['vendues'] }}</div>
        </div>
    </div>

    {{-- Filtres statut --}}
    <div class="status-filters">
@foreach(['all' => 'Toutes', 'active' => '✅ Actives', 'inactive' => '⏸️ Inactives', 'vendue' => '✓ Vendues'] as $val => $label)
        <a href="{{ route('annonces.mes', $val !== 'all' ? ['statut' => $val] : []) }}"
                    class="status-pill-btn {{ (request('statut', 'all') === $val || ($val === 'all' && !request('statut'))) ? 'active' : '' }}">
    {{ $label }}
        </a>
@endforeach
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-card-header">
        <div class="table-card-title">📋 Liste des annonces</div>
        <span style="font-size:0.78rem;color:var(--white-50);">{{ $annonces->total() }} résultat(s)</span>
        </div>

@if($annonces->isEmpty())
        <div class="empty-state">
          <div style="font-size:3rem;margin-bottom:1rem;">📭</div>
          <div style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:0.5rem;">Aucune annonce</div>
          <p style="color:var(--white-50);font-size:0.875rem;margin-bottom:1.5rem;">Publiez votre première annonce gratuitement.</p>
          <a href="{{ route('annonces.create') }}" class="btn-cyan">+ Publier une annonce</a>
        </div>
      @else
        @foreach($annonces as $annonce)
          <div class="annonce-row">
            <div class="annonce-thumb">
              @if($annonce->image)
                <img src="{{ asset('storage/' . $annonce->image) }}" alt="{{ $annonce->titre }}">
              @else
                {{ $annonce->categorie->icone ?? '📦' }}
              @endif
            </div>
            <div class="annonce-info">
              <div class="annonce-title">{{ $annonce->titre }}</div>
              <div class="annonce-meta">
                <span class="status-pill status-{{ $annonce->statut }}">
                  {{ $annonce->statut === 'active' ? '✅ Active' : ($annonce->statut === 'vendue' ? '✓ Vendue' : '⏸️ Inactive') }}
                </span>
                &nbsp;·&nbsp; {{ $annonce->categorie->nom ?? '' }}
                &nbsp;·&nbsp; {{ $annonce->prixFormate() }}
                @if($annonce->ville) &nbsp;·&nbsp; 📍 {{ $annonce->ville }} @endif
                &nbsp;·&nbsp; {{ $annonce->created_at->format('d/m/Y') }}
              </div>
            </div>
            <div class="annonce-actions">
              <a href="{{ route('annonces.show', $annonce) }}" class="action-btn-sm" title="Voir">👁️ Voir</a>
              <a href="{{ route('annonces.edit', $annonce) }}" class="action-btn-sm" title="Modifier">✏️ Modifier</a>
              <form method="POST" action="{{ route('annonces.destroy', $annonce) }}"
                    onsubmit="return confirm('Supprimer « {{ addslashes($annonce->titre) }} » ?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn-sm danger" title="Supprimer">🗑️</button>
              </form>
            </div>
          </div>
        @endforeach

        {{-- Pagination --}}
        @if($annonces->hasPages())
          <div style="padding:1rem 1.2rem;border-top:1px solid var(--border);display:flex;justify-content:center;gap:0.4rem;">
            @foreach($annonces->getUrlRange(1, $annonces->lastPage()) as $page => $url)
              <a href="{{ $url }}"
                 style="width:32px;height:32px;border-radius:7px;border:1px solid {{ $page == $annonces->currentPage() ? 'var(--cyan)' : 'var(--border)' }};background:{{ $page == $annonces->currentPage() ? 'var(--cyan)' : 'var(--white-06)' }};color:{{ $page == $annonces->currentPage() ? 'var(--navy)' : 'var(--white-80)' }};font-size:0.82rem;display:flex;align-items:center;justify-content:center;text-decoration:none;font-weight:{{ $page == $annonces->currentPage() ? '700' : '400' }};transition:all 0.2s;">
                {{ $page }}
              </a>
            @endforeach
          </div>
        @endif
      @endif
    </div>
  </main>
</div>

@endsection