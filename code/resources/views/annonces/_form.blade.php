@extends('layouts.app')

@php
  $isEdit = isset($annonce);
@endphp

@section('title', $isEdit ? 'Modifier l\'annonce' : 'Publier une annonce')

@push('styles')
<style>
.form-container { max-width: 800px; margin: 3rem auto; padding: 0 2rem; }
.form-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); padding: 2.5rem; }
.form-title { font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; }
.form-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; }

.form-group { margin-bottom: 1.5rem; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.form-label { display: block; font-weight: 600; font-size: 0.9rem; color: var(--text-main); margin-bottom: 0.5rem; }

.form-input, .form-select, .form-textarea { width: 100%; background: #f8fafc; border: 1px solid var(--border); color: var(--text-main); font-family: var(--font-main); font-size: 0.95rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); outline: none; transition: all 0.2s; }
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--primary); background: white; box-shadow: 0 0 0 3px var(--primary-light); }
.form-textarea { resize: vertical; min-height: 120px; }

.form-input.error, .form-select.error, .form-textarea.error { border-color: var(--danger); }
.form-error { font-size: 0.8rem; color: var(--danger); margin-top: 0.4rem; }

/* FILE UPLOAD */
.file-upload { border: 2px dashed var(--border); border-radius: var(--radius-md); padding: 2rem; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s; position: relative; }
.file-upload:hover { border-color: var(--primary); background: var(--primary-light); }
.file-upload input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; z-index: 10; }
.file-icon { font-size: 2rem; margin-bottom: 0.5rem; }
.file-text { font-size: 0.9rem; color: var(--text-muted); font-weight: 500; }
.file-text span { color: var(--primary); font-weight: 600; }

.form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border); }
.btn-submit { background: var(--primary); color: white; border: none; font-weight: 600; padding: 0.75rem 2rem; border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; box-shadow: var(--shadow-sm); }
.btn-submit:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: var(--shadow-md); }
</style>
@endpush

@section('content')
<div class="form-container">
  
  <div class="form-card">
    <h1 class="form-title">{{ $isEdit ? 'Modifier mon annonce' : 'Publier une annonce' }}</h1>
    <p class="form-subtitle">Remplissez les informations ci-dessous pour {{ $isEdit ? 'mettre à jour' : 'créer' }} votre annonce.</p>

    <form method="POST" action="{{ $isEdit ? route('annonces.update', $annonce) : route('annonces.store') }}" enctype="multipart/form-data">
      @csrf
      @if($isEdit) @method('PUT') @endif

      <div class="form-group">
        <label class="form-label">Titre de l'annonce <span style="color:var(--danger);">*</span></label>
        <input type="text" name="titre" class="form-input {{ $errors->has('titre') ? 'error' : '' }}" 
               value="{{ old('titre', $annonce->titre ?? '') }}" placeholder="Ex: iPhone 13 Pro Max 256Go" required>
        @error('titre') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Catégorie <span style="color:var(--danger);">*</span></label>
          <select name="id_categorie" class="form-select {{ $errors->has('id_categorie') ? 'error' : '' }}" required>
            <option value="">-- Choisir --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('id_categorie', $annonce->id_categorie ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->icone }} {{ $cat->nom }}
              </option>
            @endforeach
          </select>
          @error('id_categorie') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Prix (DH)</label>
          <input type="number" name="prix" class="form-input {{ $errors->has('prix') ? 'error' : '' }}" 
                 value="{{ old('prix', $annonce->prix ?? '') }}" placeholder="Ex: 8500" min="0" step="0.01">
          <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Laissez vide si "Prix sur demande"</div>
          @error('prix') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Description détaillée <span style="color:var(--danger);">*</span></label>
        <textarea name="description" class="form-textarea {{ $errors->has('description') ? 'error' : '' }}" 
                  placeholder="Décrivez votre article en détail (état, utilisation, accessoires...)" required>{{ old('description', $annonce->description ?? '') }}</textarea>
        @error('description') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Photo principale</label>
        
        @if($isEdit && $annonce->photos->count() > 0)
          <div style="margin-bottom:1rem; display:flex; align-items:center; gap:1rem; padding: 1rem; background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-md);">
            <img src="{{ asset($annonce->photos->first()->url) }}" alt="Image actuelle" style="width:80px;height:60px;object-fit:cover;border-radius:4px;border:1px solid var(--border);">
            <span style="font-size:0.85rem;color:var(--text-muted);">Image actuelle (sera remplacée si vous en choisissez une nouvelle)</span>
          </div>
        @endif

        <div class="file-upload">
          <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
          <div class="file-icon">📸</div>
          <div class="file-text"><span>Cliquez pour parcourir</span> ou glissez-déposez</div>
          <div style="font-size:0.75rem; color:var(--text-light); margin-top:0.5rem;">PNG, JPG, GIF jusqu'à 5MB</div>
        </div>
        @error('image') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-actions">
        <a href="{{ route('annonces.mine') }}" class="btn-outline">Annuler</a>
        <button type="submit" class="btn-submit">{{ $isEdit ? 'Enregistrer les modifications' : 'Publier l\'annonce' }}</button>
      </div>

    </form>
  </div>

</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('.file-upload input[type="file"]');
    const fileText = document.querySelector('.file-upload .file-text');

    if (fileInput && fileText) {
      fileInput.addEventListener('change', function(e) {
        if (this.files && this.files.length > 0) {
          fileText.innerHTML = `<span style="color:var(--primary);font-weight:600;">${this.files[0].name}</span>`;
        } else {
          fileText.innerHTML = `<span>Cliquez pour parcourir</span> ou glissez-déposez`;
        }
      });
    }
  });
</script>
@endpush