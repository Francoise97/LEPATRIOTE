@extends('admin.layout')

@section('title', $item->exists ? 'Modifier le message du fil info' : 'Ajouter au fil info')

@section('content')
<div style="max-width:760px;">
    <h1 style="margin:0 0 8px;color:#102b46;">{{ $item->exists ? 'Modifier le message' : 'Ajouter un message au fil info' }}</h1>
    <p style="margin:0 0 24px;color:#718096;">Le texte défile sur une seule ligne, à droite du badge fixe.</p>

    @if($errors->any())
        <div role="alert" style="padding:12px 16px;margin-bottom:16px;background:#fff0f0;color:#9b1c31;">
            @foreach($errors->all() as $error)<p style="margin:4px 0;">{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ $item->exists ? route('admin.breaking-news.update', $item) : route('admin.breaking-news.store') }}" style="display:grid;gap:18px;padding:26px;background:#fff;border:1px solid #e7edf3;border-radius:12px;">
        @csrf
        @if($item->exists) @method('PUT') @endif
        <div>
            <label for="text" style="display:block;margin-bottom:7px;font-weight:700;">Message</label>
            <input id="text" name="text" required maxlength="255" value="{{ old('text', $item->text) }}" style="width:100%;padding:12px;border:1px solid #d5dce5;border-radius:6px;">
        </div>
        <div>
            <label for="url" style="display:block;margin-bottom:7px;font-weight:700;">Lien (facultatif)</label>
            <input id="url" name="url" type="url" maxlength="2048" placeholder="https://exemple.com/article" value="{{ old('url', $item->url) }}" style="width:100%;padding:12px;border:1px solid #d5dce5;border-radius:6px;">
        </div>
        <div>
            <label for="sort_order" style="display:block;margin-bottom:7px;font-weight:700;">Ordre d’affichage</label>
            <input id="sort_order" name="sort_order" type="number" min="0" max="10000" required value="{{ old('sort_order', $item->sort_order ?? 0) }}" style="width:150px;padding:10px;border:1px solid #d5dce5;border-radius:6px;">
            <small style="display:block;margin-top:6px;color:#718096;">Les valeurs les plus petites défilent en premier.</small>
        </div>
        <label style="display:flex;align-items:center;gap:9px;font-weight:700;">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->exists ? $item->is_active : true))>
            Afficher ce message dans le bandeau
        </label>
        <div style="display:flex;align-items:center;gap:14px;">
            <button type="submit" style="padding:12px 20px;border:0;border-radius:6px;background:#102b46;color:#fff;font-weight:700;cursor:pointer;">{{ $item->exists ? 'Enregistrer' : 'Ajouter le message' }}</button>
            <a href="{{ route('admin.breaking-news.index') }}" style="color:#718096;">Annuler</a>
        </div>
    </form>
</div>
@endsection
