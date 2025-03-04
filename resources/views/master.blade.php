<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Homepage')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="https://nanybot-landing.s3.us-west-1.amazonaws.com/assets/images/nanybot-logo-128x128.webp" type="image/webp">
    @yield('css')
    <style>

    </style>
</head>

<body>

{{--Views other than homepage may pass headerTitle, So it's optional--}}
@include('layouts.partials.header', ['headerTitle' => $serviceName ?? null, 'serviceAppUrl' => $serviceAppUrl])

<main class="pt-8">
    @yield('content')
</main>

@include('layouts.partials.footer')

@yield('js')
</body>
</html>
