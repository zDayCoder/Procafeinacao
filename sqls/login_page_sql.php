<?php
// Inclui o arquivo de conexão com o banco de dados e cria a conexão
require_once '../database/connect.php';
require_once 'consultas_user_sql.php';

function login($user, $page)
{
    $find = findUserByCPF($user['user_cpf']);
    if ($find) {
        if ($user['user_password'] === $find['user_password']) {
            makeSession($find);
            gotoPage($page);
        }else{
            $message = "Senha inválida.";
            
            //gotoPage("senha errada");
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
    }else{
        $message = "CPF nao registrado";
    }
    return $message;
}

function Register($user, $page)
{
    $conn = connection();
    $hashed_password = hash("sha512", $user['user_password']); // Precisa criar uma chave aleatória aqui

    // Verificar se o CPF já está cadastrado
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_cpf = ?");
    $checkStmt->bind_param("s", $user['user_cpf']);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // CPF já cadastrado, lançar uma exceção
        closeconn();
        //throw new Exception();
        return "CPF já cadastrado. Não é possível criar um novo cadastro com o mesmo CPF.";
    } else {
        // CPF não cadastrado, continue com a inserção
        $insertStmt = $conn->prepare("INSERT INTO users (user_fullname, user_cpf, user_password) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE user_cpf = user_cpf");
        $insertStmt->bind_param("sss", $user['user_fullname'], $user['user_cpf'], $hashed_password);

        // Executar a inserção e verificar se foi bem-sucedida
        if ($insertStmt->execute()) {
            $insertStmt->close();
            closeconn();
            return true;
        } else {
            $insertStmt->close();
            closeconn();
            throw new Exception("Erro ao criar o cadastro.");
        }
    }
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
    $_SESSION['Ufname'] = $user['user_fullname'];
    $_SESSION['Ucpf'] = $user['user_cpf'];
    $_SESSION['Upassword'] = $user['user_password'];
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