@extends('admin.layout')

@section('title', 'Publicités')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:22px;">
    <div><h1 style="margin:0 0 6px;color:#102b46;">Publicités</h1><p style="margin:0;color:#718096;">Gérez les visuels, liens, périodes et emplacements de diffusion.</p></div>
    <a href="{{ route('admin.advertisements.create') }}" style="padding:11px 16px;background:#102b46;color:#fff;text-decoration:none;border-radius:6px;font-weight:700;white-space:nowrap;">Ajouter une publicité</a>
</div>
@if(session('success'))<p role="status" style="padding:12px 16px;background:#e8f6ed;color:#176b37;">{{ session('success') }}</p>@endif
<div style="overflow:auto;background:#fff;border:1px solid #e7edf3;border-radius:10px;">
    <table style="width:100%;border-collapse:collapse;min-width:700px;">
        <thead><tr style="background:#f7f9fb;text-align:left;"><th style="padding:13px;">Visuel</th><th style="padding:13px;">Publicité</th><th style="padding:13px;">Emplacement</th><th style="padding:13px;">Période</th><th style="padding:13px;">Actions</th></tr></thead>
        <tbody>
        @forelse($advertisements as $advertisement)
            <tr style="border-top:1px solid #edf0f3;">
                <td style="padding:12px;"><img src="{{ asset('storage/'.$advertisement->image_path) }}" alt="" style="width:100px;height:60px;object-fit:cover;"></td>
                <td style="padding:12px;"><strong>{{ $advertisement->title }}</strong><br><span style="color:#718096;font-size:12px;">{{ $advertisement->is_active ? 'Active' : 'Désactivée' }}</span></td>
                <td style="padding:12px;">{{ ['home' => 'Accueil', 'sidebar' => 'Colonne latérale', 'footer' => 'Pied de page'][$advertisement->placement] ?? $advertisement->placement }}</td>
                <td style="padding:12px;color:#718096;">{{ $advertisement->starts_at?->format('d/m/Y') ?? 'Dès maintenant' }} – {{ $advertisement->ends_at?->format('d/m/Y') ?? 'Sans échéance' }}</td>
                <td style="padding:12px;white-space:nowrap;"><a href="{{ route('admin.advertisements.edit', $advertisement) }}" style="color:#a51f35;">Modifier</a><form method="POST" action="{{ route('admin.advertisements.destroy', $advertisement) }}" style="display:inline;margin-left:10px;" onsubmit="return confirm('Supprimer cette publicité ?')">@csrf @method('DELETE')<button type="submit" style="border:0;background:none;color:#a51f35;cursor:pointer;">Supprimer</button></form></td>
            </tr>
        @empty
            <tr><td colspan="5" style="padding:28px;text-align:center;color:#718096;">Aucune publicité enregistrée.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:18px;">{{ $advertisements->links() }}</div>
@endsection
