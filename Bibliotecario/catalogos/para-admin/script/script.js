document.getElementById('txtBusca').addEventListener('input', function() {
    const query = this.value;
    if (query.length < 3) {
        return;
    }
    fetch(`search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            let resultsHtml = '';
            if (data.length > 0) {
                data.forEach(book => {
                    resultsHtml += `
                        <div class="shop-link">
                            <h3>${book.titulo}</h3>
                            <img src="${book.imagem}" alt="capa do livro" style="max-width: 100px; max-height: 150px;" loading="lazy">
                            <button class="botao-verde">+ informações</button>
                        </div>
                    `;
                });
            } else {
                resultsHtml = '<p>Nenhum livro encontrado.</p>';
            }
            document.getElementById('search-results').innerHTML = resultsHtml;
        })
        .catch(error => {
            console.error('Erro ao buscar livros:', error);
        });
});
