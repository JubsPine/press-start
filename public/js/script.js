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