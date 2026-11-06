@extends('admin.layout')

@section('title', $advertisement->exists ? 'Modifier une publicité' : 'Ajouter une publicité')

@section('content')
<div style="max-width:760px;">
    <h1 style="margin:0 0 20px;color:#102b46;">{{ $advertisement->exists ? 'Modifier une publicité' : 'Ajouter une publicité' }}</h1>
    @if($errors->any())<div role="alert" style="padding:12px 16px;margin-bottom:16px;background:#fff0f0;color:#9b1c31;">@foreach($errors->all() as $error)<p style="margin:4px 0;">{{ $error }}</p>@endforeach</div>@endif
    <form method="POST" action="{{ $advertisement->exists ? route('admin.advertisements.update', $advertisement) : route('admin.advertisements.store') }}" enctype="multipart/form-data" style="display:grid;gap:16px;padding:24px;background:#fff;border:1px solid #e7edf3;border-radius:10px;">
        @csrf @if($advertisement->exists) @method('PUT') @endif
        <div><label for="title" style="display:block;margin-bottom:6px;font-weight:700;">Nom / texte alternatif</label><input id="title" name="title" required maxlength="160" value="{{ old('title', $advertisement->title) }}" style="width:100%;padding:11px;border:1px solid #d5dce5;border-radius:6px;"></div>
        <div><label for="target_url" style="display:block;margin-bottom:6px;font-weight:700;">Lien de destination</label><input id="target_url" name="target_url" type="url" required value="{{ old('target_url', $advertisement->target_url) }}" placeholder="https://..." style="width:100%;padding:11px;border:1px solid #d5dce5;border-radius:6px;"></div>
        <div><label for="image" style="display:block;margin-bottom:6px;font-weight:700;">Visuel {{ $advertisement->exists ? '(facultatif pour conserver l’actuel)' : '' }}</label>@if($advertisement->image_path)<img src="{{ asset('storage/'.$advertisement->image_path) }}" alt="" style="display:block;max-width:300px;max-height:120px;object-fit:contain;margin-bottom:10px;">@endif<input id="image" type="file" name="image" accept="image/*" @required(!$advertisement->exists)></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px;">
            <div><label for="placement" style="display:block;margin-bottom:6px;font-weight:700;">Emplacement</label><select id="placement" name="placement" style="width:100%;padding:10px;border:1px solid #d5dce5;border-radius:6px;">@foreach(['home' => 'Accueil', 'sidebar' => 'Colonne latérale', 'footer' => 'Pied de page'] as $value => $label)<option value="{{ $value }}" @selected(old('placement', $advertisement->placement ?: 'home') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div><label for="sort_order" style="display:block;margin-bottom:6px;font-weight:700;">Ordre d’affichage</label><input id="sort_order" type="number" name="sort_order" min="0" max="10000" value="{{ old('sort_order', $advertisement->sort_order ?? 0) }}" style="width:100%;padding:10px;border:1px solid #d5dce5;border-radius:6px;"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label for="starts_at" style="display:block;margin-bottom:6px;font-weight:700;">Début de diffusion</label><input id="starts_at" type="datetime-local" name="starts_at" value="{{ old('starts_at', $advertisement->starts_at?->format('Y-m-d\TH:i')) }}" style="width:100%;padding:10px;border:1px solid #d5dce5;border-radius:6px;"></div>
            <div><label for="ends_at" style="display:block;margin-bottom:6px;font-weight:700;">Fin de diffusion</label><input id="ends_at" type="datetime-local" name="ends_at" value="{{ old('ends_at', $advertisement->ends_at?->format('Y-m-d\TH:i')) }}" style="width:100%;padding:10px;border:1px solid #d5dce5;border-radius:6px;"></div>
        </div>
        <label style="display:flex;gap:9px;align-items:center;"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $advertisement->exists ? $advertisement->is_active : true))> Publicité active</label>
        <div style="display:flex;gap:12px;align-items:center;"><button type="submit" style="padding:11px 17px;border:0;border-radius:6px;background:#102b46;color:white;font-weight:700;cursor:pointer;">Enregistrer</button><a href="{{ route('admin.advertisements.index') }}" style="color:#718096;">Annuler</a></div>
    </form>
</div>
@endsection
