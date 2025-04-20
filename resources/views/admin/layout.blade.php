@extends('admin.layouts.admin')

@section('title', 'Tailwindify')

@section('content')
    <div class="row">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px; height: 60vh;">
            <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">⚡ Tailwindify</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('tailwindify.admin.index') }}" class="nav-link text-white {{ request()->routeIs('tailwindify.admin.index') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li>
                    <a href="{{ route('tailwindify.admin.logs') }}" class="nav-link text-white {{ request()->routeIs('tailwindify.admin.logs') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-file-earmark-text"></i> Logs
                    </a>
                </li>
                <li>
                    <a href="{{ route('tailwindify.admin.bootstrap') }}" class="nav-link text-white {{ request()->routeIs('tailwindify.admin.bootstrap') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-arrow-repeat"></i> Conversion Bootstrap
                    </a>
                </li>
            </ul>
            <hr>
            <div class="text-center">
                <small>&copy; 2025 Tailwindify</small>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="m-0">@yield('header', '🏠 Dashboard')</h4>
                    <form action="{{ route('tailwindify.admin.force-compile') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">⚙️ Rebuild Tailwind</button>
                    </form>
                </div>
                <div class="card-body">
                    @yield('admin-content')
                </div>
            </div>
        </div>
    </div>
@endsection
