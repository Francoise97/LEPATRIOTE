@extends('admin.layout')

@section('title', 'Apparence du site')

@section('content')
<div style="max-width: 760px;">
    <h1 style="margin:0 0 8px;color:#102b46;">Apparence du site</h1>
    <p style="margin:0 0 24px;color:#718096;">Personnalisez le logo, les couleurs et la signature éditoriale.</p>

    @if(session('success'))
        <p role="status" style="padding:12px 16px;background:#e8f6ed;color:#176b37;">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <div role="alert" style="padding:12px 16px;margin-bottom:16px;background:#fff0f0;color:#9b1c31;">
            @foreach($errors->all() as $error)<p style="margin:4px 0;">{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" style="display:grid;gap:20px;padding:26px;background:#fff;border:1px solid #e7edf3;border-radius:12px;">
        @csrf
        @method('PUT')
        <div>
            <label for="brand_slogan" style="display:block;margin-bottom:7px;font-weight:700;">Slogan du site</label>
            <input id="brand_slogan" name="brand_slogan" maxlength="120" required value="{{ old('brand_slogan', $settings['brand_slogan']) }}" style="width:100%;padding:12px;border:1px solid #d5dce5;border-radius:6px;">
            <small style="display:block;margin-top:8px;color:#718096;">Aperçu typographique : <span class="{{ $settings['brand_font'] === 'sans' ? 'slogan-preview--sans' : 'slogan-preview--serif' }}" style="font-family:{{ $settings['brand_font'] === 'sans' ? 'Arial, sans-serif' : 'Georgia, serif' }};font-style:italic;">{{ $settings['brand_slogan'] }}</span></small>
        </div>
        <div>
            <label for="brand_font" style="display:block;margin-bottom:7px;font-weight:700;">Typographie du slogan</label>
            <select id="brand_font" name="brand_font" style="padding:10px;border:1px solid #d5dce5;border-radius:6px;">
                <option value="serif" @selected(old('brand_font', $settings['brand_font']) === 'serif')>Éditoriale — avec empattements</option>
                <option value="sans" @selected(old('brand_font', $settings['brand_font']) === 'sans')>Moderne — sans empattements</option>
            </select>
        </div>
        <div>
            <label for="logo" style="display:block;margin-bottom:7px;font-weight:700;">Logo</label>
            @if($settings['logo'])
                <img src="{{ asset('storage/'.$settings['logo']) }}" alt="Logo actuel" style="display:block;max-width:220px;max-height:90px;object-fit:contain;margin-bottom:12px;">
            @endif
            <input id="logo" type="file" name="logo" accept="image/*" style="display:block;">
            <small style="color:#718096;">PNG, JPEG, WebP ou SVG converti en image. Taille maximale : 4 Mo.</small>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;">
            <div>
                <label for="primary_color" style="display:block;margin-bottom:7px;font-weight:700;">Couleur principale</label>
                <input id="primary_color" type="color" name="primary_color" value="{{ old('primary_color', $settings['primary_color']) }}" style="width:100%;height:44px;padding:4px;border:1px solid #d5dce5;">
            </div>
            <div>
                <label for="accent_color" style="display:block;margin-bottom:7px;font-weight:700;">Couleur d’accent</label>
                <input id="accent_color" type="color" name="accent_color" value="{{ old('accent_color', $settings['accent_color']) }}" style="width:100%;height:44px;padding:4px;border:1px solid #d5dce5;">
            </div>
        </div>
        <button type="submit" style="width:fit-content;padding:12px 20px;border:0;border-radius:6px;background:#102b46;color:#fff;font-weight:700;cursor:pointer;">Enregistrer l’apparence</button>
    </form>
</div>
@endsection
