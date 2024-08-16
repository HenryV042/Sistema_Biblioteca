function abrirMenu(){
    document.getElementById("menu-Oculto").style.width="400px";
    document.getElementById("principal").style.marginLeft="0px";
}
function fecharMenu(){
    document.getElementById("menu-Oculto").style.width="0vw";
    document.getElementById("principal").style.marginLeft="0vw";
}

document.addEventListener('DOMContentLoaded', function () {
    const numeroRegistroSelect = document.getElementById('numero_registro');
    const imagemLivro = document.querySelector('.img-livro img');
    const nomeLivroInput = document.querySelector('input[placeholder="NOME DO LIVRO"]');
    const urlParams = new URLSearchParams(window.location.search);
    const titulo = urlParams.get('titulo_livro'); // Recuperar o título da URL

    numeroRegistroSelect.addEventListener('change', function () {
        const numeroRegistro = this.value;

        if (numeroRegistro === 'todos') {
            if (titulo) {
                fetch(`getBookDetails.php?titulo_livro=${encodeURIComponent(titulo)}&tipo=todos`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        // Preencher o formulário com os dados do primeiro livro encontrado
                        imagemLivro.src = data.imagem || '../img/sem-foto.png';
                        nomeLivroInput.value = data.titulo_livro || '';
                        document.querySelector('input[placeholder="AUTOR"]').value = data.autor || '';
                        document.querySelector('input[placeholder="CDU"]').value = data.cdu || '';
                        document.querySelector('input[placeholder="CDD"]').value = data.cdd || '';
                        document.querySelector('input[placeholder="ORIGEM"]').value = data.origem || '';
                        document.querySelector('input[placeholder="EDITORA"]').value = data.editora || '';
                        document.querySelector('input[placeholder="LOCAL"]').value = data.local || '';
                        document.querySelector('input[placeholder="GÊNERO"]').value = data.genero || '';
                        document.querySelector('input[type="date"]').value = data.ano_aquisicao ? new Date(data.ano_aquisicao).toISOString().split('T')[0] : '';

                        // Atualizar o campo selo
                        const seloSelect = document.querySelector('#selo');
                        if (seloSelect) {
                            seloSelect.value = data.selo || '';
                        }

                        // Atualizar campo de estoque
                        document.querySelector('input[placeholder="QUANTIDADE EM ESTOQUE"]').value = `${data.total_livros} (${data.estoque_disponivel} disponíveis para empréstimo)`;
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao buscar os dados do livro.');
                    });
            }
        } else {
            fetch(`getBookDetails.php?numero_registro=${numeroRegistro}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Atualizar imagem do livro com a URL da API
                    imagemLivro.src = data.imagem || '../img/sem-foto.png';

                    // Preencher os campos do formulário com os dados retornados
                    nomeLivroInput.value = data.titulo_livro || '';
                    document.querySelector('input[placeholder="AUTOR"]').value = data.autor || '';
                    document.querySelector('input[placeholder="CDU"]').value = data.cdu || '';
                    document.querySelector('input[placeholder="CDD"]').value = data.cdd || '';
                    document.querySelector('input[placeholder="ORIGEM"]').value = data.origem || '';
                    document.querySelector('input[placeholder="EDITORA"]').value = data.editora || '';
                    document.querySelector('input[placeholder="LOCAL"]').value = data.local || '';
                    document.querySelector('input[placeholder="GÊNERO"]').value = data.genero || '';
                    document.querySelector('input[type="date"]').value = data.ano_aquisicao ? new Date(data.ano_aquisicao).toISOString().split('T')[0] : '';

                    // Atualizar o campo selo
                    const seloSelect = document.querySelector('#selo');
                    if (seloSelect) {
                        seloSelect.value = data.selo || '';
                    }

                    // Atualizar campo de estoque
                    document.querySelector('input[placeholder="QUANTIDADE EM ESTOQUE"]').value = `${data.total_livros} (${data.estoque_disponivel} disponíveis para empréstimo)`;
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao buscar os dados do livro.');
                });
        }
    });

    // Se já houver um valor selecionado na página de carregamento
    if (numeroRegistroSelect.value !== 'todos') {
        numeroRegistroSelect.dispatchEvent(new Event('change'));
    }
});


// Funções para abrir e fechar o menu
function abrirMenu() {
    document.getElementById('menu-Oculto').style.width = '250px';
}

function fecharMenu() {
    document.getElementById('menu-Oculto').style.width = '0';
}

// Funções para abrir e fechar o menu
function abrirMenu() {
    document.getElementById('menu-Oculto').style.width = '250px';
}

function fecharMenu() {
    document.getElementById('menu-Oculto').style.width = '0';
}

// Funções para abrir e fechar o menu
function abrirMenu() {
    document.getElementById('menu-Oculto').style.width = '250px';
}

function fecharMenu() {
    document.getElementById('menu-Oculto').style.width = '0';
}

// Funções para abrir e fechar o menu
function abrirMenu() {
    document.getElementById('menu-Oculto').style.width = '250px';
}

function fecharMenu() {
    document.getElementById('menu-Oculto').style.width = '0';
}
