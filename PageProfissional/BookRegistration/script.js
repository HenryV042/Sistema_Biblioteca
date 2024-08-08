// script.js
const fileInput = document.getElementById('bookImage');
const fileInputContainer = document.getElementById('fileInputContainer');

fileInputContainer.addEventListener('click', () => {
  fileInput.click();
});

fileInputContainer.addEventListener('dragover', (event) => {
  event.preventDefault();
  fileInputContainer.classList.add('dragover');
});

fileInputContainer.addEventListener('dragleave', () => {
  fileInputContainer.classList.remove('dragover');
});

fileInputContainer.addEventListener('drop', (event) => {
  event.preventDefault();
  fileInputContainer.classList.remove('dragover');
  const files = event.dataTransfer.files;
  if (files.length > 0) {
    fileInput.files = files;
    // Optionally, trigger any change events or handle the file
    console.log('File dropped:', files[0]);
  }
});