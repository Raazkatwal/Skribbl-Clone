<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'ChitraKala' }}</title>
</head>

<body style="background-image: url('images/bg.png')" class="overflow-x-hidden">
    {{ $slot }}
</body>

</html>
