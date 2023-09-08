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
    header("Location: error.php");
    exit;
}
function connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";

    //$dbname = "procafedb";
    $dbname = createDatabase(new mysqli($servername, $username, $password), "procafedb");


    try {
        global $conn; // Indica que você está usando a variável global $conn
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
        createTables($conn);
    } else {
        echo "Erro ao criar o banco de dados $databaseName: " . $conn->error;
        return false; // Retorna false em caso de erro
    }

    $existsdb = "SHOW DATABASES LIKE '$databaseName'";
    $result = $conn->query($existsdb);

    if ($result->num_rows > 0) {
        return $databaseName;
    }else{
        echo "Erro o banco de dados $databaseName não existe.";
    }
}


function closeconn()
{
    global $conn; // Indica que você está usando a variável global $conn
    $conn->close();
}


function createTables($conn)
{
    createTableUsers($conn);
    createTableAddress($conn);
    createTableCategory($conn);
    createTableItens($conn);
}

// Create Table if not exists users // Criar tabela caso não exista
function createTableUsers($conn)
{

    $createTable = "CREATE TABLE IF NOT EXISTS users (
            user_id int(11) AUTO_INCREMENT PRIMARY KEY,
            user_fullname varchar(500) NOT NULL,
            user_cpf varchar(11) NOT NULL UNIQUE,
            user_password varchar(128) NOT NULL
        )";

    if ($conn->query($createTable) === TRUE) {
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'users'");
        return false; // Erro ao criar tabela
    }
}

function createTableAddress($conn)
{
    $createTable = "CREATE TABLE IF NOT EXISTS address (
        address_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        street VARCHAR(255),
        city VARCHAR(100),
        state VARCHAR(50),
        zip_code VARCHAR(8),
        residential_number VARCHAR(20),
        complement VARCHAR(80),
        FOREIGN KEY (user_id) REFERENCES users (user_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'address'");
        return false; // Erro ao criar tabela
    }
}

function createTableCategory($conn)
{
    $createTable = "CREATE TABLE IF NOT EXISTS category (
        category_id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(255) NOT NULL
    )";

    if ($conn->query($createTable) === TRUE) {
        return true;
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'category'");
        return false; // Erro ao criar tabela
    }
}

function createTableItens($conn)
{
    $createTable = "CREATE TABLE IF NOT EXISTS item (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        item_available BOOLEAN NOT NULL DEFAULT 1,
        category_id INT,
        FOREIGN KEY (category_id) REFERENCES category(category_id)
    )";

    if ($conn->query($createTable) === TRUE) {
        return true; // Tabela 'users' criada com sucesso
    } else {
        error('coming-soon-img.png', $conn->error, "Erro ao criar tabela 'item'");
        return false; // Erro ao criar tabela
    }
}




function createCategory($categoryName)
{
    $conn = connection();

    $insertStmt = $conn->prepare("INSERT INTO category (category_name) VALUES (?)");
    $insertStmt->bind_param("s", $categoryName);

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
function createItem($item)
{
    $conn = connection();

    $insertStmt = $conn->prepare("INSERT INTO item (product_name, description, price, item_available, category_id) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->bind_param("ssdii", $item['product_name'], $item['description'], $item['price'], $item['item_available'], $item['category_id']);

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
}