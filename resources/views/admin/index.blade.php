@extends('tailwindify::admin.layout')

@section('header', '🏠 Accueil')

@section('admin-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="lead">Bienvenue sur <strong>Tailwindify</strong></p>
                    <ul class="list-unstyled">
                        <li>📌 <strong>Statut du thème actif :</strong>
                            <span class="badge bg-info">{{ $theme }}</span>
                        </li>
                        <li>📜 <strong>Dernière compilation Tailwind :</strong>
                            <span class="{{ $compilationExists ? 'text-success' : 'text-danger' }}">
                                {{ $lastCompilation }}
                                @if(!$compilationExists)
                                    <small class="text-muted">(Fichier output.css manquant)</small>
                                @endif
                            </span>
                        </li>
                    </ul>

                    @if(!$compilationExists)
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i> Le fichier CSS compilé n'existe pas.
                            <a href="{{ route('tailwindify.admin.force-compile') }}" class="alert-link">
                                Forcer la compilation
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function(){
            window.location.reload();
        }, 30000);
    </script>
@endsection
