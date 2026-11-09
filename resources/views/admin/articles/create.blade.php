@extends('admin.layout')

@section('content')
<h1>Créer un article</h1>

<form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" style="background: white; padding: 24px; border-radius: 8px;">
    @csrf
    @if($errors->any())
        <div role="alert" style="margin-bottom:20px;padding:14px;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;">
            <strong>Vérifiez les informations suivantes :</strong>
            <ul style="margin:8px 0 0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Titre</label>
        <input type="text" name="title" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Catégorie</label>
        <select name="category_id" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
            <option value="">Sélectionner une catégorie</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom: 16px;">
        <label for="country_id" style="display: block; margin-bottom: 8px; font-weight: 600;">Pays de l’actualité</label>
        <select id="country_id" name="country_id" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
            <option value="">Pays non défini</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom: 16px;">
        <label for="featured_image" style="display: block; margin-bottom: 8px; font-weight: 600;">Image principale</label>
        <input id="featured_image" type="file" name="featured_image" accept="image/jpeg,image/png,image/webp,image/gif" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
        <small>JPG, PNG, WebP ou GIF — 8 Mo maximum.</small>
    </div>

    <div style="margin-bottom: 16px;">
        <label for="gallery_images" style="display: block; margin-bottom: 8px; font-weight: 600;">Galerie d’images</label>
        <input id="gallery_images" type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
        <small>Jusqu’à 10 images supplémentaires, 8 Mo chacune.</small>
    </div>

    <div style="margin-bottom: 16px;">
        <label for="video_files" style="display: block; margin-bottom: 8px; font-weight: 600;">Vidéos à téléverser</label>
        <input id="video_files" type="file" name="video_files[]" accept="video/mp4,video/webm,video/quicktime,video/ogg" multiple style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
        <small>MP4, WebM, MOV ou OGG — jusqu’à 3 vidéos de 100 Mo chacune.</small>
    </div>

    <div style="margin-bottom: 16px;">
        <label for="video_urls" style="display: block; margin-bottom: 8px; font-weight: 600;">Liens YouTube ou Vimeo</label>
        <input id="video_urls" type="url" name="video_urls[]" placeholder="https://www.youtube.com/watch?v=…" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
        <small>Ajoutez d’autres liens après l’enregistrement de l’article.</small>
    </div>

    <div style="margin-bottom: 16px;">
        <label for="documents" style="display: block; margin-bottom: 8px; font-weight: 600;">Documents PDF</label>
        <input id="documents" type="file" name="documents[]" accept="application/pdf" multiple style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
        <small>Jusqu’à 10 documents PDF de 10 Mo chacun.</small>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;" for="content">Texte complet de l’article</label>
        <textarea id="content" name="content" rows="14" required style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">{{ old('content') }}</textarea>
        <small>Publiez uniquement un texte rédigé par votre rédaction ou que vous êtes autorisé à republier.</small>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;" for="excerpt">Résumé / chapô</label>
        <textarea id="excerpt" name="excerpt" rows="3" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">{{ old('excerpt') }}</textarea>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;" for="source_url">Lien de la source (facultatif)</label>
        <input id="source_url" type="url" name="source_url" value="{{ old('source_url') }}" placeholder="https://..." style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Statut</label>
        <select name="status" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
            <option value="draft">Brouillon</option>
            <option value="published">Publié</option>
        </select>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:20px;margin-bottom:20px;">
        <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))> Mettre à la une</label>
        <label><input type="checkbox" name="is_breaking" value="1" @checked(old('is_breaking'))> Afficher dans le bandeau défilant</label>
    </div>

    <button type="submit" style="background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer;">Créer</button>
</form>
@endsection
