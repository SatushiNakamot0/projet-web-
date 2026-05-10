@extends('layouts.app')

@section('title', 'Gérer les annonces — Admin')

@push('styles')
<style>
.admin-container { max-width: 1280px; margin: 2rem auto; padding: 0 2rem; }
.admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.admin-title { font-size: 1.75rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; }
.admin-badge { background: var(--primary-light); color: var(--primary); font-size: 0.85rem; font-weight: 600; padding: 0.3rem 0.8rem; border-radius: 50px; }

.admin-tabs { display: flex; gap: 0.5rem; margin-bottom: 2rem; }
.admin-tab { padding: 0.5rem 1.2rem; border-radius: var(--radius-md); font-size: 0.9rem; font-weight: 600; cursor: pointer; border: 1px solid var(--border); background: white; color: var(--text-muted); text-decoration: none; transition: all 0.2s; }
.admin-tab:hover { border-color: var(--primary); color: var(--primary); }
.admin-tab.active { background: var(--primary); color: white; border-color: var(--primary); }

.admin-table { width: 100%; background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); }
.admin-table th { background: #f8fafc; padding: 0.85rem 1rem; text-align: left; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); }
.admin-table td { padding: 0.85rem 1rem; font-size: 0.9rem; color: var(--text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: #fafbfc; }

.ad-thumb { width: 48px; height: 48px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--border); }
.ad-thumb-placeholder { width: 48px; height: 48px; border-radius: var(--radius-sm); background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #cbd5e1; border: 1px solid var(--border); }

.status-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
.status-publiee { background: #d1fae5; color: #065f46; }
.status-en_attente { background: #fef3c7; color: #92400e; }
.status-rejetee { background: #fee2e2; color: #991b1b; }

.action-btn { padding: 0.35rem 0.7rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; cursor: pointer; border: none; font-family: var(--font-main); transition: all 0.15s; text-decoration: none; display: inline-block; }
.btn-approve { background: #d1fae5; color: #065f46; }
.btn-approve:hover { background: #a7f3d0; }
.btn-reject { background: #fef3c7; color: #92400e; }
.btn-reject:hover { background: #fde68a; }
.btn-delete { background: #fee2e2; color: #991b1b; }
.btn-delete:hover { background: #fecaca; }
.btn-view { background: var(--primary-light); color: var(--primary); }
.btn-view:hover { background: #c7d2fe; }

.actions-cell { display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; }

.empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
.empty-state-icon { font-size: 3rem; margin-bottom: 1rem; }

/* Modal for reject */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 999; align-items: center; justify-content: center; }
.modal-overlay.active { display: flex; }
.modal-box { background: white; border-radius: var(--radius-lg); padding: 2rem; width: 90%; max-width: 450px; box-shadow: var(--shadow-lg); }
.modal-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="admin-container">

  <div class="admin-header">
    <div>
      <h1 class="admin-title">🛡️ Gérer les annonces</h1>
    </div>
    <span class="admin-badge">{{ $annonces->count() }} annonces</span>
  </div>

  <div class="admin-tabs">
    <a href="{{ route('admin.moderation.index') }}" class="admin-tab active">📋 Annonces</a>
    <a href="{{ route('admin.users.index') }}" class="admin-tab">👥 Utilisateurs</a>
  </div>

  @if($annonces->isEmpty())
    <div class="empty-state">
      <div class="empty-state-icon">📭</div>
      <p>Aucune annonce à gérer pour l'instant.</p>
    </div>
  @else
    <table class="admin-table">
      <thead>
        <tr>
          <th></th>
          <th>Titre</th>
          <th>Catégorie</th>
          <th>Prix</th>
          <th>Auteur</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($annonces as $annonce)
        <tr>
          <td>
            @if($annonce->photos->count() > 0)
              <img src="{{ asset($annonce->photos->first()->url) }}" alt="" class="ad-thumb">
            @else
              <div class="ad-thumb-placeholder">📦</div>
            @endif
          </td>
          <td><strong>{{ Str::limit($annonce->titre, 40) }}</strong></td>
          <td>{{ $annonce->categorie->nom }}</td>
          <td>{{ $annonce->prixFormate() }}</td>
          <td>{{ $annonce->utilisateur->prenom }} {{ $annonce->utilisateur->nom }}</td>
          <td>
            <span class="status-badge status-{{ $annonce->statut }}">
              {{ $annonce->statut === 'publiee' ? 'Publiée' : ($annonce->statut === 'en_attente' ? 'En attente' : 'Rejetée') }}
            </span>
          </td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('annonces.show', $annonce) }}" class="action-btn btn-view">👁️ Voir</a>

              @if($annonce->statut !== 'publiee')
                <form method="POST" action="{{ route('admin.moderation.approve', $annonce) }}" style="display:inline;">
                  @csrf @method('PATCH')
                  <button type="submit" class="action-btn btn-approve">✅ Approuver</button>
                </form>
              @endif

              @if($annonce->statut !== 'rejetee')
                <button type="button" class="action-btn btn-reject" onclick="openRejectModal({{ $annonce->id }})">❌ Rejeter</button>
              @endif

              <form method="POST" action="{{ route('admin.moderation.destroy', $annonce) }}" style="display:inline;" onsubmit="return confirm('Supprimer définitivement cette annonce ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn btn-delete">🗑️</button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif

</div>

{{-- Modal de rejet --}}
<div class="modal-overlay" id="rejectModal">
  <div class="modal-box">
    <div class="modal-title">❌ Motif du rejet</div>
    <form method="POST" id="rejectForm">
      @csrf @method('PATCH')
      <div class="form-group" style="margin-bottom:1rem;">
        <textarea name="motif_rejet" class="form-input" rows="3" placeholder="Expliquez la raison du rejet..." required style="width:100%;font-family:var(--font-main);font-size:0.9rem;padding:0.75rem;border:1px solid var(--border);border-radius:var(--radius-md);resize:vertical;"></textarea>
      </div>
      <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
        <button type="button" class="action-btn" style="background:#f1f5f9;color:var(--text-muted);" onclick="closeRejectModal()">Annuler</button>
        <button type="submit" class="action-btn btn-delete">Confirmer le rejet</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/moderation/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('active');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('active');
}
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush
