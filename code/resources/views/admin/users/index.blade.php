@extends('layouts.app')

@section('title', 'Gérer les utilisateurs — Admin')

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

.user-avatar-cell { display: flex; align-items: center; gap: 0.75rem; }
.avatar-circle { width: 36px; height: 36px; border-radius: 50%; background: var(--primary-light); color: var(--primary); font-weight: 700; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.role-badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
.role-admin { background: #ede9fe; color: #5b21b6; }
.role-membre { background: #e0e7ff; color: #3730a3; }
.role-visiteur { background: #f1f5f9; color: #64748b; }

.status-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
.status-actif { background: #d1fae5; color: #065f46; }
.status-suspendu { background: #fef3c7; color: #92400e; }
.status-banni { background: #fee2e2; color: #991b1b; }

.action-btn { padding: 0.35rem 0.7rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; cursor: pointer; border: none; font-family: var(--font-main); transition: all 0.15s; text-decoration: none; display: inline-block; }
.btn-activate { background: #d1fae5; color: #065f46; }
.btn-activate:hover { background: #a7f3d0; }
.btn-suspend { background: #fef3c7; color: #92400e; }
.btn-suspend:hover { background: #fde68a; }
.btn-ban { background: #fee2e2; color: #991b1b; }
.btn-ban:hover { background: #fecaca; }
.btn-delete { background: #fee2e2; color: #991b1b; }
.btn-delete:hover { background: #fecaca; }

.actions-cell { display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; }

.empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
.empty-state-icon { font-size: 3rem; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="admin-container">

  <div class="admin-header">
    <div>
      <h1 class="admin-title">👥 Gérer les utilisateurs</h1>
    </div>
    <span class="admin-badge">{{ $users->count() }} utilisateurs</span>
  </div>

  <div class="admin-tabs">
    <a href="{{ route('admin.moderation.index') }}" class="admin-tab">📋 Annonces</a>
    <a href="{{ route('admin.users.index') }}" class="admin-tab active">👥 Utilisateurs</a>
  </div>

  @if($users->isEmpty())
    <div class="empty-state">
      <div class="empty-state-icon">👤</div>
      <p>Aucun autre utilisateur inscrit.</p>
    </div>
  @else
    <table class="admin-table">
      <thead>
        <tr>
          <th>Utilisateur</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Annonces</th>
          <th>Inscrit le</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>
            <div class="user-avatar-cell">
              <div class="avatar-circle">{{ strtoupper(substr($user->nom, 0, 2)) }}</div>
              <div>
                <strong>{{ $user->prenom }} {{ $user->nom }}</strong>
              </div>
            </div>
          </td>
          <td style="color:var(--text-muted);">{{ $user->email }}</td>
          <td>
            <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
          </td>
          <td style="text-align:center;">{{ $user->annonces_count }}</td>
          <td style="color:var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
          <td>
            <span class="status-badge status-{{ $user->statut }}">
              {{ ucfirst($user->statut) }}
            </span>
          </td>
          <td>
            <div class="actions-cell">
              @if($user->statut !== 'actif')
                <form method="POST" action="{{ route('admin.users.status', $user) }}" style="display:inline;">
                  @csrf @method('PATCH')
                  <input type="hidden" name="statut" value="actif">
                  <button type="submit" class="action-btn btn-activate">✅ Activer</button>
                </form>
              @endif

              @if($user->statut !== 'suspendu')
                <form method="POST" action="{{ route('admin.users.status', $user) }}" style="display:inline;">
                  @csrf @method('PATCH')
                  <input type="hidden" name="statut" value="suspendu">
                  <button type="submit" class="action-btn btn-suspend">⏸️ Suspendre</button>
                </form>
              @endif

              @if($user->statut !== 'banni')
                <form method="POST" action="{{ route('admin.users.status', $user) }}" style="display:inline;">
                  @csrf @method('PATCH')
                  <input type="hidden" name="statut" value="banni">
                  <button type="submit" class="action-btn btn-ban">🚫 Bannir</button>
                </form>
              @endif

              @if(!$user->isAdmin())
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;" onsubmit="return confirm('Supprimer définitivement {{ $user->prenom }} {{ $user->nom }} ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="action-btn btn-delete">🗑️</button>
                </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif

</div>
@endsection
