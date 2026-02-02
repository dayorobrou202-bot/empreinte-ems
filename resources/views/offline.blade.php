<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Application hors ligne</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="max-w-xl mx-auto p-8 bg-white rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Application temporairement indisponible</h1>
        <p class="text-gray-600 mb-4">La connexion à la base de données a échoué. L'application est en mode "offline" pour éviter des erreurs internes.</p>
        @if(!empty($error))
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4"><strong>Détail :</strong> {{ Str::limit($error, 200) }}</div>
        @endif
        <div class="flex gap-2">
            <a href="/" onclick="window.location.reload(); return false;" class="px-4 py-2 bg-blue-600 text-white rounded">Rafraîchir</a>
            <a href="mailto:devteam@example.com" class="px-4 py-2 border rounded">Contacter le support</a>
        </div>
    </div>
</body>
</html>
