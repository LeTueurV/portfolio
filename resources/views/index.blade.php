<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - {{ $portfolio->full_name ?? 'BTS SIO' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,600&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'DM Sans', sans-serif;">
    @include('components.navbar')
    @include('components.hero')
    @include('components.profil')
    @include('components.realisations')
    @include('components.competences')
    @include('components.projets')
    @include('components.contact')
    @include('components.footer')

    @stack('scripts')
</body>
</html>
