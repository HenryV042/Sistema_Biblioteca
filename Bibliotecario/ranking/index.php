<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/index_graph.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Css/buttons.css"> <!-- Link para o CSS dos botões -->
</head>

<body>
    <header>
        <nav>
            <aside id="menu-Oculto" class="menu-Oculto">
                <div class="imagemMenu">
                    <img src="img/logoMenu.png" alt="" class="logoMenu">
                    <button class="fechar" href="" onclick="fecharMenu()">&times;</button>
                        
                </div>
                    
                <div class="linha"></div>
                <div class="opcoes">
        
                    <a href=""><i class="fa-solid fa-file"></i>    Cadastrar Livro</a>
                    <a href=""><i class="fa-solid fa-book-open-reader"></i>    Cadastrar Empréstimo</a>
                    <a href=""><i class="fa-solid fa-book-bookmark"></i>    Banco de Livros</a>
                    <a href=""><i class="fa-brands fa-leanpub"></i>    Empréstimos</a>
                    <a href=""><i class="fa-solid fa-user-plus"></i>    Adicionar Turma</a>
                    <a href=""><i class="fa-solid fa-address-book"></i>    Pedidos</a>
                    <a href=""><i class="fa-solid fa-file-import"></i>    Relatório</a>
                    <a href="" class="sair"><i class="fa-solid fa-circle-xmark"></i>    Sair</a>
                </div>
                    
            </aside>
            <section id="principal">
                <span style="font-size:30px;cursor:pointer"onclick="abrirMenu()">&#9776;</span>
                <div class="nav-logo">
                    <img  src="img/logoEEEP.png" alt="logo" class="logo_eeep"/>
                    <div class="ret"></div>
                    <img src="img/logoNav.png" alt="logo" class="library"/>
                </div>
        
            </section>
        </nav>
    </header>

    <!-- RANKING e Botões -->
    <div class="title">
        <div class="h1">RANKING</div>
    </div>

    <div class="buttons-container">
        <button class="dropdown-btn">MÊS INICIAL <i class="fa-solid fa-chevron-down"></i></button>
        <div class="dropdown-content">
            <select name="mes-inicial" id="mes-inicial">
                <option value="janeiro">Janeiro</option>
                <option value="fevereiro">Fevereiro</option>
                <option value="marco">Março</option>
                <option value="abril">Abril</option>
                <option value="maio">Maio</option>
                <option value="junho">Junho</option>
                <option value="julho">Julho</option>
                <option value="agosto">Agosto</option>
                <option value="setembro">Setembro</option>
                <option value="outubro">Outubro</option>
                <option value="novembro">Novembro</option>
                <option value="dezembro">Dezembro</option>
            </select>
        </div>

        <button class="dropdown-btn">MÊS FINAL <i class="fa-solid fa-chevron-down"></i></button>
        <div class="dropdown-content">
            <select name="mes-final" id="mes-final">
                <option value="janeiro">Janeiro</option>
                <option value="fevereiro">Fevereiro</option>
                <option value="marco">Março</option>
                <option value="abril">Abril</option>
                <option value="maio">Maio</option>
                <option value="junho">Junho</option>
                <option value="julho">Julho</option>
                <option value="agosto">Agosto</option>
                <option value="setembro">Setembro</option>
                <option value="outubro">Outubro</option>
                <option value="novembro">Novembro</option>
                <option value="dezembro">Dezembro</option>
            </select>
        </div>

        <button class="report-btn"><i class="fa-solid fa-print"></i> RELATÓRIO</button>
    </div>
    

    <!-- Gráficos -->
    <div class="charts-container">
        <div id="chart_livros"></div>
        <div id="chart_cursos"></div>
    </div>

    <script type="text/javascript">
        // Código dos gráficos
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            var data1 = google.visualization.arrayToDataTable([
                ['Livro', 'Leituras'],
                ['Maze Runner', 100],
                ['Vidas Secas', 85],
                ['Senhora', 70],
                ['O Guarani', 65],
                ['Dom Casmurro', 50]
            ]);

            var options1 = {
                title: 'LIVROS MAIS LIDOS',
                titleTextStyle: { color: 'green' },
                bars: 'vertical',
                /* hAxis: { title: 'Livros' },
                vAxis: { title: 'Leituras' }, */
                bar: { groupWidth: '55%' },
                colors: ['#4d4d4d'],
                backgroundColor: 'transparent'
            };

            var chart1 = new google.visualization.ColumnChart(document.getElementById('chart_livros'));
            chart1.draw(data1, options1);

            var data2 = google.visualization.arrayToDataTable([
                ['Curso', 'Leituras'],
                ['Enfermagem', 90],
                ['Informática', 75],
                ['Comércio', 60],
                ['Administração', 45]
            ]);

            var options2 = {
                title: 'CURSO COM MAIS LEITORES',
                titleTextStyle: { color: 'green' },
                bars: 'vertical',
               /*  hAxis: { title: 'Cursos' },
                vAxis: { title: 'Leituras' }, */
                bar: { groupWidth: '55%' },
                colors: ['#0000FF'],
                backgroundColor: 'transparent'
            };

            var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_cursos'));
            chart2.draw(data2, options2);
        }
        
        // Código para mostrar e ocultar os dropdowns
        document.querySelectorAll('.dropdown-btn').forEach(button => {
            button.addEventListener('click', function() {
                const dropdownContent = this.nextElementSibling;
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            });
        });

        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-btn')) {
                document.querySelectorAll('.dropdown-content').forEach(content => {
                    if (content.style.display === 'block') {
                        content.style.display = 'none';
                    }
                });
            }
        };
    </script>
    <script type="text/javascript" src="scripts.js"></script>
</body>

</html>
