<?php
// include the database connection
require '../dependencies/config.php';

// Lê os arquivos JSON com os mapeamentos
$cddToGenreJson = file_get_contents('cddToGenre.json');
$cddToGenreDecJson = file_get_contents('cddToGenreDec.json');

// Decodifica os JSON em arrays PHP
$cddToGenre = json_decode($cddToGenreJson, true);
$cddToGenreDec = json_decode($cddToGenreDecJson, true);

// Cria um array para armazenar os comandos SQL
$sqlCommands = [];

// Adiciona os comandos SQL para os mapeamentos de cddToGenre
foreach ($cddToGenre as $cdd => $genre) {
    $sqlCommands[] = [
        'sql' => "UPDATE livros SET genero = :genero WHERE cdd = :cdd",
        'params' => [
            ':genero' => $genre,
            ':cdd' => $cdd
        ]
    ];
}

// Adiciona os comandos SQL para os mapeamentos de cddToGenreDec
foreach ($cddToGenreDec as $cdd => $genre) {
    $sqlCommands[] = [
        'sql' => "UPDATE livros SET genero = :genero WHERE cdd = :cdd",
        'params' => [
            ':genero' => $genre,
            ':cdd' => $cdd
        ]
    ];
}

// Comando SQL para definir 'Sem Gênero' para todos os livros cujo CDD não corresponde a nenhum mapeamento
$sqlCommands[] = [
    'sql' => "UPDATE livros SET genero = 'Sem Gênero' WHERE genero IS NULL",
    'params' => []
];

// Executa todos os comandos SQL
try {
    foreach ($sqlCommands as $command) {
        $stmt = $conn->prepare($command['sql']);
        $stmt->execute($command['params']);
    }
    echo "Gêneros atualizados com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao atualizar gêneros: " . $e->getMessage();
}
?>
