<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur serveur — UCO</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f7f4ef; color: #1a2332; margin: 0; }
        main { max-width: 720px; margin: 4rem auto; padding: 0 1.25rem; }
        h1 { font-size: 1.8rem; }
        pre { background: #1a2332; color: #f7f4ef; padding: 1rem; overflow: auto; border-radius: .4rem; white-space: pre-wrap; }
        a { color: #0d5c63; }
    </style>
</head>
<body>
<main>
    <h1>Une erreur est survenue</h1>
    <p>Le site n’a pas pu traiter cette requête. Vérifiez la configuration (base de données, fichier <code>.env</code>) puis réessayez.</p>
    <?php if (!empty($debug) && !empty($exception) && $exception instanceof \Throwable): ?>
        <pre><?= htmlspecialchars($exception->__toString(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></pre>
    <?php endif; ?>
    <p><a href="/">Retour à l’accueil</a></p>
</main>
</body>
</html>
