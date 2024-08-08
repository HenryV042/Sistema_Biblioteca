<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="adicionarTurma.css">

    <title>Document</title>
</head>

<body>
<div class="topbar" id="header"></div>

<h1>Turmas</h1>
<table id="turmas-table">
    <thead>
        <tr>
            <th>Identificação</th>
            <th>Ano</th>
            <th>Status</th>
            <th>Visualizar</th>
            <th>Editar</th>
            <th>Adicionar Aluno</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dados serão inseridos aqui via JavaScript -->
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('#header').load('../../Component/Menu_Nav');
    });

    document.addEventListener('DOMContentLoaded', function () {
        fetch('getAllTurmas.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#turmas-table tbody');
                tableBody.innerHTML = ''; // Clear the table body
                const currentYear = new Date().getFullYear();

                data.forEach(turma => {
                    const statusClass = currentYear > turma.ano_conclusao ? 'inactive' : 'active';
                    const statusText = currentYear > turma.ano_conclusao ? 'Desativado' : 'Ativado';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${turma.nome_identificacao}</td>
                        <td>${turma.ano_inicio}</td>
                        <td><span class="status-icon ${statusClass}">${statusText}</span></td>
                        <td><button class="btn btn-view"><i class="fas fa-eye"></i> Visualizar</button></td>
                        <td><button class="btn btn-edit"><i class="fas fa-edit"></i> Editar</button></td>
                        <td><button class="btn btn-add-student"><i class="fas fa-user-plus"></i> Adicionar Aluno</button></td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error:', error));
    });
</script>
</body>

</html>