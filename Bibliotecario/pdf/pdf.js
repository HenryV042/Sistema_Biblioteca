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
      let cabecalho = `
        <div class="cabecario" style="position: relative;">
          <img src="logo.png" alt="" style="position: absolute; top: 0; left: 0; width: 90px; height: 50px;">
          <img src="coruja.png" alt="" style="position: absolute; top: 0; right: 0; width: 90px; height: 60px;">
          <div style="display: flex; justify-content: center; align-items: center;">
            <h1 style="font-family: 'Poppins', sans-serif; text-align: center; font-size: 24px;">Relatório de Empréstimos ${new Date().getFullYear()}</h1>
          </div>
          <div style="width: 98%; height: 4px; background: linear-gradient(to right, #FFA07A, #F7DC6F); margin-bottom: 20px;"></div>
        </div>
      `;

      for (let chave in grupos) {
        let serie = chave.split(' - ')[0];
        let curso = chave.split(' - ')[1];
        html += cabecalho; // Adicionar o cabeçalho em cada página
        html += `<h1 style="font-family: 'Poppins', sans-serif; text-align: center;">Turma ${serie} - ${curso}</h1>`;
        let meses = {};
        for (let emprestimo of grupos[chave]) {
          let mes = new Date(emprestimo.data_emprestimo).toLocaleString('pt-BR', { month: 'long' });
          if (!meses[mes]) {
            meses[mes] = [];
          }
          meses[mes].push(emprestimo);
        }
        for (let mes in meses) {
          html += `<h2 style="font-size: 24px; font-family: 'Poppins', sans-serif; text-align: center; background-color: #f7f7f7; width: 95.5%;  padding: 10px; text-transform: capitalize;">${mes}</h2>` ;
          html += `
            <table style="width: 90%; margin: 0 auto; border-collapse: collapse;">
              <tr>
                <th style="text-align: center; background-color: #f0f0f0; border: 1px solid #ddd; padding: 8px; font-weight: bold; font-family: 'Poppins', sans-serif;">Nome</th>
                <th style="text-align: center; background-color: #f0f0f0; border: 1px solid #ddd; padding: 8px; font-weight: bold; font-family: 'Poppins', sans-serif;">Livro</th>
                <th style="text-align: center; background-color: #f0f0f0; border: 1px solid #ddd; padding: 8px; font-weight: bold; font-family: 'Poppins', sans-serif;">Série</th>
              </tr>
              <style>
        </style>
          `;
          for (let emprestimo of meses[mes]) {
            html += `
              <tr>
                <td style="text-align: center; border: 1px solid #ddd; padding: 8px; font-family: 'Poppins', sans-serif;">${emprestimo.nome_aluno}</td>
                <td style="text-align: center; border: 1px solid #ddd; padding: 8px; font-family: 'Poppins', sans-serif;">${emprestimo.titulo_livro}</td>
                <td style="text-align: center; border: 1px solid #ddd; padding: 8px; font-family: 'Poppins', sans-serif;">${emprestimo.serie}</td>
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