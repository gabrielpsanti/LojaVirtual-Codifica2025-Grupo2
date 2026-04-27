<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/chloe-teste/teste.css') }}">
    <title>Lojinha</title>
</head>
<body>

<x-cliente.header :categorias="$categorias"/>

<main>
    {{ $slot }}
</main>

<x-footer/>

</body>
</html>
