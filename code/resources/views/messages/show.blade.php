@extends('layouts.app')

@section('title', 'Discussion avec ' . $user->prenom)

@push('styles')
<style>
.chat-container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; height: calc(100vh - 120px); display: flex; flex-direction: column; }

.chat-header { background: white; border: 1px solid var(--border); border-radius: var(--radius-lg) var(--radius-lg) 0 0; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; border-bottom: none; }
.chat-user-info { display: flex; align-items: center; gap: 1rem; }
.chat-avatar { width: 45px; height: 45px; border-radius: 50%; background: var(--primary-light); color: var(--primary); font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; }
.chat-name { font-size: 1.2rem; font-weight: 700; color: var(--text-main); }
.back-link { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: color 0.2s; }
.back-link:hover { color: var(--primary); }

.chat-messages { flex: 1; background: #f8fafc; border: 1px solid var(--border); padding: 2rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1.5rem; }

.msg-wrapper { display: flex; flex-direction: column; max-width: 75%; }
.msg-wrapper.received { align-self: flex-start; }
.msg-wrapper.sent { align-self: flex-end; align-items: flex-end; }

.msg-bubble { padding: 1rem 1.25rem; border-radius: 1.2rem; font-size: 0.95rem; line-height: 1.5; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.msg-wrapper.received .msg-bubble { background: white; color: var(--text-main); border: 1px solid var(--border); border-bottom-left-radius: 0.25rem; }
.msg-wrapper.sent .msg-bubble { background: var(--primary); color: white; border-bottom-right-radius: 0.25rem; }

.msg-info { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem; display: flex; gap: 0.5rem; align-items: center; }
.msg-wrapper.sent .msg-info { color: var(--text-light); }

.chat-input-area { background: white; border: 1px solid var(--border); border-top: none; border-radius: 0 0 var(--radius-lg) var(--radius-lg); padding: 1.5rem 2rem; }
.chat-form { display: flex; gap: 1rem; align-items: flex-end; }
.chat-textarea { flex: 1; background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1rem; font-family: var(--font-main); font-size: 0.95rem; resize: none; outline: none; transition: all 0.2s; min-height: 60px; max-height: 150px; }
.chat-textarea:focus { border-color: var(--primary); background: white; }
.btn-send { background: var(--primary); color: white; border: none; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 1.5rem; flex-shrink: 0; box-shadow: var(--shadow-sm); }
.btn-send:hover { background: var(--primary-hover); transform: scale(1.05); box-shadow: var(--shadow-md); }

/* Custom Scrollbar for messages */
.chat-messages::-webkit-scrollbar { width: 6px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.chat-messages::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<div class="chat-container">
  
  <div class="chat-header">
    <div class="chat-user-info">
      <div class="chat-avatar">{{ strtoupper(substr($user->nom, 0, 2)) }}</div>
      <div class="chat-name">{{ $user->prenom }} {{ $user->nom }}</div>
    </div>
    <a href="{{ route('messages.index') }}" class="back-link">← Retour aux messages</a>
  </div>

  <div class="chat-messages" id="chatBox">
    @if($messages->isEmpty())
      <div style="text-align:center; color:var(--text-muted); margin-top:2rem;">
        <p>Envoyez un message pour démarrer la discussion.</p>
      </div>
    @endif

    @php $lastDate = null; @endphp

    @foreach($messages as $msg)
      @php 
        $currentDate = \Carbon\Carbon::parse($msg->date_envoi)->format('Y-m-d'); 
        $isSent = $msg->id_expediteur == Auth::id();
      @endphp

      @if($lastDate !== $currentDate)
        <div style="text-align:center; margin:1rem 0;">
          <span style="background:var(--border); color:var(--text-muted); font-size:0.75rem; font-weight:600; padding:0.25rem 0.75rem; border-radius:50px;">
            {{ \Carbon\Carbon::parse($msg->date_envoi)->isToday() ? "Aujourd'hui" : \Carbon\Carbon::parse($msg->date_envoi)->translatedFormat('d M Y') }}
          </span>
        </div>
        @php $lastDate = $currentDate; @endphp
      @endif

      <div class="msg-wrapper {{ $isSent ? 'sent' : 'received' }}">
        <div class="msg-bubble">
          {!! nl2br(e($msg->contenu)) !!}
        </div>
        <div class="msg-info">
          {{ \Carbon\Carbon::parse($msg->date_envoi)->format('H:i') }}
          @if($isSent)
            <span>{!! $msg->lu ? '<span style="color:#3b82f6;">✓✓</span>' : '<span>✓</span>' !!}</span>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <div class="chat-input-area">
    <form action="{{ route('messages.store', $user) }}" method="POST" class="chat-form">
      @csrf
      @if(request('annonce_id'))
        <input type="hidden" name="id_annonce" value="{{ request('annonce_id') }}">
      @endif
      <textarea name="contenu" class="chat-textarea" placeholder="Écrivez votre message ici..." required autofocus onkeydown="if(event.keyCode == 13 && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>
      <button type="submit" class="btn-send">➤</button>
    </form>
  </div>

</div>
@endsection

@push('scripts')
<script>
  // Scroll to bottom of chat automatically
  const chatBox = document.getElementById('chatBox');
  chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
