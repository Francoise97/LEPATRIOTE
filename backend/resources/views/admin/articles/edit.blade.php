@extends('admin.layout')

@section('content')
<h1>Modifier l'article</h1>

<form method="POST" action="/admin/articles/{{ $article->id }}" style="background: white; padding: 24px; border-radius: 8px;">
    @csrf
    @method('PUT')
    
    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Titre</label>
        <input type="text" name="title" value="{{ $article->title }}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Catégorie</label>
        <select name="category_id" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
            <option value="">Sélectionner une catégorie</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $article->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;" for="content">Texte complet de l’article</label>
        <textarea id="content" name="content" rows="14" required style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">{{ old('content', $article->content) }}</textarea>
        <small>Publiez uniquement un texte rédigé par votre rédaction ou que vous êtes autorisé à republier.</small>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;" for="excerpt">Résumé / chapô</label>
        <textarea id="excerpt" name="excerpt" rows="3" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">{{ old('excerpt', $article->excerpt) }}</textarea>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;" for="source_url">Lien de la source (facultatif)</label>
        <input id="source_url" type="url" name="source_url" value="{{ old('source_url', $article->source_url) }}" placeholder="https://..." style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Statut</label>
        <select name="status" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
            <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
            <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>Publié</option>
        </select>
    </div>

    <button type="submit" style="background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer;">Mettre à jour</button>
</form>
@endsection
