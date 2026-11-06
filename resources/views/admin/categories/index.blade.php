@extends('admin.layout')

@section('content')
<h1>Catégories</h1>
<a href="/admin/categories/create" style="display: inline-block; background: #800020; color: white; padding: 12px 24px; text-decoration: none; margin-bottom: 24px; border-radius: 6px;">Créer une catégorie</a>

<table style="width: 100%; border-collapse: collapse; background: white;">
    <thead>
        <tr style="background: #f9fafb;">
            <th style="padding: 12px; text-align: left;">Nom</th>
            <th style="padding: 12px; text-align: left;">Slug</th>
            <th style="padding: 12px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $category->name }}</td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $category->slug }}</td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                <a href="/admin/categories/{{ $category->id }}/edit" style="color: #003366; margin-right: 16px;">Modifier</a>
                <form method="POST" action="/admin/categories/{{ $category->id }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer;">Supprimer</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="padding: 24px; text-align: center;">Aucune catégorie pour le moment</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
