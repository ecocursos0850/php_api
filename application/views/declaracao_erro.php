<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Erro</title>
    <style>
        body { font-family: sans-serif; background: #f8d7da; color: #721c24; padding: 30px; }
        .container { background: #f5c6cb; padding: 20px; border-radius: 6px; max-width: 600px; margin: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Erro ao processar a solicitação</h2>
        <p><strong>Mensagem:</strong> <?= htmlspecialchars($mensagem) ?></p>
        <p><strong>Código:</strong> <?= $codigo ?></p>
        <p><a href="javascript:history.back()">Voltar</a></p>
    </div>
</body>
</html>
