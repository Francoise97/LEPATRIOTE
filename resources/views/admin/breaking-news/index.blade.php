@extends('admin.layout')

@section('title', 'Fil info')

@section('content')
<div style="max-width:1000px;">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:22px;">
        <div>
            <h1 style="margin:0 0 8px;color:#102b46;">Fil info</h1>
            <p style="margin:0;color:#718096;">Gérez les messages affichés dans le bandeau défilant de la page d’accueil.</p>
        </div>
        <a href="{{ route('admin.breaking-news.create') }}" style="flex:none;padding:11px 16px;border-radius:6px;background:#a51f35;color:#fff;font-weight:700;text-decoration:none;">Ajouter un message</a>
    </div>

    @if(session('success'))
        <p role="status" style="padding:12px 16px;background:#e8f6ed;color:#176b37;">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <div role="alert" style="padding:12px 16px;margin-bottom:16px;background:#fff0f0;color:#9b1c31;">
            @foreach($errors->all() as $error)<p style="margin:4px 0;">{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.breaking-news.label') }}" style="display:flex;align-items:end;gap:12px;margin:20px 0;padding:18px;background:#fff;border:1px solid #e7edf3;border-radius:10px;">
        @csrf
        @method('PUT')
        <div style="flex:1;">
            <label for="label" style="display:block;margin-bottom:7px;font-weight:700;">Texte du badge fixe</label>
            <input id="label" name="label" required maxlength="30" value="{{ old('label', $label) }}" style="width:100%;padding:11px;border:1px solid #d5dce5;border-radius:6px;">
        </div>
        <button type="submit" style="padding:11px 16px;border:0;border-radius:6px;background:#102b46;color:#fff;font-weight:700;cursor:pointer;">Enregistrer</button>
    </form>

    <div style="overflow-x:auto;background:#fff;border:1px solid #e7edf3;border-radius:10px;">
        <table style="width:100%;border-collapse:collapse;text-align:left;">
            <thead style="background:#f7f9fb;">
                <tr>
                    <th style="padding:13px 16px;">Ordre</th>
                    <th style="padding:13px 16px;">Message</th>
                    <th style="padding:13px 16px;">Lien</th>
                    <th style="padding:13px 16px;">État</th>
                    <th style="padding:13px 16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr style="border-top:1px solid #e7edf3;">
                        <td style="padding:13px 16px;">{{ $item->sort_order }}</td>
                        <td style="max-width:400px;padding:13px 16px;">{{ $item->text }}</td>
                        <td style="max-width:220px;padding:13px 16px;overflow-wrap:anywhere;">{{ $item->url ?: '—' }}</td>
                        <td style="padding:13px 16px;">{{ $item->is_active ? 'Actif' : 'Désactivé' }}</td>
                        <td style="padding:13px 16px;white-space:nowrap;">
                            <a href="{{ route('admin.breaking-news.edit', $item) }}" style="color:#102b46;font-weight:700;">Modifier</a>
                            <form method="POST" action="{{ route('admin.breaking-news.destroy', $item) }}" style="display:inline;margin-left:12px;" onsubmit="return confirm('Supprimer ce message du fil info ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border:0;padding:0;background:none;color:#a51f35;font:inherit;font-weight:700;cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding:22px 16px;color:#718096;">Aucun message. Le bandeau apparaîtra dès qu’un message sera activé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">{{ $items->links() }}</div>
</div>
@endsection
