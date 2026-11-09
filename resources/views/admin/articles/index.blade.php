@extends('admin.layout')

@section('content')
<h1>Articles</h1>
<a href="/admin/articles/create" style="display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; margin-bottom: 24px; border-radius: 6px;">Créer un article</a>

<table style="width: 100%; border-collapse: collapse; background: white;">
    <thead>
        <tr style="background: #f9fafb;">
            <th style="padding: 12px; text-align: left;">Image</th>
            <th style="padding: 12px; text-align: left;">Titre</th>
            <th style="padding: 12px; text-align: left;">Catégorie</th>
            <th style="padding: 12px; text-align: left;">Pays</th>
            <th style="padding: 12px; text-align: left;">Source</th>
            <th style="padding: 12px; text-align: left;">Statut</th>
            <th style="padding: 12px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($articles as $article)
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                @else
                <span style="color: #9ca3af;">-</span>
                @endif
            </td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $article->title }}</td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $article->category->name ?? '-' }}</td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $article->country?->name ?? '-' }}</td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                @if($article->source_url)
                    <a href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer" style="color:#3b82f6;">Voir</a>
                @else
                    -
                @endif
            </td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $article->status }}</td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                <a href="/admin/articles/{{ $article->id }}/edit" style="color: #3b82f6;">Modifier</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="padding: 24px; text-align: center;">Aucun article pour le moment</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
