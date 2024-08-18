<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação</title>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const redirectUrl = urlParams.get('redirect') || 'index.php'; // URL padrão se não fornecida

            if (status === 'success') {
                alert('Pedido realizado com sucesso!');
            } else if (status === 'error') {
                alert('Ocorreu um erro ao realizar o pedido. Por favor, tente novamente.');
            } else if (status === 'pedido_existente') {
                alert('Você já tem um pedido registrado com esta matrícula.');
            }

            // Redirecionar imediatamente após o alerta
            window.location.href = redirectUrl;
        };
    </script>
</head>
<body>
    <!-- Opcionalmente, você pode incluir algum conteúdo HTML adicional aqui -->
</body>
</html>
