@extends('admin.layout')

@section('content')
<h1>Modifier la catégorie</h1>

<form method="POST" action="/admin/categories/{{ $category->id }}" style="background: white; padding: 24px; border-radius: 8px;">
    @csrf
    @method('PUT')
    
    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nom</label>
        <input type="text" name="name" value="{{ $category->name }}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
    </div>

    <button type="submit" style="background: #800020; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer;">Mettre à jour</button>
</form>
@endsection
