@extends('layouts.app')
@section('title', (isset($annonce) && $annonce->exists ? 'Modifier' : 'Publier') . ' une annonce — MarketAd World')

@push('styles')
<style>
.publish-layout { max-width:820px; margin:0 auto; padding:2rem; }

/* Steps */
.publish-steps { display:flex; align-items:center; gap:0; margin-bottom:2.5rem; }
.step { display:flex; align-items:center; gap:0.6rem; }
.step-num {
  width:32px; height:32px; border-radius:50%;
  background:var(--white-10); border:1px solid var(--border);
  display:flex; align-items:center; justify-content:center;
  font-size:0.8rem; font-weight:700; font-family:var(--font-display);
  color:var(--white-50); flex-shrink:0;
}
.step-num.done { background:var(--success); color:var(--white); border-color:transparent; }
.step-num.active { background:var(--cyan); color:var(--navy); border-color:transparent; }
.step-label { font-size:0.8rem; font-weight:600; color:var(--white-50); }
.step-label.active { color:var(--white); }
.step-line { flex:1; height:1px; background:var(--border); margin:0 0.75rem; }

/* Form cards */
.form-card { background:var(--navy2); border:1px solid var(--border); border-radius:14px; padding:1.8rem; margin-bottom:1.2rem; }
.form-card-title { font-family:var(--font-display); font-size:1rem; font-weight:700; margin-bottom:1.2rem; padding-bottom:0.8rem; border-bottom:1px solid var(--border); }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.form-group { margin-bottom:1rem; }
.form-label { display:block; font-size:0.8rem; font-weight:600; color:var(--white-80); margin-bottom:0.4rem; letter-spacing:0.02em; }
.form-input, .form-select, .form-textarea {
  width:100%;
  background: var(--navy);
  border: 1px solid var(--border);
  color: var(--white);
  font-family: var(--font-body);
  font-size: 0.9rem;
  padding: 0.65rem 0.9rem;
  border-radius: 8px;
  outline: none;
  transition: border-color 0.2s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color:var(--border-cyan); box-shadow:0 0 0 3px rgba(0,178,255,0.08); }
.form-input::placeholder, .form-textarea::placeholder { color:var(--white-30); }
.form-input.error, .form-select.error, .form-textarea.error { border-color:rgba(239,68,68,0.5); box-shadow:0 0 0 3px rgba(239,68,68,0.06); }
.form-select option { background:var(--navy2); }
.form-error { font-size:0.78rem; color:#ef4444; margin-top:0.3rem; }
.form-textarea { resize:vertical; min-height:120px; line-height:1.6; }
.char-count { font-size:0.75rem; color:var(--white-40); text-align:right; margin-top:0.3rem; }

/* Upload zone */
.upload-zone {
  border: 2px dashed var(--border);
  border-radius: 12px;
  padding: 2.5rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  background: var(--navy);
}
.upload-zone:hover, .upload-zone.drag-over { border-color:var(--border-cyan); background:var(--cyan-dim2); }
.upload-icon { font-size:2.5rem; margin-bottom:0.8rem; opacity:0.5; }
.upload-text { color:var(--white-50); font-size:0.875rem; }
.upload-text strong { color:var(--cyan); }
.preview-grid { display:flex; gap:0.7rem; flex-wrap:wrap; margin-top:1rem; }
.preview-img { width:80px; height:70px; background:var(--navy3); border-radius:8px; border:1px solid var(--border); display:flex; align-items:center; justify-content:center; font-size:1.8rem; position:relative; cursor:pointer; overflow:hidden; }
.preview-img img { width:100%; height:100%; object-fit:cover; border-radius:8px; }
.preview-img .rm { position:absolute; top:-6px; right:-6px; width:18px; height:18px; background:#ef4444; border-radius:50%; font-size:0.65rem; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; }

/* Actions */
.publish-actions { display:flex; justify-content:space-between; align-items:center; margin-top:1.5rem; }
</style>
@endpush

@section('content')

@php $isEdit = isset($annonce) && $annonce->exists; @endphp

<div class="publish-layout">

  {{-- Steps --}}
  <div class="publish-steps">
    <div class="step">
      <div class="step-num done">✓</div>
      <div class="step-label">Catégorie</div>
    </div>
    <div class="step-line"></div>
    <div class="step">
      <div class="step-num active">2</div>
      <div class="step-label active">Détails</div>
    </div>
    <div class="step-line"></div>
    <div class="step">
      <div class="step-num">3</div>
      <div class="step-label">Photos</div>
    </div>
    <div class="step-line"></div>
    <div class="step">
      <div class="step-num">4</div>
      <div class="step-label">Publier</div>
    </div>
  </div>

  {{-- Titre de page --}}
  <div style="margin-bottom:1.5rem;">
    <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:700;letter-spacing:-0.5px;">
      {{ $isEdit ? 'Modifier l\'annonce' : 'Publier une annonce' }}
    </h1>
    <p style="color:var(--white-50);font-size:0.875rem;margin-top:0.3rem;font-weight:300;">
      {{ $isEdit ? 'Mettez à jour les informations de votre annonce.' : 'Remplissez les informations ci-dessous. Publication gratuite et immédiate.' }}
    </p>
  </div>

  <form method="POST"
        action="{{ $isEdit ? route('annonces.update', $annonce) : route('annonces.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- SECTION 1 : Informations principales --}}
    <div class="form-card">
      <div class="form-card-title">📝 Informations principales</div>

      {{-- Titre --}}
      <div class="form-group">
        <label class="form-label">Titre de l'annonce <span style="color:#ef4444;">*</span></label>
        <input type="text" name="titre"
               class="form-input {{ $errors->has('titre') ? 'error' : '' }}"
               value="{{ old('titre', $annonce->titre ?? '') }}"
               placeholder="Ex: iPhone 15 Pro Max 256Go — Comme neuf"
               maxlength="255"
               oninput="document.getElementById('titre-count').textContent = this.value.length + '/255'">
        <div class="char-count"><span id="titre-count">{{ strlen(old('titre', $annonce->titre ?? '')) }}/255</span></div>
        @error('titre') <div class="form-error">{{ $message }}</div> @enderror
      </div>

      {{-- Catégorie + Ville --}}
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Catégorie <span style="color:#ef4444;">*</span></label>
          <select name="categorie_id" class="form-select {{ $errors->has('categorie_id') ? 'error' : '' }}">
            <option value="">-- Choisir --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('categorie_id', $annonce->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->icone }} {{ $cat->nom }}
              </option>
            @endforeach
          </select>
          @error('categorie_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Ville</label>
          <input type="text" name="ville"
                 class="form-input {{ $errors->has('ville') ? 'error' : '' }}"
                 value="{{ old('ville', $annonce->ville ?? '') }}"
                 placeholder="Ex: Casablanca, Rabat, Fès...">
          @error('ville') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>

      {{-- Description --}}
      <div class="form-group">
        <label class="form-label">Description <span style="color:#ef4444;">*</span></label>
        <textarea name="description"
                  class="form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                  rows="6"
                  maxlength="3000"
                  placeholder="Décrivez votre article en détail : état, caractéristiques, inclusions, raison de vente..."
                  oninput="document.getElementById('desc-count').textContent = this.value.length + '/3000'"
        >{{ old('description', $annonce->description ?? '') }}</textarea>
        <div class="char-count"><span id="desc-count">{{ strlen(old('description', $annonce->description ?? '')) }}/3000</span></div>
        @error('description') <div class="form-error">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- SECTION 2 : Prix & statut --}}
    <div class="form-card">
      <div class="form-card-title">💰 Prix & statut</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Prix (DH)</label>
          <div style="position:relative;">
            <input type="number" name="prix"
                   class="form-input {{ $errors->has('prix') ? 'error' : '' }}"
                   value="{{ old('prix', $annonce->prix ?? '') }}"
                   placeholder="0"
                   min="0" step="0.01"
                   style="padding-right:3rem;">
            <span style="position:absolute;right:0.9rem;top:50%;transform:translateY(-50%);color:var(--white-40);font-size:0.85rem;">DH</span>
          </div>
          <div style="font-size:0.75rem;color:var(--white-40);margin-top:0.3rem;">Laissez vide pour « Prix à négocier »</div>
          @error('prix') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        @if($isEdit)
          <div class="form-group">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select">
              <option value="active" {{ old('statut', $annonce->statut) === 'active' ? 'selected' : '' }}>✅ Active — visible par tous</option>
              <option value="inactive" {{ old('statut', $annonce->statut) === 'inactive' ? 'selected' : '' }}>⏸️ Inactive — masquée</option>
              <option value="vendue" {{ old('statut', $annonce->statut) === 'vendue' ? 'selected' : '' }}>✓ Vendue</option>
            </select>
          </div>
        @endif
      </div>
    </div>

    {{-- SECTION 3 : Photo --}}
    <div class="form-card">
      <div class="form-card-title">📸 Photo principale</div>

      @if($isEdit && $annonce->image)
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;padding:0.8rem;background:var(--white-06);border-radius:10px;">
          <img src="{{ asset('storage/' . $annonce->image) }}" alt="Image actuelle" style="width:72px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
          <div style="font-size:0.8rem;color:var(--white-50);">Image actuelle. Uploadez-en une nouvelle pour la remplacer.</div>
        </div>
      @endif

      <label for="image-input" class="upload-zone">
        <div class="upload-icon">🖼️</div>
        <div class="upload-text">
          <strong>Cliquez pour uploader</strong> ou glissez-déposez ici
        </div>
        <div style="font-size:0.78rem;color:var(--white-40);margin-top:0.4rem;">JPEG, PNG, WebP — max 2 Mo</div>
        <input type="file" id="image-input" name="image" accept="image/*" style="display:none" onchange="previewImg(this)">
      </label>

      <div class="preview-grid" id="preview-container"></div>

      <div style="font-size:0.78rem;color:var(--white-40);margin-top:0.8rem;">
        💡 Les annonces avec photos reçoivent <strong style="color:var(--cyan);">5× plus de vues</strong>.
      </div>
      @error('image') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    {{-- Actions --}}
    <div class="publish-actions">
      @if($isEdit)
        <a href="{{ route('annonces.show', $annonce) }}" class="btn-outline">← Annuler</a>
      @else
        <a href="{{ route('home') }}" class="btn-outline">← Annuler</a>
      @endif

      <div style="display:flex;gap:0.7rem;">
        <button type="submit" class="btn-cyan" style="padding:0.7rem 2rem;font-size:0.95rem;">
          {{ $isEdit ? 'Enregistrer les modifications →' : 'Publier l\'annonce →' }}
        </button>
      </div>
    </div>
  </form>
</div>

@push('scripts')
<script>
function previewImg(input) {
  const container = document.getElementById('preview-container');
  container.innerHTML = '';
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement('div');
      div.className = 'preview-img';
      div.innerHTML = `<img src="${e.target.result}" alt="Aperçu"><div class="rm" onclick="clearPreview()">×</div>`;
      container.appendChild(div);
    };
    reader.readAsDataURL(input.files[0]);
  }
}
function clearPreview() {
  document.getElementById('image-input').value = '';
  document.getElementById('preview-container').innerHTML = '';
}

// Drag & drop
const zone = document.querySelector('.upload-zone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
zone.addEventListener('drop', e => {
  e.preventDefault();
  zone.classList.remove('drag-over');
  const input = document.getElementById('image-input');
  input.files = e.dataTransfer.files;
  previewImg(input);
});
</script>
@endpush

@endsection