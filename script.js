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
