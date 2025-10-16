<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Texto → Voz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <div class="container">
    <h2>Texto para Fala</h2>

    <form id="speechForm" class="mb-3">
      @csrf
      <div class="mb-2">
        <textarea id="text" name="text" class="form-control" rows="4" placeholder="Digite o texto..."></textarea>
      </div>
      <button class="btn btn-primary" type="submit">Converter</button>
    </form>

    <div id="audioContainer"></div>
  </div>

<script>
document.getElementById('speechForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const text = document.getElementById('text').value.trim();
  if (!text) return alert('Digite algum texto.');

  // envia a requisição
  try {
    const token = document.querySelector('input[name="_token"]').value;
    const res = await fetch('/speak', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify({ text })
    });

    // se não for 200, mostra erro
    if (!res.ok) {
      const txt = await res.text();
      throw new Error('Resposta do servidor: ' + txt);
    }

    const data = await res.json();

    // Segurança: se a API retornou texto de erro
    if (data.error) {
      throw new Error(data.error);
    }

    // 'data.audio_url' pode aparecer com barras escapadas na visualização do JSON,
    // mas aqui ele já é uma string correta. Substituição extra apenas por segurança.
    let audioUrl = data.audio_url;
    audioUrl = audioUrl.replace(/\\\//g, '/');

    // cria o player dinamicamente (sobrescreve qualquer player anterior)
    const container = document.getElementById('audioContainer');
    container.innerHTML = `
      <audio id="ttsPlayer" controls>
        <source src="${audioUrl}" type="audio/mpeg">
        Seu navegador não suporta áudio.
      </audio>
    `;

    const audioEl = document.getElementById('ttsPlayer');

    // tenta tocar (pode ser bloqueado por autoplay; como o usuário clicou no botão, normalmente funciona)
    const playPromise = audioEl.play();
    if (playPromise !== undefined) {
      playPromise.catch(err => {
        // autoplay bloqueado: o player aparecerá e o usuário pode clicar para reproduzir
        console.warn('Não foi possível reproduzir automaticamente:', err);
      });
    }

    // opcional: log para debug
    console.log('audio URL:', audioUrl);

  } catch (err) {
    console.error(err);
    alert('Erro: ' + (err.message || 'verifique o console'));
  }
});
</script>
</body>
</html>
