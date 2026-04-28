<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Lojinha - Administração</title>
</head>
<body>

<x-admin.menu/>

<main>
    {{ $slot }}
</main>

<x-footer/>

</body>
</html>
