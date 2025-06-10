document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("quiz-engasgo-form");
  const resultado = document.getElementById("resultado-engasgo");
  const carrossel = document.getElementById("carrossel-engasgo");
  const secaoCarrossel = document.getElementById("secao-carrossel");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Verifica se todas as perguntas foram respondidas
    const q1 = form.q1.value;
    const q2 = form.q2.value;
    const q3 = form.q3.value;

    if (q1 && q2 && q3) {
      carrossel.classList.remove("d-none");
      secaoCarrossel.classList.remove("d-none");
      secaoCarrossel.scrollIntoView({ behavior: "smooth" });
    } else {
      resultado.innerHTML = `<p class="pixel-font text-danger">Responda todas as perguntas antes de continuar.</p>`;
    }
  });

  // Sincroniza os textos com o carrossel de emergências
  const texts = document.querySelectorAll("#carouselText .carousel-text");

  $('#carouselEmergencias').on('slide.bs.carousel', function (e) {
    texts.forEach((text) => text.classList.add("d-none"));
    texts[e.to].classList.remove("d-none");
  });
});
