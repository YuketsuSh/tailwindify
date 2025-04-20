@extends('tailwindify::admin.layout')

@section('header', '🎨 Correspondance Bootstrap → Tailwind')

@section('admin-content')
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted">Voici les classes Bootstrap et leur équivalent en Tailwind. Vous pouvez ajouter, modifier ou supprimer des remplacements ici.</p>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Ajouter un remplacement</h5>
            <form action="{{ route('tailwindify.admin.bootstrap.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="bootstrap_class" class="form-label">Classe Bootstrap</label>
                    <input type="text" name="bootstrap_class" class="form-control" placeholder="Ex: container" required>
                </div>
                <div class="mb-3">
                    <label for="tailwind_class" class="form-label">Classe Tailwind</label>
                    <input type="text" name="tailwind_class" class="form-control" placeholder="Ex: max-w-7xl mx-auto px-4" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th scope="col">🛠️ Classe Bootstrap</th>
                <th scope="col">🎨 Classe Tailwind</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($replacements as $replacement)
                <tr>
                    <td><code>{{ $replacement->bootstrap_class }}</code></td>
                    <td class="text-primary"><code>{{ $replacement->tailwind_class }}</code></td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $replacement->id }}">Modifier</a>

                        <div class="modal fade" id="editModal-{{ $replacement->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('tailwindify.admin.bootstrap.update', $replacement->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Modifier le remplacement</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="bootstrap_class" class="form-label">Classe Bootstrap</label>
                                                <input type="text" name="bootstrap_class" class="form-control" value="{{ $replacement->bootstrap_class }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tailwind_class" class="form-label">Classe Tailwind</label>
                                                <input type="text" name="tailwind_class" class="form-control" value="{{ $replacement->tailwind_class }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('tailwindify.admin.bootstrap.delete', $replacement->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-3 mt-3">
        <form action="{{ route('tailwindify.admin.bootstrap.scan') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">Relancer le scan des classes Bootstrap</button>
        </form>
    </div>
    <div class="table-responsive mt-3">
        <h5>Classes Bootstrap détectées</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th scope="col">Classe Bootstrap</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($bootstrapClasses as $class)
                <tr>
                    <td><code>{{ $class }}</code></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
