<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Lojinha</title>
</head>
<body>

<div class="h-16 mb-6">
    <x-cliente.header :categorias="$categorias"/>
</div>

<main>
    {{ $slot }}
</main>

<x-footer/>

</body>
</html>
