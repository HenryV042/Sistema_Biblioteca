<php?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="LoanRegister.css">
        <title>Registro De Empréstimo</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <body>
        <div class="Encapsulamento">
            <div class="grayBox">
                <div class="CadastrarEmprestimo">
                    <span>CADASTRAR EMPRÉSTIMO</span>
                </div>

                <form action="/submit_form" method="post">
                    <div class="form-group full-width">
                        <label for="studentName">Nome do Estudante:</label>
                        <input type="text" id="studentName" name="studentName" placeholder="Digite o nome do estudante"
                            required>
                    </div>

                    <div class="form-group full-width">
                        <label for="matriculation">Matrícula:</label>
                        <input type="text" id="matriculation" name="matriculation" placeholder="Digite a matrícula"
                            required>
                    </div>

                    <div class="form-group full-width">
                        <label for="bookTitle">Título do Livro:</label>
                        <input type="text" id="bookTitle" name="bookTitle" placeholder="Digite o título do livro"
                            required>
                    </div>

                    <div class="form-group full-width">
                        <label for="bookAuthor">Autor do Livro:</label>
                        <input type="text" id="bookAuthor" name="bookAuthor" placeholder="Digite o autor do livro"
                            required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="loanDate">Data de Empréstimo:</label>
                            <input type="date" id="loanDate" name="loanDate" required>
                        </div>

                        <div class="form-group">
                            <label for="registrationNumber">Número de Registro:</label>
                            <input type="text" id="registrationNumber" name="registrationNumber"
                                placeholder="Digite o número de registro" required>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="observations">Observações:</label>
                        <textarea id="observations" name="observations" rows="4"
                            placeholder="Digite observações adicionais"></textarea>
                    </div>

                    <div class="button-container">
                        <button type="submit">
                            <i class="fas fa-check"></i> ADICIONAR
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <script>
            // JavaScript para definir os limites do campo de data
            document.addEventListener("DOMContentLoaded", function () {
                const today = new Date();
                const currentMonth = today.getMonth();
                const currentYear = today.getFullYear();

                const nextMonth = new Date();
                nextMonth.setMonth(currentMonth + 1);

                const minDate = today.toISOString().split('T')[0];

                const dateInput = document.getElementById('loanDate');
                dateInput.setAttribute('min', minDate);

                // Centraliza a data no input (feito com CSS)
            });
        </script>
    </body>

    </html>
    </php>