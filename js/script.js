if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(
    function (position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;

      fetch("https://9e09ee34-1050-485a-9e76-a2ebedc5a332-00-1um8t03yn84ly.spock.replit.dev/localizacao", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ latitude, longitude })
      })
        .then(res => res.json())
        .then(data => console.log("Resposta do backend:", data))
        .catch(err => console.error("Erro ao enviar localização:", err));
    },
    function (error) {
      console.error("Erro ao obter localização:", error.message);
    }
  );
}

function mostrarDetalhes(id) {
  // Oculta todos os detalhes
  const detalhes = document.querySelectorAll('.detalhe-emergencia');
  detalhes.forEach(detalhe => detalhe.classList.add('d-none'));

  // Exibe o detalhe selecionado
  const detalheSelecionado = document.getElementById(id);
  if (detalheSelecionado) {
    detalheSelecionado.classList.remove('d-none');
    detalheSelecionado.scrollIntoView({ behavior: 'smooth' });
  }
}

function voltar() {
  // Oculta todos os detalhes
  const detalhes = document.querySelectorAll('.detalhe-emergencia');
  detalhes.forEach(detalhe => detalhe.classList.add('d-none'));

  // Rola de volta para o carrossel
  const carrossel = document.getElementById('carouselEmergencias');
  if (carrossel) {
    carrossel.scrollIntoView({ behavior: 'smooth' });
  }
}
