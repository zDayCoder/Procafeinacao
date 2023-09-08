<?php
// Inclui o arquivo de conexão com o banco de dados e cria a conexão
require_once '../database/connect.php';
// Indica que você está usando a variável global $conn

function generateStrongPassword($length)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?@#$%&*()-=+;.,[]';
    $password = '';

    // Shuffle the characters to ensure randomness
    $shuffledChars = str_shuffle($chars);

    // Generate the password
    for ($i = 0; $i < $length; $i++) {
        $password .= $shuffledChars[rand(0, strlen($shuffledChars) - 1)];
    }

    return $password;
}

//Preencher com dados


// Função para buscar um usuário pelo nome
function findUserByCPF($user_cpf)
{
    try {
        $conn = connection(); // Indica que você está usando a variável global $conn
        $stmt = $conn->prepare("SELECT user_id,  user_fullname, user_cpf, user_password FROM users WHERE user_cpf = ? LIMIT 1");
        $stmt->bind_param("s", $user_cpf);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
         
        //error('coming-soon-img.png', "Erro de SQL", $e->getMessage());
    }
}

function findUserByID($user_id)
{
    try {
        $conn = connection(); // Indica que você está usando a variável global $conn
        $stmt = $conn->prepare("SELECT user_id, user_name, acesso, is_blocked, is_head_manager, failed_login_attempts FROM phpgen_users WHERE user_id = ? LIMIT 1");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row;
    } catch (Exception $e) {
        error('coming-soon-img.png', "Erro de SQL", $e->getMessage());
    }
}



function selectAllUsers($order, $by)
{
    $order = ($order === null || empty($order)) ? 'user_id' : $order;
    $by = ($by === 'asc') ? 'desc' : 'asc';
    try {
        $conn = connection(); // Indica que você está usando a variável global $conn
        $query = "SELECT user_id, user_name, acesso, is_head_manager, is_blocked, failed_login_attempts FROM phpgen_users ORDER BY $order $by limit 10";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $users = array();
            while ($row = $result->fetch_assoc()) {
               // if ($row['user_id'] != 14) {
                    $users[] = $row;
                //}
            }
            return $users;
        } else {
            return array(); // Retorna um array vazio se não houver usuários encontrados
        }
    } catch (Exception $e) {
        error('coming-soon-img.png', "Erro de SQL", $e->getMessage());
    }
}



/*
  .oooooo.   ooooooooo.   ooooo     ooo oooooooooo.   
 d8P'  `Y8b  `888   `Y88. `888'     `8' `888'   `Y8b  
888           888   .d88'  888       8   888      888 
888           888ooo88P'   888       8   888      888 
888           888`88b.     888       8   888      888 
`88b    ooo   888  `88b.   `88.    .8'   888     d88' 
 `Y8bood8P'  o888o  o888o    `YbodP'    o888bood8P'

*/

function createUser($user)
{
    $conn = connection();
    $hashed_password = hash("sha512", $user['user_password']); // Precisa criar uma chave aleatória aqui

    if ($user['acesso'] !== "CLIENTE") {
        $is_head_manager = 1;
    } else {
        $is_head_manager = 0;
    }

    $is_blocked = 0;

    $insertStmt = $conn->prepare("INSERT INTO phpgen_users (user_name, acesso, user_password, is_head_manager, is_blocked) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->bind_param("sssii", $user['user_name'], $user['acesso'], $hashed_password, $is_head_manager, $is_blocked);

    // Executar a inserção e verificar se foi bem-sucedida
    if ($insertStmt->execute()) {
        $insertStmt->close();
        closeconn();
        return true;
    } else {
        $insertStmt->close();
        closeconn();
        return false;
    }
}


function updateUser($user)
{
    $conn = connection();
    $faileds = intval($user['failed_login_attempts']);
    $user_blocked = intval($user['is_blocked']);

    if ($faileds === 5 || $user_blocked === 1) {
        $is_blocked = 1;
    } else {
        $is_blocked = 0;
    }

    $updateStmt = $conn->prepare("UPDATE phpgen_users SET user_name = ?, acesso = ?, is_head_manager = ?, is_blocked = ?, failed_login_attempts = ? WHERE user_id = ?");
    $updateStmt->bind_param("ssssis", $user['user_name'], $user['acesso'], $user['is_head_manager'], $is_blocked, $faileds, $user['user_id']);

    // Executar a inserção e verificar se foi bem-sucedida
    if ($updateStmt->execute()) {
        $updateStmt->close();
        closeconn();
        return true;
    } else {
        $updateStmt->close();
        closeconn();
        return false;
    }

}

function desactiveUser($user)
{
    $cond = null;
    if ($user['is_blocked']) {
        $cond = 0;
    } else {
        $cond = 1;
    }

    $conn = connection();
    $updateStmt = $conn->prepare("UPDATE phpgen_users SET is_blocked = ? WHERE user_id = ?");
    $updateStmt->bind_param("ss", $cond, $user['user_id']);
    $updateStmt->execute();
    $updateStmt->close();
}



function deleteUser($user)
{
    $conn = connection();


    $deleteStmt = $conn->prepare("DELETE FROM phpgen_users WHERE user_id = ?");
    $deleteStmt->bind_param("s", $user['user_id']);

    // Executar a inserção e verificar se foi bem-sucedida
    if ($deleteStmt->execute()) {
        $deleteStmt->close();
        closeconn();
        return true;
    } else {
        $deleteStmt->close();
        closeconn();
        return false;
    }

}