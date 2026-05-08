<?php
// RNF05: Controle de Sessão
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? 'Requisitante'; // Padrão

    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($tipo)) {
        
        // Hash seguro da senha para o banco de dados
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            // RNF03: Segurança com Prepared Statements (PDO)
            // Verificamos primeiro se o e-mail já existe
            $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
            $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                // E-mail já cadastrado
                header("Location: index.html?erro=email_existente");
                exit;
            }

            // Inserção do novo usuário
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (:nome, :email, :senha, :tipo)");
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senha_hash, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                // Cadastro concluído com sucesso
                header("Location: index.html?sucesso=cadastro");
                exit;
            } else {
                die("Erro ao salvar no banco de dados.");
            }
        } catch (PDOException $e) {
            die("Erro no banco de dados: " . $e->getMessage());
        }
    } else {
        // Campos em branco
        header("Location: index.html?erro=campos_vazios");
        exit;
    }
} else {
    // Acesso direto sem POST
    header("Location: index.html");
    exit;
}
?>
