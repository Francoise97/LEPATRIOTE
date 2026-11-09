@extends('admin.layout')

@section('title', $source->exists ? 'Modifier une source' : 'Ajouter une source')

@section('content')
<div style="max-width:760px;">
    <h1 style="margin:0 0 8px;color:#102b46;">{{ $source->exists ? 'Modifier une source' : 'Ajouter une source' }}</h1>
    <p style="margin:0 0 22px;color:#718096;">Une source n’est importable qu’après vérification et activation. Les articles importés restent des brouillons.</p>
    @if($errors->any())<div role="alert" style="padding:12px 16px;margin-bottom:16px;background:#fff0f0;color:#9b1c31;">@foreach($errors->all() as $error)<p style="margin:4px 0;">{{ $error }}</p>@endforeach</div>@endif
    <form method="POST" action="{{ $source->exists ? route('admin.sources.update', $source) : route('admin.sources.store') }}" style="display:grid;gap:16px;padding:24px;background:#fff;border:1px solid #e7edf3;border-radius:10px;">
        @csrf @if($source->exists) @method('PUT') @endif
        <div><label for="name" style="display:block;margin-bottom:6px;font-weight:700;">Nom de la source</label><input id="name" name="name" required maxlength="160" value="{{ old('name', $source->name) }}" style="width:100%;padding:11px;border:1px solid #d5dce5;border-radius:6px;"></div>
        <div><label for="feed_url" style="display:block;margin-bottom:6px;font-weight:700;">URL du flux RSS</label><input id="feed_url" name="feed_url" type="url" required value="{{ old('feed_url', $source->feed_url) }}" placeholder="https://exemple.com/rss" style="width:100%;padding:11px;border:1px solid #d5dce5;border-radius:6px;"></div>
        <div><label for="website_url" style="display:block;margin-bottom:6px;font-weight:700;">Site web (facultatif)</label><input id="website_url" name="website_url" type="url" value="{{ old('website_url', $source->website_url) }}" style="width:100%;padding:11px;border:1px solid #d5dce5;border-radius:6px;"></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
            <div><label for="category_id" style="display:block;margin-bottom:6px;font-weight:700;">Rubrique par défaut</label><select id="category_id" name="category_id" style="width:100%;padding:10px;border:1px solid #d5dce5;border-radius:6px;"><option value="">Déduire du flux RSS</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string)old('category_id', $source->category_id) === (string)$category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div><label for="country_id" style="display:block;margin-bottom:6px;font-weight:700;">Pays par défaut</label><select id="country_id" name="country_id" style="width:100%;padding:10px;border:1px solid #d5dce5;border-radius:6px;"><option value="">Non défini</option>@foreach($countries as $country)<option value="{{ $country->id }}" @selected((string)old('country_id', $source->country_id) === (string)$country->id)>{{ $country->name }}</option>@endforeach</select></div>
        </div>
        <label style="display:flex;gap:9px;align-items:flex-start;"><input type="checkbox" name="is_trusted" value="1" @checked(old('is_trusted', $source->is_trusted))><span><strong>Source vérifiée</strong><br><small style="color:#718096;">J’ai vérifié la fiabilité et les conditions de republication de ce flux.</small></span></label>
        <label style="display:flex;gap:9px;align-items:center;"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $source->is_active))> Source active</label>
        <div style="display:flex;gap:12px;align-items:center;"><button type="submit" style="padding:11px 17px;border:0;border-radius:6px;background:#102b46;color:white;font-weight:700;cursor:pointer;">Enregistrer</button><a href="{{ route('admin.sources.index') }}" style="color:#718096;">Annuler</a></div>
    </form>
</div>
@endsection
