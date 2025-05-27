const express = require("express");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(express.json());

app.post("/localizacao", (req, res) => {
  const { latitude, longitude } = req.body;
  console.log("Localização recebida:", latitude, longitude);

  // Aqui você pode integrar com banco ou geocodificação reversa

  res.json({ mensagem: "Localização recebida com sucesso!" });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Servidor rodando na porta ${PORT}`);
});
