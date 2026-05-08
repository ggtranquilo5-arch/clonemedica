<?php
// RNF01, RNF02: PHP Puro com MariaDB/MySQL (XAMPP)
$host = '127.0.0.1';
$dbname = 'gestao_inventario';
$user = 'root'; // Usuário padrão do XAMPP
$pass = '';     // Senha padrão do XAMPP (vazia)

try {
    // RNF03: Segurança via PDO e Prepared Statements (Prevenção de SQL Injection)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Retorna os dados como array associativo por padrão
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Em produção, não exiba o erro real para o usuário, mas salve em logs
    die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
}
?>
