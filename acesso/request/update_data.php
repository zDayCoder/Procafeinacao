<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/consultas_uac_sql.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do formulário
    $conn = connection();
    function validateField($fieldValue)
    {
        // Verifica se o valor do campo é vazio ou nulo, e retorna null em caso afirmativo, caso contrário, retorna o valor do campo
        return trim($fieldValue) === "" || $fieldValue === null ? null : $fieldValue;
    }

    $user_id = $_SESSION['UID'];
    if ($user_id !== null || !empty($user_id)) {
        //$destination_unit = validateField(isset($_POST['destination_unit']) ? trim($_POST['destination_unit']) : null);
        $updateUser_adds_cep = validateField(isset($_POST['user_adds_cep']) ? trim($_POST['user_adds_cep']) : null);
        $updateUser_adds_complemento = validateField(isset($_POST['user_adds_complemento']) ? trim($_POST['user_adds_complemento']) : null);
        $updateUser_adds_number = validateField(isset($_POST['user_adds_number']) ? trim($_POST['user_adds_number']) : null);
        $updateUser_adds_logradouro = validateField(isset($_POST['user_adds_logradouro']) ? trim($_POST['user_adds_logradouro']) : null);
        $updateUser_adds_bairro = validateField(isset($_POST['user_adds_bairro']) ? trim($_POST['user_adds_bairro']) : null);
        $updateUser_adds_localidade = validateField(isset($_POST['user_adds_localidade']) ? trim($_POST['user_adds_localidade']) : null);
        $updateUser_adds_estado = validateField(isset($_POST['user_adds_estado']) ? trim($_POST['user_adds_estado']) : null);


        $addressId = insertAddress($user_id, $updateUser_adds_cep, $updateUser_adds_complemento, $updateUser_adds_number, $updateUser_adds_logradouro, $updateUser_adds_bairro, $updateUser_adds_localidade, $updateUser_adds_estado);
        if ($addressId) {
            updateAddressUser($user_id, $addressId);
        }
    }

   
}

function updateAddressUser($user_id, $addressId)
{

    $conn = connection();

    $sql = "UPDATE user SET address_id = ? WHERE user_id = ?";

    $insertStmt = $conn->prepare($sql);

    if ($insertStmt) {
        $insertStmt->bind_param("ii", $addressId, $user_id);

        if ($insertStmt->execute()) {
            closeconn();
            echo "Endereço adicionado.\nRedirecionando...";
        } else {
            closeconn();
            echo "Erro ao inserir dados em user: " . $insertStmt->error;
            return false;
        }

    } else {
        closeconn();
        echo "Erro ao preparar a declaração para user: " . $conn->error;
        return false;
    }

}

function insertAddress($user_id, $updateUser_adds_cep, $updateUser_adds_complemento, $updateUser_adds_number, $updateUser_adds_logradouro, $updateUser_adds_bairro, $updateUser_adds_localidade, $updateUser_adds_estado)
{

    $conn = connection();
    // Verifique se o CPF já existe na tabela
    $addsExistQuery = "SELECT address_id FROM user WHERE address_id IS NOT NULL AND user_id = ?";
    $addsExistStmt = $conn->prepare($addsExistQuery);
    $addsExistStmt->bind_param("i", $user_id);
    $addsExistStmt->execute();
    $addsExistResult = $addsExistStmt->get_result();

    if ($addsExistResult->num_rows > 0) {
        // O CPF já existe, você pode lidar com isso de acordo com suas necessidades, por exemplo, lançando um erro ou retornando uma mensagem
        echo "Esse usuário já possuí um endereço!";
        closeconn();
        return false;
    } else {

        $sql = "INSERT INTO address(address_zip_code, address_number, address_complement, address_street, address_district, address_city, address_state)
    VALUES (?, ?, ?, ?, ?, ?, ?)";

        $insertStmt = $conn->prepare($sql);

        if ($insertStmt) {
            $insertStmt->bind_param("sssssss", $updateUser_adds_cep, $updateUser_adds_complemento, $updateUser_adds_number, $updateUser_adds_logradouro, $updateUser_adds_bairro, $updateUser_adds_localidade, $updateUser_adds_estado);
            if ($insertStmt->execute()) {
                // Retorne o ID gerado para uso posterior
                closeconn();
                return $insertStmt->insert_id;
            } else {
                closeconn();
                echo "Erro ao inserir dados em address: " . $insertStmt->error;
                return false;
            }
        } else {
            closeconn();
            echo "Erro ao preparar a declaração para address: " . $conn->error;
            return false;
        }

    }
}
//closeconn();

?>