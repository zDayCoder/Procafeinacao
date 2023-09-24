<?php

$conn = null;
function error($attb1, $attb2, $attb3)
{
    $atributos = array(
        'attb1' => $attb1,
        'attb2' => $attb2,
        'attb3' => $attb3,
    );
    $jsonData = json_encode($atributos);
    $expiringTime = time() + 10;
    setcookie('hidden_message', $jsonData, $expiringTime);
    $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $page = $base_url . "/TCC/Procafeinacao/error";
    header("Location: " . $page);
    exit;
}
function connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";

    $dbname = createDatabase(new mysqli($servername, $username, $password), "if0_35034372_procafedb");
    
    try {
        global $conn;
        $conn = new mysqli($servername, $username, $password, $dbname);
    } catch (Exception $e) {
        error('coming-soon-img.png', $conn->connect_errno, $e->getMessage());
    }

    return $conn;
}

function createDatabase($conn, $databaseName)
{
    // Comando SQL para criar o banco de dados se ele não existir
    $createdb = "CREATE DATABASE IF NOT EXISTS $databaseName";

    if ($conn->query($createdb) === TRUE) {
        $conn->select_db($databaseName);
    } else {
        echo "Erro ao criar o banco de dados $databaseName: " . $conn->error;
        return false; // Retorna false em caso de erro
    }

    $existsdb = "SHOW DATABASES LIKE '$databaseName'";
    $result = $conn->query($existsdb);

    if ($result->num_rows > 0) {
        return $databaseName;
    } else {
        echo "Erro o banco de dados $databaseName não existe.";
    }
}

function closeconn()
{
    global $conn; // Indica que você está usando a variável global $conn
    $conn->close();
}

createTables();

function createTables()
{
    createTableEndereco();
    createTableUser();
    createTableCliente();
    createTableEmpresa();
    createTableHorarioFuncionamento();
    createTableCategoria();
    createTableAdicional();
    createTableItem();
    createTableItemAdicional();
    createTableMenu();
    createTableItemPedido();
    createTableDeSempre();
    createTableItemPedidoDeSempre();
    createTableSacola();
    createTableUltimoPedido();
}





function createTableEndereco()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS address (
        address_id INT AUTO_INCREMENT PRIMARY KEY,
        address_zip_code BIGINT(8) NOT NULL,
        address_number VARCHAR(80) NOT NULL,
        address_complement VARCHAR(80) NOT NULL,
        address_street VARCHAR(80) NOT NULL,
        address_district VARCHAR(80) NOT NULL,
        address_city VARCHAR(80) NOT NULL,
        address_state VARCHAR(80) NOT NULL
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'address'");
        closeconn();
        return false; // Erro ao criar tabela
    }
    
}
/*
function createTableUser()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS user (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        user_name VARCHAR(120) NOT NULL,
        user_password VARCHAR(128) NOT NULL,
        user_phone VARCHAR(11) DEFAULT NULL,
        user_cpf BIGINT(11) NOT NULL,
        user_email VARCHAR(120) NOT NULL
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'user'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}*/

function createTableUser()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS user (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        user_name VARCHAR(120) NOT NULL,
        user_password VARCHAR(128) NOT NULL,
        user_phone VARCHAR(11) DEFAULT NULL,
        user_email VARCHAR(120) NOT NULL,
        user_photo MEDIUMBLOB DEFAULT NULL,
        user_type CHAR NOT NULL DEFAULT 'C',
        address_id INT NOT NULL,
        FOREIGN KEY (address_id) REFERENCES address(address_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'user'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableCliente()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS client (
        user_id INT PRIMARY KEY,
        client_cpf BIGINT(11) NOT NULL UNIQUE,
        FOREIGN KEY (user_id) REFERENCES user(user_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'client'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableEmpresa()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS business (
        user_id INT PRIMARY KEY,
        business_cnpj BIGINT(11) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES user(user_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'business'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableHorarioFuncionamento()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS opening_hours (
        opening_hours_id INT AUTO_INCREMENT PRIMARY KEY,
        opening_hours_days BIGINT(11) NOT NULL,
        opening_hours_time_day DATE NOT NULL,
        business_id INT NOT NULL,
        FOREIGN KEY (business_id) REFERENCES business(user_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'opening_hours'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableCategoria()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS category (
        category_id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(80) NOT NULL
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'category'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableAdicional()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS aditional (
        aditional_id INT AUTO_INCREMENT PRIMARY KEY,
        aditional_name VARCHAR(80) NOT NULL,
        aditional_amount INTEGER NOT NULL,
        aditional_price NUMERIC(10,2) NOT NULL
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'aditional'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}
function createTableItem()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS item (
        item_id INT AUTO_INCREMENT PRIMARY KEY,
        item_name VARCHAR(80) NOT NULL,
        item_description VARCHAR(255) NOT NULL,
        item_price NUMERIC(10,2) NOT NULL,
        category_id INT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES category(category_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'item'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableItemAdicional()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS item_aditional (
        item_id INT NOT NULL,
        aditional_id INT NOT NULL,
        PRIMARY KEY (item_id, aditional_id),
        FOREIGN KEY (item_id) REFERENCES item(item_id),
        FOREIGN KEY (aditional_id) REFERENCES aditional(aditional_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'item_aditional'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableMenu()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS menu (
        menu_id INT AUTO_INCREMENT PRIMARY KEY,
        business_id INT NOT NULL,
        FOREIGN KEY (business_id) REFERENCES business(user_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'menu'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableItemPedido()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS ordered_item (
        ordered_item_id INT AUTO_INCREMENT PRIMARY KEY,
        ordered_item_subtotal_price NUMERIC(10,2) NOT NULL,
        ordered_item_observations VARCHAR(255) DEFAULT NULL,
        ordered_item_amount INTEGER DEFAULT 1,
        user_id INT NOT NULL,
        menu_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES user(user_id),
        FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'ordered_item'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableDeSempre()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS of_evermore (
        of_evermore_id INT AUTO_INCREMENT PRIMARY KEY,
        ordered_item_id INT NOT NULL,
        FOREIGN KEY (ordered_item_id) REFERENCES ordered_item(ordered_item_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'of_evermore'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableItemPedidoDeSempre()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS ordered_item_of_evermore (
        ordered_item_of_evermore_id INT AUTO_INCREMENT PRIMARY KEY,
        ordered_item_id INT NOT NULL,
        of_evermore_id INT NOT NULL,
        FOREIGN KEY (ordered_item_id) REFERENCES ordered_item(ordered_item_id),
        FOREIGN KEY (of_evermore_id) REFERENCES of_evermore(of_evermore_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'ordered_item_of_evermore'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableSacola()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS wallet (
        wallet_id INT AUTO_INCREMENT PRIMARY KEY,
        wallet_amount INTEGER NOT NULL,
        wallet_total_price NUMERIC(10,2) NOT NULL,
        ordered_item_id INT NOT NULL,
        FOREIGN KEY (ordered_item_id) REFERENCES ordered_item(ordered_item_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'wallet'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}

function createTableUltimoPedido()
{
    $conn = connection();
    $createTable = "CREATE TABLE IF NOT EXISTS last_order (
        last_order_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        wallet_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES user(user_id),
        FOREIGN KEY (wallet_id) REFERENCES wallet(wallet_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        closeconn();
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'last_order'");
        closeconn();
        return false; // Erro ao criar tabela
    }
}










/*
function selectAllItems()
{

    try {
        $conn = connection(); // Indica que você está usando a variável global $conn
        $query = "SELECT id, product_name, description, price, item_available, category_id FROM item";
        $result = $conn->query($query);

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
}*/