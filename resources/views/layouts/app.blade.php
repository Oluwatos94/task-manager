<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager')</title>
    <link rel="stylesheet" href="{{ asset('css/custom-element.css') }}">
    @stack('styles')
</head>
<body>
    <header>
        <nav>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Footer content -->
    </footer>

    @stack('scripts')
</body>
</html>
