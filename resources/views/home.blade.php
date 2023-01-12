<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Crud with Test</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    <main class="flex items-start justify-center min-h-screen w-full bg-white p-4 overflow-hidden">

        <div class="w-full sm:w-3/4 md:w-1/2 bg-slate-200 p-4 shadow">

            @yield('content')

        </div>

    </main>

</body>

</html>