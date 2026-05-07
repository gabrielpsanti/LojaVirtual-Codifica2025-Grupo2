<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Lojinha - Administração</title>
</head>
<body>

<div class="grid grid-cols-[min-content_auto]">
    <span class="w-fit h-full px-5 bg-transparent">
        <div class="w-36">
            <x-admin.menu/>
        </div>
    </span>
    <main>
        {{ $slot }}
    </main>
</div>

<x-footer/>

</body>
</html>
