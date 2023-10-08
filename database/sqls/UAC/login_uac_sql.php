<?php
// Inclui o arquivo de conexão com o banco de dados e cria a conexão
$message = null;
require_once 'consultas_uac_sql.php';

function login($user, $page)
{
    $find = $user['user_type'] === "C" ? findUserByCPF($user['client_cpf']) : findUserByCNPJ($user['business_cnpj']);
    if ($find) {
        if ($user['user_password'] === $find['user_password']) {
            makeSession($find);

            gotoPage($page);
            exit;
        } else {
            $message = "Senha inválida.";
            //gotoPage("senha errada");
            //exit;
        }
        /* if (!isBlocked($find)) {
             updateFailedLoginAttemptsStatus($find, 0);
             makeSession($find);
             if ($find['is_head_manager'] >= 1) {
                 gotoPage($page);
             } else {
                 gotoPage("paginacomum");

             }
         } else {
             return isBlockedMessage($find);
         }
     } else {
         $message = null;
         if (failedLoginAttempt($find)) {
             $message = failedLoginAttemptMessage($find); // Retorna a mensagem de tentativas restantes ou bloqueio
         }
         if (isBlocked($find)) {
             $message = isBlockedMessage($find);
         }
         return $message;
     }*/
    } else {
        $message = "Não foi possível encontrar uma conta associada a essas credenciais.\nVerifique se você digitou corretamente ou registre-se para criar uma nova conta.";
    }
    return $message;
}


function Register($user, $page)
{
    $conn = connection();
    $cpfcnpj = ($user['user_type'] === 'C') ? $user['client_cpf'] : $user['business_cnpj'];
    if ($user['user_type'] === 'C') {
        $sql = "SELECT COUNT(*) FROM user u JOIN client c ON c.user_id = u.user_id WHERE c.client_cpf = ?;";
    } else if ($user['user_type'] === 'B') {
        $sql = "SELECT COUNT(*) FROM user u JOIN business b ON b.user_id = u.user_id WHERE b.business_cnpj = ?;";
    }

    $checkStmt = $conn->prepare($sql);
    $checkStmt->bind_param("s", $cpfcnpj);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        closeconn();
        $message =  "Desculpe, mas essas informações já estão registras em nosso sistema.\nPor favor, verifique seus dados ou faça login na sua conta.";
    } else {
        // Inserir o usuário na tabela 'user'
        $insertUserStmt = $conn->prepare("INSERT INTO user (user_name, user_password, user_type, user_email) VALUES (?, ?, ?, ?)");
        $insertUserStmt->bind_param("ssss", $user['user_name'],  $user['user_password'], $user['user_type'], $user['user_email']);
        
        // $insertUserStmt = $conn->prepare("INSERT INTO user (user_name, user_password, user_type, user_email, address_id) VALUES (?, ?, ?, ?, ?)");
        // $insertUserStmt->bind_param("ssssi", $user['user_name'],  $user['user_password'], $user['user_type'], $user['user_email'], $address_id);
        
    if ($insertUserStmt->execute()) {
        // Obter o ID do usuário recém-inserido
        $user_id = $conn->insert_id;
        $insertUserStmt->close();
        // Inserir dados na tabela 'client' ou 'business' com base no tipo de usuário
        if ($user['user_type'] === 'C') {
            $insertClientStmt = $conn->prepare("INSERT INTO client (user_id, client_cpf) VALUES (?, ?)");
            $insertClientStmt->bind_param("is", $user_id, $user['client_cpf']);
            $insertClientStmt->execute();
            $insertClientStmt->close();
        } elseif ($user['user_type'] === 'B') {
            $insertBusinessStmt = $conn->prepare("INSERT INTO business (user_id, business_cnpj) VALUES (?, ?)");
            $insertBusinessStmt->bind_param("is", $user_id, $user['business_cnpj']);
            $insertBusinessStmt->execute();
            $insertBusinessStmt->close();
        } else {
            $message = "Tipo de usuário inválido.";
        }

        closeconn();
        gotoPage("/TCC/Procafeinacao/acesso/login");
        return true;
    } else {
        $insertUserStmt->close();
        closeconn();
        throw new Exception("Erro ao criar o cadastro.");
    }
    }
    return $message;
}


function isBlocked($user)
{
    $blocked = $user['is_blocked'];

    return $blocked !== 0; // Retorna true se bloqueado, false se não bloqueado
}

function isBlockedMessage($user)
{
    if (isBlocked($user)) {
        return "Parece que seu usuário está bloqueado.\nContate o administrador do sistema.";
    }
    return;
}

function failedLoginAttempt($user)
{
    $faileds = $user['failed_login_attempts'];

    if (!isBlocked($user)) {
        if ($faileds >= 0 && $faileds < 5) {
            $tentativas = $faileds;

            if ($tentativas > 3 && !isBlocked($user)) {
                updateBlockedStatus($user);
            }
            $tentativas <= 4 ? $tentativas++ : $tentativas;
            updateFailedLoginAttemptsStatus($user, $tentativas);

            return true; // Login falhou
        } else if ($faileds >= 5 && !isBlocked($user)) {
            updateBlockedStatus($user);
            return true; // Login falhou
        }
        return false;
    }
    return false;
}


function failedLoginAttemptMessage($user)
{
    $faileds = $user['failed_login_attempts'];

    if ($faileds >= 0 && $faileds < 4) {
        $tentativas = $faileds;
        $tentativas < 4 ? $tentativas++ : $tentativas;
        $restantes = 4 - $tentativas;
        return "Tentativas restantes: $restantes";
    } elseif ($faileds >= 5 && !isBlocked($user)) {
        updateBlockedStatus($user);
        return "Sua conta foi bloqueada devido a múltiplas tentativas de login incorretas.\nPor favor, contate o administrador do sistema.";
    }

    return;
}

function makeSession($user)
{
    $_SESSION['UID'] = $user['user_id'];
    $_SESSION['Uname'] = $user['user_name'];
    $_SESSION['Ucpfcnpj'] = $user['user_type'] === "C" ? $user['client_cpf'] : $user['business_cnpj'];
    $_SESSION['Upassword'] = $user['user_password'];
    // gotoPage( $_SESSION['Uname'] );
}
function gotoPage($page)
{
    header("Location: " . $page);
    exit;
}

// Função para atualizar o status de bloqueio do usuário
function updateBlockedStatus($user)
{
    $conn = connection();

    $updateStmt = $conn->prepare("UPDATE phpgen_users SET is_blocked = 1 WHERE user_id = ?");
    $updateStmt->bind_param("i", $user['user_id']);

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

// Função para atualizar o número de tentativas de login incorretas
function updateFailedLoginAttemptsStatus($user, $tentativas)
{
    $conn = connection();
    $updateStmt = $conn->prepare("UPDATE phpgen_users SET failed_login_attempts = ? WHERE user_id = ?");
    $updateStmt->bind_param("ii", $tentativas, $user['user_id']);

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