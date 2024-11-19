<?php
include 'conecta.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$message = '';

function executeQuery($sql, $params, $types) {
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    return $stmt->execute() ? "Operação realizada com sucesso" : "Erro na operação";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usu_nome = trim($_POST["usu_nome"] ?? '');
    $usu_email = trim($_POST["usu_email"] ?? '');

    if (!empty($usu_nome) && !empty($usu_email)) {
        $sql = "INSERT INTO usuarios (usu_nome, usu_email) VALUES (?, ?)";
        $message = executeQuery($sql, [$usu_nome, $usu_email], 'ss');
    } else {
        $message = "Por favor, preencha todos os campos.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
    <style>
        /* Definindo a cor principal rosa */
        :root {
            --rosa-claro: #f7a1c7;
            --rosa-escuro: #d84e87;
            --branco: #ffffff;
            --cinza-claro: #f1f1f1;
        }

        /* Resetando margens e padding */
        body, h1, h2, p, form {
            margin: 0;
            padding: 0;
        }

        /* Corpo e fundo da página */
        body {
            font-family: Arial, sans-serif;
            background-color: var(--cinza-claro);
            color: #333;
            line-height: 1.6;
        }

        /* Barra de navegação */
        .navbar {
            background-color: var(--rosa-escuro);
            padding: 15px;
            text-align: center;
        }

        .navbar h1 {
            color: var(--branco);
            font-size: 2rem;
        }

        /* Menu de navegação */
        .menu {
            margin-top: 10px;
        }

        .menu a {
            color: var(--branco);
            margin: 0 15px;
            text-decoration: none;
            font-size: 1rem;
        }

        .menu a:hover {
            text-decoration: underline;
        }

        /* Estilo do conteúdo */
        .content {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: var(--branco);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            color: var(--rosa-escuro);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 1rem;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }

        .form-group input:focus {
            border-color: var(--rosa-escuro);
            outline: none;
        }

        /* Estilo do botão */
        .btn {
            background-color: var(--rosa-escuro);
            color: var(--branco);
            padding: 10px 15px;
            font-size: 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: var(--rosa-claro);
        }

        /* Estilo da mensagem */
        .message {
            color: var(--rosa-escuro);
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Gerenciamento de Tarefas</h1>
        <div class="menu">
            <a href="#">Cadastro de Usuários</a>
            <a href="cadastro_de_tarefas.php">Cadastro de Tarefas</a>
            <a href="gerenciamento_de_tarefas.php">Gerenciar Tarefas</a>
        </div>
    </div>
    <div class="content">
        <h2>Cadastro de Usuários</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="usu_nome">Nome:</label>
                <input type="text" id="usu_nome" name="usu_nome">
            </div>
            <div class="form-group">
                <label for="usu_email">Email:</label>
                <input type="email" id="usu_email" name="usu_email">
            </div>
            <button type="submit" name="add_usuario" class="btn">Cadastrar</button>
        </form>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

