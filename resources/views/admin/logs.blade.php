@extends('tailwindify::admin.layout')

@section('header', '📋 Historique des logs')

@section('admin-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <pre class="bg-black text-success p-3 rounded small overflow-auto" style="max-height: 400px;">{{ $logs }}</pre>
                </div>
            </div>

            <div class="text-end mt-3">
                <form action="{{ route('tailwindify.admin.clear-logs') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        🗑️ Vider les logs
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
