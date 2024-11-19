<?php
include 'conecta.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$message = '';

// Função para executar uma query
function executeQuery($sql, $params, $types) {
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    return $stmt->execute() ? "Operação realizada com sucesso" : "Erro na operação";
}

// Carregar usuários para o dropdown
$usuarios = [];
$sql = "SELECT usu_id, usu_nome FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarefa_setor = trim($_POST["tarefa_setor"] ?? '');
    $tarefa_prioridade = trim($_POST["tarefa_prioridade"] ?? '');
    $tarefa_descricao = trim($_POST["tarefa_descricao"] ?? '');
    $tarefa_status = trim($_POST["tarefa_status"] ?? '');
    $usu_id = trim($_POST["usu_id"] ?? '');

    if (!empty($tarefa_setor) && !empty($tarefa_prioridade) && !empty($tarefa_descricao) && !empty($tarefa_status) && !empty($usu_id)) {
        $sql = "INSERT INTO tarefas (tarefa_setor, tarefa_prioridade, tarefa_descricao, tarefa_status, usu_id) VALUES (?, ?, ?, ?, ?)";
        $message = executeQuery($sql, [$tarefa_setor, $tarefa_prioridade, $tarefa_descricao, $tarefa_status, $usu_id], 'ssssi');
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
        /* Resetando alguns estilos padrão */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Corpo da página */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fb; /* Cor de fundo suave */
            color: #333;
            padding: 20px;
        }

        /* Barra de navegação */
        .navbar {
            background-color: #d81b60; /* Cor de fundo rosa vibrante para a barra de navegação */
            padding: 20px;
            color: white;
            text-align: center;
        }

        .navbar h1 {
            font-size: 36px;
            letter-spacing: 2px;
        }

        .menu {
            margin-top: 10px;
        }

        .menu a {
            color: #fff;
            margin: 0 15px;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .menu a:hover {
            color: #ff4081; /* Cor de hover rosa mais claro */
        }

        /* Estilo da seção de conteúdo */
        .content {
            margin-top: 30px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Estilo do formulário */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: #444;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #d81b60; /* Borda rosa para os campos de formulário */
            border-radius: 5px;
            font-size: 16px;
            color: #444;
            background-color: #fafafa;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #ff4081; /* Cor de foco para os campos */
            outline: none;
        }

        /* Estilo do botão */
        .btn {
            background-color: #ff4081; /* Cor rosa clara para os botões */
            color: white;
            font-size: 18px;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #d81b60; /* Cor rosa mais escura para o hover do botão */
        }

        /* Estilo da mensagem */
        .message {
            margin-top: 20px;
            padding: 15px;
            background-color: #f7f7f7;
            border-left: 5px solid #ff4081; /* Borda rosa para as mensagens */
            color: #444;
            font-size: 16px;
        }

    </style>
</head>
<body>
    <div class="navbar">
        <h1>Gerenciamento de Tarefas</h1>
        <div class="menu">
            <a href="index.php">Cadastro de Usuários</a>
            <a href="cadastro_de_tarefas.php">Cadastro de Tarefas</a>
            <a href="gerenciamento_de_tarefas.php">Gerenciar Tarefas</a>
        </div>
    </div>
    <div class="content">
        <h2>Cadastro de Tarefas</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="tarefa_setor">Setor:</label>
                <input type="text" id="tarefa_setor" name="tarefa_setor">
            </div>
            <div class="form-group">
                <label for="tarefa_prioridade">Prioridade:</label>
                <select id="tarefa_prioridade" name="tarefa_prioridade" required>
                    <option value="">Selecione a prioridade</option>
                    <option value="alta">Alta</option>
                    <option value="media">Média</option>
                    <option value="baixa">Baixa</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tarefa_descricao">Descrição:</label>
                <input type="text" id="tarefa_descricao" name="tarefa_descricao">
            </div>
            <div class="form-group">
                <label for="tarefa_status">Status:</label>
                <select id="tarefa_status" name="tarefa_status" required>
                    <option value="">Selecione o Status</option>
                    <option value="Em andamento">Em andamento</option>
                    <option value="Pendente">Pendente</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="usu_id">Usuário:</label>
                <select id="usu_id" name="usu_id" required>
                    <option value="">Selecione um usuário</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['usu_id']; ?>">
                            <?php echo $usuario['usu_nome']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_tarefa" class="btn">Cadastrar</button>
        </form>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>



