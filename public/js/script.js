if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(
    function (position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;

      // Chama API passando coordenadas
      fetch(`backend/api.php?acao=listar_postos&lat=${latitude}&lng=${longitude}`)
        .then(res => res.json())
        .then(data => {
          console.log("Postos próximos:", data);

          // Exemplo: exibir em lista
          const lista = document.getElementById("lista-postos");
          lista.innerHTML = "";
          data.forEach(posto => {
            const li = document.createElement("li");
            li.textContent = `${posto.NOME} - ${posto.ENDERECO} (${posto.TELEFONE})`;
            lista.appendChild(li);
          });
        })
        .catch(err => console.error("Erro ao buscar postos:", err));
    },
    function (error) {
      console.error("Erro ao obter localização:", error.message);
    }
  );
}
