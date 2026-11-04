<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['props']['title'] ?? 'Le Patriote' }}</title>
    <link rel="stylesheet" href="/resources/css/app.css">
    <script src="http://localhost:5173/resources/js/app.jsx" type="module"></script>
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
