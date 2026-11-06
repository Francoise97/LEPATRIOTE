@extends('admin.layout')

@section('title', 'Sources fiables')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:22px;">
    <div><h1 style="margin:0 0 6px;color:#102b46;">Sources d’information</h1><p style="margin:0;color:#718096;">Les articles importés arrivent en brouillon pour validation éditoriale.</p></div>
    <a href="{{ route('admin.sources.create') }}" style="padding:11px 16px;background:#102b46;color:#fff;text-decoration:none;border-radius:6px;font-weight:700;white-space:nowrap;">Ajouter une source</a>
</div>
@if(session('success'))<p role="status" style="padding:12px 16px;background:#e8f6ed;color:#176b37;">{{ session('success') }}</p>@endif
@if($errors->any())<p role="alert" style="padding:12px 16px;background:#fff0f0;color:#9b1c31;">{{ $errors->first() }}</p>@endif
<div style="overflow:auto;background:#fff;border:1px solid #e7edf3;border-radius:10px;">
    <table style="width:100%;border-collapse:collapse;min-width:740px;">
        <thead><tr style="background:#f7f9fb;text-align:left;"><th style="padding:13px;">Source</th><th style="padding:13px;">Rubrique / pays</th><th style="padding:13px;">État</th><th style="padding:13px;">Dernier import</th><th style="padding:13px;">Actions</th></tr></thead>
        <tbody>
        @forelse($sources as $source)
            <tr style="border-top:1px solid #edf0f3;">
                <td style="padding:13px;"><strong>{{ $source->name }}</strong><br><a href="{{ $source->website_url ?: $source->feed_url }}" target="_blank" rel="noopener noreferrer" style="color:#718096;font-size:12px;">Voir la source</a></td>
                <td style="padding:13px;color:#536174;">{{ $source->category?->name ?? 'Rubrique du flux' }} · {{ $source->country?->name ?? 'Pays non défini' }}</td>
                <td style="padding:13px;">{{ $source->is_trusted ? 'Vérifiée' : 'À vérifier' }} · {{ $source->is_active ? 'Active' : 'Désactivée' }}</td>
                <td style="padding:13px;color:#718096;">{{ $source->last_imported_at?->format('d/m/Y H:i') ?? 'Jamais' }}</td>
                <td style="padding:13px;white-space:nowrap;">
                    <form method="POST" action="{{ route('admin.sources.import', $source) }}" style="display:inline;">@csrf<button type="submit" @disabled(!$source->is_active || !$source->is_trusted) style="padding:7px 10px;border:0;border-radius:5px;background:#e8f0f8;color:#102b46;cursor:pointer;">Importer</button></form>
                    <a href="{{ route('admin.sources.edit', $source) }}" style="margin-left:8px;color:#a51f35;">Modifier</a>
                    <form method="POST" action="{{ route('admin.sources.destroy', $source) }}" style="display:inline;margin-left:8px;" onsubmit="return confirm('Supprimer cette source ?')">@csrf @method('DELETE')<button type="submit" style="border:0;background:none;color:#a51f35;cursor:pointer;">Supprimer</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" style="padding:28px;text-align:center;color:#718096;">Aucune source enregistrée.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:18px;">{{ $sources->links() }}</div>
@endsection
