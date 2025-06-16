<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Askews Legal AI Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//unpkg.com/alpinejs" defer></script>

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-3xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-blue-800 mb-6">Askews Legal AI Demo</h1>
        @yield('content')
    </div>
</body>
</html>
