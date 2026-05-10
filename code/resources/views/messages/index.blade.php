@extends('layouts.app')

@section('title', 'Messagerie — MarketAd World')

@push('styles')
<style>
.inbox-container { max-width: 1000px; margin: 2rem auto; padding: 0 1rem; }
.inbox-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.inbox-title { font-size: 1.75rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; }

.conv-list { display: flex; flex-direction: column; gap: 0.5rem; }
.conv-item { display: flex; align-items: center; gap: 1.5rem; padding: 1.25rem; background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); text-decoration: none; color: var(--text-main); transition: all 0.2s; box-shadow: var(--shadow-sm); }
.conv-item:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: var(--primary-light); }
.conv-item.unread { background: #f8fafc; border-left: 4px solid var(--primary); }

.conv-avatar { width: 50px; height: 50px; border-radius: 50%; background: var(--primary-light); color: var(--primary); font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.conv-content { flex: 1; min-width: 0; }
.conv-top { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem; }
.conv-name { font-weight: 700; font-size: 1.05rem; }
.conv-date { font-size: 0.8rem; color: var(--text-muted); }

.conv-bottom { display: flex; justify-content: space-between; align-items: center; }
.conv-msg { font-size: 0.9rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90%; }
.conv-item.unread .conv-msg { font-weight: 600; color: var(--text-main); }

.unread-badge { background: var(--primary); color: white; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 50px; }

.empty-state { text-align: center; padding: 4rem 2rem; background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); color: var(--text-muted); }
.empty-icon { font-size: 3.5rem; margin-bottom: 1rem; opacity: 0.5; }
</style>
@endpush

@section('content')
<div class="inbox-container">
  <div class="inbox-header">
    <h1 class="inbox-title">💬 Messagerie</h1>
  </div>

  @if(empty($conversations))
    <div class="empty-state">
      <div class="empty-icon">📭</div>
      <h3>Aucun message</h3>
      <p style="margin-top:0.5rem;">Vous n'avez pas encore de conversations.</p>
    </div>
  @else
    <div class="conv-list">
      @foreach($conversations as $conv)
        <a href="{{ route('messages.show', $conv['user']) }}" class="conv-item {{ $conv['unread_count'] > 0 ? 'unread' : '' }}">
          <div class="conv-avatar">{{ strtoupper(substr($conv['user']->nom, 0, 2)) }}</div>
          <div class="conv-content">
            <div class="conv-top">
              <span class="conv-name">{{ $conv['user']->prenom }} {{ $conv['user']->nom }}</span>
              <span class="conv-date">{{ \Carbon\Carbon::parse($conv['last_message']->date_envoi)->diffForHumans() }}</span>
            </div>
            <div class="conv-bottom">
              <span class="conv-msg">
                @if($conv['last_message']->id_expediteur == Auth::id())
                  <span style="opacity:0.6;">Vous :</span> 
                @endif
                {{ $conv['last_message']->contenu }}
              </span>
              @if($conv['unread_count'] > 0)
                <span class="unread-badge">{{ $conv['unread_count'] }}</span>
              @endif
            </div>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
