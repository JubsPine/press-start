// engasgo.js - carrega perguntas via API PHP e controla submissão
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('quiz-engasgo-form');
  const resultadoEl = document.getElementById('resultado-engasgo');
  const secaoCarrossel = document.getElementById('secao-carrossel');
  const carregando = document.getElementById('carregando-questoes');

  // Busca as questões do backend
  fetch('../backend/quiz.php')
    .then(res => {
      if (!res.ok) throw new Error('Falha ao carregar perguntas');
      return res.json();
    })
    .then(questoes => {
      carregando && (carregando.style.display = 'none');
      form.innerHTML = ''; // limpar

      questoes.forEach((q, idx) => {
        const div = document.createElement('div');
        div.className = 'form-group';

        const label = document.createElement('label');
        label.innerText = (idx+1) + '. ' + q.QUESTAO;
        div.appendChild(label);

        q.respostas.forEach(r => {
          const idRadio = `q${q.ID_QUESTAO}_r${r.ID_RESPOSTA}`;
          const wrapper = document.createElement('div');
          wrapper.innerHTML = `<input type="radio" name="q${q.ID_QUESTAO}" id="${idRadio}" value="${r.ID_RESPOSTA}" required> <label for="${idRadio}"> ${r.RESPOSTA}</label>`;
          div.appendChild(wrapper);
        });

        form.appendChild(div);
      });

      // botão enviar
      const btn = document.createElement('button');
      btn.type = 'submit';
      btn.className = 'botao-retro pixel-font';
      btn.innerText = 'Ver Resultado';
      form.appendChild(btn);
    })
    .catch(err => {
      carregando && (carregando.innerText = 'Erro ao carregar perguntas.');
      console.error(err);
    });

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    // coletar respostas (apenas demonstração de lógica)
    const data = new FormData(form);
    const entries = [...data.entries()];
    // aqui você poderia enviar para backend para avaliação ou salvar em RESULTADOS
    let score = 0;
    entries.forEach(([k,v]) => {
      // valor do input é ID_RESPOSTA, mas precisamos do valor associado
      // Para simplificar, vamos calcular pontuação consultando atributo data-valor (não presente)
      // Em produção, envie as respostas ao backend para calcular.
    });

    resultadoEl.innerHTML = '<p class="text-white">Resultado: exercício concluído. Veja a manobra abaixo.</p>';
    // Mostrar carrossel
    secaoCarrossel.classList.remove('d-none');
    secaoCarrossel.scrollIntoView({ behavior: 'smooth' });
  });
});
