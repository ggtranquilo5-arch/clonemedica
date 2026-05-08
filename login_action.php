<?php
// RNF05: Controle de Sessão nativo do PHP
session_start();

// Inclui a conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        try {
            // RNF03: Segurança com Prepared Statements (PDO)
            $stmt = $pdo->prepare("SELECT id, nome, senha, tipo_usuario FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $usuario = $stmt->fetch();

            // Verifica se o usuário existe e se a senha está correta (RF01)
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                
                // Regenera o ID da sessão por segurança contra Session Hijacking
                session_regenerate_id(true);

                // Armazena os dados básicos na sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario']; // Administrador ou Requisitante (RF02)
                
                // Redireciona para o dashboard (tela inicial)
                header("Location: telainicial.html");
                exit;
            } else {
                // Falha na autenticação
                header("Location: index.html?erro=credenciais");
                exit;
            }
        } catch (PDOException $e) {
            // Falha no banco de dados
            die("Erro ao realizar login: " . $e->getMessage());
        }
    } else {
        // Campos em branco
        header("Location: index.html?erro=vazio");
        exit;
    }
} else {
    // Acesso direto ao arquivo sem POST
    header("Location: index.html");
    exit;
}
?>
