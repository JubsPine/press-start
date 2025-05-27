const canvas = document.getElementById('animacaoEscorpiao');
const ctx = canvas.getContext('2d');

let frame = 0;

function desenharCenario() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Pé (representado como bloco bege)
  ctx.fillStyle = '#f5deb3';
  ctx.fillRect(110, 40, 20, 40);

  // Escorpião (preto, se aproximando)
  const xEscorpiao = 10 + frame * 2; // movimenta o escorpião

  ctx.fillStyle = '#111';
  ctx.fillRect(xEscorpiao, 50, 20, 10); // corpo
  ctx.fillRect(xEscorpiao + 15, 48, 5, 5); // ferrão

  // Animação da "picada"
  if (frame >= 25 && frame < 30) {
    ctx.fillStyle = 'red';
    ctx.fillRect(115, 60, 5, 5); // ponto da picada
  }

  frame++;
  if (frame > 60) frame = 0; // reinicia a animação

  requestAnimationFrame(desenharCenario);
}

desenharCenario();