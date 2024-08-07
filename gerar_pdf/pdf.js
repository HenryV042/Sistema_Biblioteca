const btnGenerate = document.querySelector("#generate_pdf");
btnGenerate.addEventListener("click", () => {
  // Simulação de dados
  let emprestimos = [
    { id: 1, aluno_id: 1, matricula: '123456', titulo_livro: 'Livro 1', numero_registro: '123', curso: 'Informática', serie: '1A', data_emprestimo: '2023-02-01', nome: 'João' },
    { id: 2, aluno_id: 2, matricula: '234567', titulo_livro: 'Livro 2', numero_registro: '456', curso: 'Informática', serie: '1A', data_emprestimo: '2023-02-15', nome: 'Maria' },
    { id: 3, aluno_id: 3, matricula: '345678', titulo_livro: 'Livro 3', numero_registro: '789', curso: 'Administração', serie: '1B', data_emprestimo: '2023-03-01', nome: 'Pedro' },
    { id: 4, aluno_id: 4, matricula: '456789', titulo_livro: 'Livro 4', numero_registro: '012', curso: 'Administração', serie: '1B', data_emprestimo: '2023-03-15', nome: 'Ana' },
  ];

  // Agrupar os emprestimos por série e curso
  let grupos = {};
  for (let emprestimo of emprestimos) {
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
            <td>${emprestimo.nome}</td>
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
});
