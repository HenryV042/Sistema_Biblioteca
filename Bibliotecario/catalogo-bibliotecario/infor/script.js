document.addEventListener('DOMContentLoaded', function() {
    var selectNumeroRegistro = document.getElementById('numero_registro');
    
    selectNumeroRegistro.addEventListener('change', function() {
        var numeroRegistro = this.value;
        var tituloLivro = document.getElementById('titulo_livro').value;

        if (numeroRegistro === 'todos') {
            fetchBookDataByTitle(tituloLivro);
        } else {
            fetchBookData(numeroRegistro);
        }
    });

    function fetchBookData(numeroRegistro) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_livro.php?numero_registro=' + encodeURIComponent(numeroRegistro), true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var data = JSON.parse(xhr.responseText);
                if (data) {
                    // Atualizar os campos do formulário com os dados retornados
                    document.getElementById('titulo_livro').value = data.titulo_livro || '';
                    document.getElementById('autor').value = data.autor || '';
                    document.getElementById('cdu').value = data.cdu || '';
                    document.getElementById('cdd').value = data.cdd || '';
                    document.getElementById('origem').value = data.origem || '';
                    document.getElementById('editora').value = data.editora || '';
                    document.getElementById('local').value = data.local || '';
                    document.getElementById('genero').value = data.genero || '';
                    document.getElementById('ano_aquisicao').value = data.ano_aquisicao || '';
                    document.getElementById('quantidade_estoque').value = data.quantidade_estoque || '';
                    document.getElementById('selo').value = data.selo || '';
                    
                    // Preencher a imagem, se houver
                    var imagemLivro = document.getElementById('imagem_livro');
                    if (data.imagem) {
                        var imgSrc = 'data:image/jpeg;base64,' + btoa(data.imagem);
                        imagemLivro.src = imgSrc;
                    } else {
                        imagemLivro.src = 'path/to/default-image.png'; // Defina um caminho para uma imagem padrão
                    }
                }
            }
        };
        xhr.send();
    }

    function fetchBookDataByTitle(tituloLivro) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_livro.php?titulo_livro=' + encodeURIComponent(tituloLivro) + '&numero_registro=todos', true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var data = JSON.parse(xhr.responseText);
                if (data) {
                    document.getElementById('titulo_livro').value = data.titulo_livro || '';
                    document.getElementById('autor').value = data.autor || '';
                    document.getElementById('cdu').value = data.cdu || '';
                    document.getElementById('cdd').value = data.cdd || '';
                    document.getElementById('origem').value = data.origem || '';
                    document.getElementById('editora').value = data.editora || '';
                    document.getElementById('local').value = data.local || '';
                    document.getElementById('genero').value = data.genero || '';
                    document.getElementById('ano_aquisicao').value = data.ano_aquisicao || '';
                    document.getElementById('quantidade_estoque').value = data.quantidade_estoque || '';
                    document.getElementById('selo').value = data.selo || '';
                }
            }
        };
        xhr.send();
    }
});
