// script.js
const imagemInput = document.getElementById('imagem');
const imagemPreview = document.getElementById('imagem-preview');

imagemInput.addEventListener('change', () => {
  const file = imagemInput.files[0];
  const reader = new FileReader();

  reader.onload = (e) => {
    imagemPreview.src = e.target.result;
  };

  if (file) {
    reader.readAsDataURL(file);
  } else {
    imagemPreview.src = "img/imagem-placeholder.png"; // Define o placeholder caso nenhum arquivo seja selecionado
  }
});