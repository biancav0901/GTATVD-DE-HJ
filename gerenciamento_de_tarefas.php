<?php
include 'conecta.php';

// Deletar tarefa
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM tarefas WHERE tarefa_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Tarefa deletada com sucesso!'); window.location.href = 'gerenciamento_de_tarefas.php';</script>";
    } else {
        echo "Erro ao deletar: " . $conn->error;
    }
    $stmt->close();
}

// Atualizar status da tarefa
if (isset($_POST['update_status'])) {
    $tarefa_id = $_POST['tarefa_id'];
    $novo_status = $_POST['novo_status'];
    $stmt = $conn->prepare("UPDATE tarefas SET tarefa_status = ? WHERE tarefa_id = ?");
    $stmt->bind_param("si", $novo_status, $tarefa_id);
    if ($stmt->execute()) {
        echo "<script>alert('Status atualizado com sucesso!'); window.location.href = 'gerenciamento_de_tarefas.php';</script>";
    } else {
        echo "Erro ao atualizar status: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
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
            background-color: #fff8e1; /* Fundo mais claro e vibrante */
            color: #333;
            padding: 20px;
        }

        /* Barra de navegação */
        .navbar {
            background-color: #ff4081; /* Cor mais chamativa para o topo */
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
            margin: 0 20px;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .menu a:hover {
            color: #ffeb3b; /* Cor de destaque no hover */
        }

        /* Seção de conteúdo */
        .content {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            font-size: 28px;
            color: #e91e63; /* Cor mais chamativa para o título */
            margin-bottom: 20px;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #ff4081; /* Cor de fundo vibrante no cabeçalho */
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #fce4ec; /* Linhas alternadas com cor suave */
        }

        table tr:hover {
            background-color: #ffe0b2; /* Cor de destaque no hover */
        }

        /* Estilo dos botões */
        .btn {
            background-color: #4caf50;
            color: white;
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #388e3c; /* Cor do botão no hover */
        }

        .delete {
            background-color: #f44336;
            margin-left: 10px;
        }

        .delete:hover {
            background-color: #d32f2f; /* Cor do botão delete no hover */
        }

        /* Estilo para o formulário de status */
        select {
            padding: 8px;
            font-size: 14px;
            border: 2px solid #ff4081; /* Cor da borda de seleção */
            border-radius: 5px;
            background-color: #fff3e0;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color: #ffeb3b; /* Cor de foco */
            outline: none;
        }

        /* Mensagens e alertas */
        .message {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffe0b2;
            border-left: 5px solid #4caf50;
            color: #444;
            font-size: 16px;
        }

    </style>
</head>
<body>
    <div class="navbar">
        <h1>Gerenciamento de Tarefas</h1>
        <div class="menu">
            <a href="index.php">Cadastro usuários</a>
            <a href="cadastro_de_tarefas.php">Cadastro de tarefas</a>
        </div>
    </div>

    <div class="content">
        <h2>Gerenciamento de Tarefas</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Setor</th>
                <th>Prioridade</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Usuário</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = "SELECT tarefas.tarefa_id, tarefas.tarefa_setor, tarefas.tarefa_prioridade, tarefas.tarefa_descricao, tarefas.tarefa_status, usuarios.usu_nome 
                    FROM tarefas 
                    JOIN usuarios ON tarefas.usu_id = usuarios.usu_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['tarefa_id'] . "</td>";
                    echo "<td>" . $row['tarefa_setor'] . "</td>";
                    echo "<td>" . $row['tarefa_prioridade'] . "</td>";
                    echo "<td>" . $row['tarefa_descricao'] . "</td>";
                    echo "<td>" . $row['tarefa_status'] . "</td>";
                    echo "<td>" . $row['usu_nome'] . "</td>";
                    echo "<td>
                            <form method='post' style='display:inline-block;'>
                                <input type='hidden' name='tarefa_id' value='" . $row['tarefa_id'] . "'>
                                <select name='novo_status'>
                                    <option value='Pendente'" . ($row['tarefa_status'] == 'Pendente' ? ' selected' : '') . ">Pendente</option>
                                    <option value='Em andamento'" . ($row['tarefa_status'] == 'Em andamento' ? ' selected' : '') . ">Em andamento</option>
                                    <option value='Concluída'" . ($row['tarefa_status'] == 'Concluída' ? ' selected' : '') . ">Concluída</option>
                                </select>
                                <button type='submit' name='update_status' class='btn'>Atualizar</button>
                            </form>
                            <a href='?delete_id=" . $row['tarefa_id'] . "' class='btn delete' onclick='return confirm(\"Tem certeza que deseja deletar esta tarefa?\")'>Deletar</a>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhuma tarefa encontrada</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>


