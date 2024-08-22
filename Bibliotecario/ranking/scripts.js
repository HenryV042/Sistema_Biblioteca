function abrirMenu(){
  document.getElementById("menu-Oculto").style.width="400px";
  document.getElementById("principal").style.marginLeft="0px";
}
function fecharMenu(){
  document.getElementById("menu-Oculto").style.width="0vw";
  document.getElementById("principal").style.marginLeft="0vw";
}

// Função para aplicar filtros e atualizar gráficos
document.getElementById('aplicar-filtros').addEventListener('click', function() {
  const mesInicial = document.getElementById('mes-inicial').value;
  const mesFinal = document.getElementById('mes-final').value;
  const curso = document.getElementById('filtro-cursos').value;

  fetch('get_data.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
          mes_inicial: mesInicial,
          mes_final: mesFinal,
          curso: curso
      })
  })
  .then(response => response.json())
  .then(data => {
      drawCharts(data);
  })
  .catch(error => console.error('Erro ao buscar dados:', error));
});

// Função para desenhar gráficos
function drawCharts(data) {
  // Dados para o gráfico de livros
  const livrosData = google.visualization.arrayToDataTable([
      ['Livro', 'Total Leituras'],
      ...data.livros.map(livro => [livro.titulo_livro, Number(livro.total_leituras)])
  ]);

  const livrosOptions = {
      title: 'Ranking de Livros',
      pieHole: 0.4,
  };

  const livrosChart = new google.visualization.PieChart(document.getElementById('chart_livros'));
  livrosChart.draw(livrosData, livrosOptions);

  // Dados para o gráfico de cursos
  const cursosData = google.visualization.arrayToDataTable([
      ['Turma', 'Total Leituras'],
      ...data.cursos.map(curso => [curso.turma, Number(curso.total_leituras)])
  ]);

  const cursosOptions = {
      title: 'Ranking de Cursos',
      pieHole: 0.4,
  };

  const cursosChart = new google.visualization.PieChart(document.getElementById('chart_cursos'));
  cursosChart.draw(cursosData, cursosOptions);
}

// Carregando o Google Charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(() => {
  document.getElementById('aplicar-filtros').click(); // Carregar gráficos ao inicializar
});

function abrirMenu(){
  document.getElementById("menu-Oculto").style.width="400px";
  document.getElementById("principal").style.marginLeft="0px";
}
function fecharMenu(){
  document.getElementById("menu-Oculto").style.width="0vw";
  document.getElementById("principal").style.marginLeft="0vw";
}