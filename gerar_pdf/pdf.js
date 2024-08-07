const btnGenerate = document.querySelector("#generate_pdf");
btnGenerate.addEventListener("click", () => {
  fetch('emprestimos.php')
    .then(response => response.json())
    .then(data => {
      // Agrupar os empréstimos por série e curso
      let grupos = {};
      for (let emprestimo of data) {
        let chave = `${emprestimo.serie} - ${emprestimo.curso}`;
        if (!grupos[chave]) {
          grupos[chave] = [];
        }
        grupos[chave].push(emprestimo);
      }

      // Criar o conteúdo do PDF
      let content = document.querySelector("#content");
      content.innerHTML = '';
      let html = '';
      for (let chave in grupos) {
        let serie = chave.split(' - ')[0];
        let curso = chave.split(' - ')[1];
        html += `<h1>Turma ${serie} - ${curso}</h1>`;
        let meses = {};
        for (let emprestimo of grupos[chave]) {
          let mes = new Date(emprestimo.data_emprestimo).toLocaleString('pt-BR', { month: 'long' });
          if (!meses[mes]) {
            meses[mes] = [];
          }
          meses[mes].push(emprestimo);
        }
        for (let mes in meses) {
          html += `<h2>${mes}</h2>`;
          html += `
            <table>
              <tr>
                <th>Nome</th>
                <th>Livro</th>
                <th>Série</th>
              </tr>
          `;
          for (let emprestimo of meses[mes]) {
            html += `
              <tr>
                <td>${emprestimo.nome_aluno}</td>
                <td>${emprestimo.titulo_livro}</td>
                <td>${emprestimo.serie}</td>
              </tr>
            `;
          }
          html += `</table>`;
          html += `<br><br>`;
        }
        html += `<div style="page-break-after: always;"></div>`;
      }
      content.innerHTML = html;

      // Configuração do arquivo final de Pdf
      const options = {
        filename: "emprestimos.pdf",
        html2canvas: {scale: 2},
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait"},
      };

      // Gerar e Baixar PDF
      html2pdf().set(options).from(content).save();
    })
    .catch(error => console.error('Erro:', error));
});
