<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/consultas_uac_sql.php');
session_status() === PHP_SESSION_NONE ? session_start() : null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do formulário
    $conn = connection();
    function validateField($fieldValue)
    {
        // Verifica se o valor do campo é vazio ou nulo, e retorna null em caso afirmativo, caso contrário, retorna o valor do campo
        return trim($fieldValue) === "" || $fieldValue === null ? null : $fieldValue;
    }

    $action = validateField(isset($_POST['form_type']) ? trim($_POST['form_type']) : null);

    //$destination_unit = validateField(isset($_POST['destination_unit']) ? trim($_POST['destination_unit']) : null);
    $name = validateField(isset($_POST['item_nome']) ? trim($_POST['item_nome']) : null);
    $desc = validateField(isset($_POST['item_descricao']) ? trim($_POST['item_descricao']) : null);
    $price = isset($_POST['item_preco']) ? trim($_POST['item_preco']) : 0;
    $category = validateField(isset($_POST['item_categoria']) ? trim($_POST['item_categoria']) : null);
    $UID = $_SESSION['UID'];

    // function uploadAndInsertFile($inputName, $itemId)
    // {
    //     try {
    //         $finfo = finfo_open(FILEINFO_MIME_TYPE);
    //         // Verifique se o arquivo foi enviado com sucesso
    //         if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
    //             // Verifique o tamanho máximo do arquivo (16MB)
    //             $tamanhoMaximo = 16 * 1024 * 1024; // 16MB em bytes
    //             if ($_FILES[$inputName]['size'] <= $tamanhoMaximo) {
    //                 // Verifique o tipo de arquivo (permitindo PDF, JPG, JPEG e PNG)
    //                 $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'svg'];
    //                 $extensaoArquivo = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
    //                 try{
    //                 $tipoMIME = finfo_file($finfo, $_FILES[$inputName]['tmp_name']);
    //                 }catch(Exception $e){
    //                     deleteCadPersonalData($cadPersonalDataId);
    //                 }


    //                 if (in_array(strtolower($extensaoArquivo), $allowedExtensions)) {
    //                     // Renomeie o arquivo (opcional)
    //                     $nomeArquivo = uniqid() . '.' . $extensaoArquivo;

    //                     // Obtenha o tipo de arquivo
    //                     $tipoArquivo = $_FILES[$inputName]['type'];

    //                     // Obtenha os dados binários do arquivo
    //                     $dadosBinarios = file_get_contents($_FILES[$inputName]['tmp_name']);

    //                     // Inserir arquivo no banco de dados ou fazer o que for necessário
    //                     insertArquivo($cadPersonalDataId, $nomeArquivo, $tipoArquivo, $dadosBinarios);

    //                     // Mova o arquivo para o local desejado (opcional)
    //                     //move_uploaded_file($_FILES[$inputName]['tmp_name'], '/caminho/para/onde/armazenar/' . $nomeArquivo);

    //                     // Upload bem-sucedido
    //                     echo "Arquivo enviado com sucesso!";
    //                 } else {
    //                     echo "Tipo de arquivo não permitido: $extensaoArquivo, MIME: $tipoMIME";
    //                     deleteCadPersonalData($cadPersonalDataId);
    //                 }
    //             } else {
    //                 echo "Tamanho máximo de arquivo excedido (16MB).";
    //                 deleteCadPersonalData($cadPersonalDataId);
    //             }
    //         } else {
    //             echo "Falha no upload do arquivo.";
    //             deleteCadPersonalData($cadPersonalDataId);
    //         }
    //     } catch (Exception $e) {
    //         deleteCadPersonalData($cadPersonalDataId);
    //     }
    //     finfo_close($finfo);
    // }
    if ($action != 'create') {
        $idItem = $_POST['itemId'];
    }
    if ($action == 'create') {
        $itemId = insertItem($name, $desc, $price, $category);
        if (!empty($itemId)) {
            insertToMenu($itemId, $UID);
            return "Item cadastrado com sucesso!\nRedirecionando...";
        } else {
            deleteItem($itemId);
            closeconn();
        }
    } else if ($action == 'edit') {
        $item = selectItemById($UID, $idItem);
        echo json_encode($item);
    } else if ($action == 'delete') {
        deleteItem($idItem);
    } else if ($action == 'saveEdit') {
        updateItem($idItem, $name, $desc, $price, $category);
    }

}

function deleteItem($item_id)
{
    $conn = connection();
    $sql = "DELETE FROM item WHERE item_id = ?";
    $deleteStmt = $conn->prepare($sql);

    if ($deleteStmt) {
        $deleteStmt->bind_param("i", $item_id);
        if ($deleteStmt->execute()) {
            closeconn();
            return true;
        } else {
            closeconn();
            echo "Erro ao excluir registro em item_id: " . $deleteStmt->error;
            return false;
        }
    } else {
        closeconn();
        echo "Erro ao preparar a declaração para exclusão em item_id: " . $conn->error;
        return false;
    }
}


// Função para inserir dados na tabela 'item'
function insertItem($name, $desc, $price, $category)
{
    $conn = connection();
    $categoryTypeQuery = "SELECT category_id FROM category WHERE category_name = ?";
    $categoryTypeStmt = $conn->prepare($categoryTypeQuery);
    $categoryTypeStmt->bind_param("s", $category);
    $categoryTypeStmt->execute();
    $categoryTypeResult = $categoryTypeStmt->get_result();

    if ($categoryTypeResult->num_rows > 0) {

        $row = $categoryTypeResult->fetch_assoc();

        // Obtém o ID da categoria
        $category_id = $row['category_id'];

        $sql = "INSERT INTO item (item_name, item_description, item_price, category_id)
            VALUES (?, ?, ?, ?)";

        $insertStmt = $conn->prepare($sql);

        if ($insertStmt) {
            $insertStmt->bind_param("ssdi", $name, $desc, $price, $category_id);
            if ($insertStmt->execute()) {
                // Retorne o ID gerado para uso posterior
                closeconn();
                return $insertStmt->insert_id;
            } else {
                closeconn();
                echo "Erro ao inserir dados em item: " . $insertStmt->error;
                return false;
            }
        } else {
            closeconn();
            echo "Erro ao preparar a declaração para item: " . $conn->error;
            return false;
        }
    }
}
function updateItem($idItem, $name, $desc, $price, $category)
{
    $conn = connection();

    // Obter o ID da categoria
    $categoryTypeQuery = "SELECT category_id FROM category WHERE category_name = ?";
    $categoryTypeStmt = $conn->prepare($categoryTypeQuery);
    $categoryTypeStmt->bind_param("s", $category);
    $categoryTypeStmt->execute();
    $categoryTypeResult = $categoryTypeStmt->get_result();

    if ($categoryTypeResult->num_rows > 0) {
        $row = $categoryTypeResult->fetch_assoc();
        $category_id = $row['category_id'];

        // Atualizar o item
        $sql = "UPDATE item SET item_name = ?, item_description = ?, item_price = ?, category_id = ? WHERE item_id = ?";
        $updateStmt = $conn->prepare($sql);

        if ($updateStmt) {
            // Definir tipos de dados para bind_param
            $updateStmt->bind_param("ssdii", $name, $desc, $price, $category_id, $idItem);
            
            if ($updateStmt->execute()) {
                closeconn();
                return "Atualizado com sucesso!";
            } else {
                closeconn();
                echo "Erro ao executar a atualização do item: " . $updateStmt->error;
                return false;
            }
        } else {
            closeconn();
            echo "Erro ao preparar a declaração para atualização do item: " . $conn->error;
            return false;
        }
    } else {
        closeconn();
        echo "Categoria não encontrada.";
        return false;
    }
}


function insertToMenu($item_id, $user_id)
{
    $conn = connection();
    $sql = "INSERT INTO menu (item_id, business_id)
            VALUES (?, ?)";

    $insertStmt = $conn->prepare($sql);

    if ($insertStmt) {
        $insertStmt->bind_param("ss", $item_id, $user_id);
        if ($insertStmt->execute()) {
            // Retorne o ID gerado para uso posterior
            closeconn();
            return true;
        } else {
            closeconn();
            echo "Erro ao inserir dados em menu: " . $insertStmt->error;
            return false;
        }
    } else {
        closeconn();
        echo "Erro ao preparar a declaração para menu: " . $conn->error;
        return false;
    }

}

function selectItemById($user_id, $item_id)
{
    try {
        $conn = connection(); // Certifique-se de usar a variável global $conn
        $query = "SELECT i.*, c.category_name 
        FROM item i
        JOIN category c ON i.category_id = c.category_id
        JOIN menu m ON i.item_id = m.item_id
        JOIN user u ON u.user_id = m.business_id
        JOIN business b ON b.user_id = u.user_id
        WHERE u.user_id = ? AND i.item_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $item_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Retorne apenas o primeiro item, já que deveria haver apenas um com o ID especificado
            return $result->fetch_assoc();
        } else {
            return null; // Retorna nulo se nenhum item for encontrado
        }
    } catch (Exception $e) {
        error('coming-soon-img.png', "Erro de SQL", $e->getMessage());
    }
}


function selectAllItems($user_id)
{
    try {
        $conn = connection(); // Certifique-se de usar a variável global $conn
        $query = "SELECT i.*, c.category_name 
        FROM item i
        JOIN category c ON i.category_id = c.category_id
        JOIN menu m ON i.item_id = m.item_id
        JOIN user u ON u.user_id = m.business_id
        JOIN business b ON b.user_id = u.user_id
        WHERE u.user_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return $items;
        } else {
            return array(); // Retorna um array vazio se não houver itens encontrados
        }
    } catch (Exception $e) {
        error('coming-soon-img.png', "Erro de SQL", $e->getMessage());
    }
}