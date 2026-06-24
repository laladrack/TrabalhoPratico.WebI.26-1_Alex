<?php
$host = 'localhost';
$db   = 'termo_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $jsonPath = __DIR__ . '/palavras.json';
    if (!file_exists($jsonPath)) {
        die("Erro: O arquivo palavras.json não foi encontrado.");
    }
    
    $jsonData = file_get_contents($jsonPath);
    $palavras = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Erro ao decodificar o arquivo JSON.");
    }

    $sql = "INSERT IGNORE INTO Dicionario_Palavras (palavra) VALUES (:palavra)";
    $stmt = $pdo->prepare($sql);

    $pdo->beginTransaction();
    
    $contador = 0;

    iterator_apply_json($palavras, function($valor) use ($stmt, &$contador) {
        if (!is_string($valor)) return;

        $palavraTratada = trim(mb_strtolower($valor, 'UTF-8'));
        
        $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $palavraLimpa = str_replace($comAcentos, $semAcentos, $palavraTratada);
        
        if (mb_strlen($palavraLimpa, 'UTF-8') === 5) {
            $stmt->execute([':palavra' => $palavraTratada]);
            $contador++;
        }
    });
    
    $pdo->commit();
    echo "Sucesso! Banco de dados povoado com $contador palavras de 5 letras.";

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Erro no banco de dados: " . $e->getMessage());
}

function iterator_apply_json($dados, $callback) {
    if (!is_array($dados)) {
        $callback($dados);
        return;
    }
    foreach ($dados as $chave => $valores) {
        if (is_array($valores)) {
            iterator_apply_json($valores, $callback);
        } else {
            $callback($valores);
        }
    }
}
