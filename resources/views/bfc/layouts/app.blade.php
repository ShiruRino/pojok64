<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Best Friend Chicken')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
        }
        nav a {
            color: white;
        }
        nav a:hover {
            color: #ddd;
        }
        main {
            padding: 2rem;
        }
        @media(max-width: 375px){
            .card-body{
                overflow-x: scroll;
            }
        }
        .nav-item{
            color: white;
            padding: 0.5rem;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-danger px-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('user.home') }}">BFC</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto" style="gap: 0.5rem">
                @include('bfc.layouts.navbar-items')
            </ul>

        </div>
    </div>
</nav>

<main class="container">
    @if (session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger mb-4">{{ $error }}</div>
        @endforeach
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
