@extends('admin.layout')

@section('title', 'Aperçu multi-écrans')

@push('styles')
    <style>
        .preview-heading { display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:18px; margin-bottom:22px; }
        .preview-heading h1 { margin:0 0 7px; color:#102b46; font-size:26px; }
        .preview-heading p { margin:0; color:#718096; font-size:13px; line-height:1.55; }
        .preview-open-link { display:inline-flex; min-height:40px; align-items:center; padding:0 14px; border-radius:7px; background:#102b46; color:white; font-size:12px; font-weight:700; text-decoration:none; }
        .preview-toolbar { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:14px; padding:13px 15px; border:1px solid #e7edf3; border-bottom:0; border-radius:11px 11px 0 0; background:#fff; }
        .preview-modes { display:flex; flex-wrap:wrap; gap:8px; }
        .preview-mode, .preview-reload { min-height:36px; padding:0 12px; border:1px solid #d5dce5; border-radius:6px; background:#fff; color:#43546a; font:inherit; font-size:12px; font-weight:650; cursor:pointer; }
        .preview-mode[aria-pressed="true"] { border-color:#102b46; background:#102b46; color:#fff; }
        .preview-actions { display:flex; align-items:center; gap:8px; }
        .preview-size { min-width:95px; color:#718096; font-size:11px; text-align:right; }
        .preview-stage { display:flex; min-height:75vh; justify-content:center; overflow:auto; padding:24px; border:1px solid #e7edf3; border-radius:0 0 11px 11px; background-color:#e8edf3; background-image:radial-gradient(#cbd4df 1px, transparent 1px); background-size:18px 18px; }
        .preview-device { width:100%; min-width:320px; height:75vh; min-height:560px; overflow:hidden; border:1px solid #cbd4df; border-radius:8px; background:#fff; box-shadow:0 12px 35px #102b4626; transition:width .2s ease, border-radius .2s ease; }
        .preview-device[data-mode="tablet"] { width:768px; max-width:100%; }
        .preview-device[data-mode="mobile"] { width:390px; max-width:100%; height:780px; min-height:560px; border:8px solid #17283b; border-radius:27px; }
        .preview-frame { display:block; width:100%; height:100%; border:0; background:white; }
        @media (max-width:760px) {
            .preview-stage { min-height:65vh; padding:12px; }
            .preview-device { height:65vh; min-height:500px; }
            .preview-device[data-mode="mobile"] { height:720px; min-height:500px; }
        }
    </style>
@endpush

@section('content')
    <section aria-labelledby="preview-title">
        <div class="preview-heading">
            <div>
                <h1 id="preview-title">Aperçu des écrans</h1>
                <p>Consultez le site directement dans un aperçu ordinateur, tablette ou mobile.</p>
            </div>
            <a class="preview-open-link" href="{{ $previewUrl }}" target="_blank" rel="noopener noreferrer">Ouvrir le site dans un onglet ↗</a>
        </div>

        <div class="preview-toolbar" role="toolbar" aria-label="Choisir le format de l’aperçu">
            <div class="preview-modes">
                <button class="preview-mode" type="button" data-preview-mode="desktop" aria-pressed="true">Ordinateur</button>
                <button class="preview-mode" type="button" data-preview-mode="tablet" aria-pressed="false">Tablette</button>
                <button class="preview-mode" type="button" data-preview-mode="mobile" aria-pressed="false">Mobile</button>
            </div>
            <div class="preview-actions">
                <span class="preview-size" id="preview-size" aria-live="polite">Largeur adaptative</span>
                <button class="preview-reload" type="button" id="preview-reload">Actualiser</button>
            </div>
        </div>

        <div class="preview-stage">
            <div class="preview-device" id="preview-device" data-mode="desktop">
                <iframe class="preview-frame" id="site-preview-frame" src="{{ $previewUrl }}" title="Aperçu direct du site Le Patriote"></iframe>
            </div>
        </div>
    </section>

    <script>
        (() => {
            const device = document.getElementById('preview-device');
            const size = document.getElementById('preview-size');
            const frame = document.getElementById('site-preview-frame');
            const buttons = document.querySelectorAll('[data-preview-mode]');
            const widths = { desktop: 'Largeur adaptative', tablet: '768 px', mobile: '390 px' };

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    const mode = button.dataset.previewMode;
                    device.dataset.mode = mode;
                    size.textContent = widths[mode];
                    buttons.forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
                });
            });

            document.getElementById('preview-reload').addEventListener('click', () => {
                frame.src = frame.src;
            });
        })();
    </script>
@endsection
