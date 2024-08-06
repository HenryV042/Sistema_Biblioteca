<php?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bookRegister.css">
        <title>Registro De Livros</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <body>
        <div class="Encapsulamento">
            <div class="grayBox">

                <div class="CadastrarLivro">
                    <span>CADASTRAR LIVRO</span>
                </div>

                <form action="/submit_form" method="post" enctype="multipart/form-data">
                    <div class="form-group full-width">
                        <div class="file-input-container">
                            <input type="file" id="bookImage" name="bookImage" accept="image/*" required>
                            <!-- <i class="fas fa-image file-icon"></i> -->
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="bookName">Nome do Livro:</label>
                        <input type="text" id="bookName" name="bookName" placeholder="Digite o nome do livro" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="author">Autor:</label>
                        <input type="text" id="author" name="author" placeholder="Digite o nome do autor" required>
                    </div>


                    <div class="form-row">
                        <div class="form-group">
                            <label for="cdu">CDU:</label>
                            <input type="text" id="cdu" name="cdu" placeholder="Digite o CDU" required>
                        </div>

                        <div class="form-group">
                            <label for="cdd">CDD:</label>
                            <input type="text" id="cdd" name="cdd" placeholder="Digite o CDD" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="origin">Origem:</label>
                            <input type="text" id="origin" name="origin" placeholder="Digite a origem" required>
                        </div>
                        <div class="form-group">
                            <label for="publisher">Editora:</label>
                            <input type="text" id="publisher" name="publisher" placeholder="Digite a editora" required>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group">
                            <label for="location">Local:</label>
                            <input type="text" id="location" name="location" placeholder="Digite o local" required>
                        </div>

                        <div class="form-group">
                            <label for="genre">Gênero:</label>
                            <input type="text" id="genre" name="genre" placeholder="Digite o gênero" required>
                        </div>

                        <div class="form-group">
                            <label for="seal">Selo:</label>
                            <select id="seal" name="seal" required>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="acquisitionYear">Ano de Aquisição:</label>
                            <input type="number" id="acquisitionYear" name="acquisitionYear"
                                placeholder="Digite o ano de aquisição" required>
                        </div>

                        <div class="form-group">
                            <label for="registrationNumber">Número de Registro:</label>
                            <input type="text" id="registrationNumber" name="registrationNumber"
                                placeholder="Digite o número de registro" required>
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="submit">
                            <i class="fas fa-check"></i> Cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>


    </html>
    </php>