<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Texto para Voz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center p-5">

    <div class="container">
        <h2 class="mb-4">Conversor de Texto para Voz</h2>

        <form action="{{ route('speak') }}" method="POST" target="audioFrame">
            @csrf
            <textarea name="text" class="form-control mb-3" rows="3" placeholder="Digite o texto aqui..." required></textarea>
            <button type="submit" class="btn btn-primary">Converter em Áudio</button>
        </form>

        <h4 class="mt-4">Resultado:</h4>
        <iframe name="audioFrame" style="width:100%; height:80px; border:none;"></iframe>
    </div>

</body>
</html>