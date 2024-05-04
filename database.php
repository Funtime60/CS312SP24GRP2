<?php
// phpinfo();
$servername = "localhost";
$username = "php_user";
$password = "supersecret";
$database = "milestone";
$table = "colors";

// https://www.php.net/manual/en/mysqlinfo.api.choosing.php

// I think it may be best to use prepared style statements
// Both apis seem very similar. Ill go with mysqli.


// Pull json from php's input buffer
function decodeJSON(): mixed
{
    // return $_GET;
    return json_decode(file_get_contents('php://input'), true);
    // return json_decode(var_dump($_POST));
}
function buildTable()
{
    global $table;
    $sql = "CREATE TABLE IF NOT EXISTS `$table`("
        . "    id INT AUTO_INCREMENT PRIMARY KEY,"
        . "    name VARCHAR(255) NOT NULL UNIQUE,"
        . "    hex_value VARCHAR(7) NOT NULL UNIQUE"
        . ");";
}
function q_insertValues($color, $hex)
{
    global $table;
    return "INSERT INTO `$table` (`name`, `hex_value`) VALUES ('$color', '$hex');";
}
function q_retrieveTable(): string
{
    global $table;
    return "SELECT `name`, `hex_value` FROM `$table`;";
}

function q_retrieveColor($color, $hex): string
{
    global $table;
    if (empty($color) && !empty($hex)) {
        return "SELECT `name` , `hex_value` from `$table` where `hex_value` = '$hex';";
    }
    else if (!empty($color) && empty($hex)) {
        return "SELECT `name` , `hex_value` from `$table` where `name` = '$color';";
    }
    // return "SELECT `name` , `hex_value` from `$table` where `name` = '$color';";
}

function q_retrieveHex($color): string
{
    global $table;
    return "SELECT `hex_value` from `$table` where `name` = '$color';";
}
function q_modifyColor($color, $hex): string
{
    global $table;
    return "UPDATE `colors` SET `hex_value` = '$hex' WHERE `name` = '$color';";
}

function q_deleteColor($color): string
{
    global $table;
    $sql = "SET @rowCount = (SELECT COUNT(*) FROM `$table`);
            IF @rowCount > 2 THEN
                DELETE FROM `$table` WHERE `name` = '$color';
            ELSE
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Cannot delete remaining colors! Must have atleast two.';
            END IF;";
    return $sql;
}


class SQL
{
    public $conn;
    public function __construct($server, $user, $pass, $database)
    {
        $this->conn = new mysqli($server, $user, $pass, $database);
        if ($this->conn->connect_errno) {
            terminate("Database Connection Failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
    public function &getRef()
    {
        return $this->conn;
    }
}
// encode array as json string
function buildJSON(array $arr): string
{
    return json_encode($arr);
}
//Create array for json response
function buildError(string $error): array
{
    return array(
        "status" => "error",
        "message" => $error
    );
}

// Something unexpected happened. Send message and quit early. 
function terminate(string $msg)
{
    echo buildJSON(buildError($msg));
    exit();
}

function maincode(): array
{
    global $mysqli;
    // $dump = decodeJSON();
    // $dump = "{\"edtName\":\"black\",\"edtColor\":\"#fffaf0\", \"type\":\"edit\"}";
    // $dump = json_decode($dump, true);
    $dump['type'] = 'getColor';
    // $dump['name'] = 'red';
    $dump['color'] = '#ffaadd';
    try {
        switch ($dump['type']) {
            case 'submit':
                $name = $dump['addName'];
                $color = $dump['addColor'];
                $result = $mysqli->query(q_insertValues($name, $color));
                // echo q_insertValues( $name, $color );
                break;
            case 'edit':
                $name = $dump['edtName'];
                $color = $dump['edtColor'];
                $result = $mysqli->query(q_modifyColor($name, $color));
                break;
            case 'delete':
                $name = $dump['delName'];
                $color = $dump['delColor'];
                $result = $mysqli->query(q_deleteColor($name, $color));
                break;
            case 'getTable':
                $result = $mysqli->query(q_retrieveTable());
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
                $response['data'] = $rows; // = json_encode($rows);
                break;
            case 'getColor':
                $name = "";
                $color = "";
                if(isset($dump['name'])){
                    $name = $dump['name'];                    
                }
                else if(isset($dump['color'])){
                    $color = $dump['color'];
                }
                $result = $mysqli->query(q_retrieveColor($name, $color));
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
                $response['data'] = $rows; // = json_encode($rows);
                break;
            default:
                throw new Exception("Invalid request!");
        }

        $response['status'] = 'success';
        $response['message'] = 'Data added successfully';
        $response['jsonerror'] = json_last_error();
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
    return $response;
}

try {

    $response = array();
    // Check the request method
    $method = $_SERVER['REQUEST_METHOD'];
    $method = 'POST';
    header('Content-Type: application/json');

    $sqlClass = new SQL($servername, $username, $password, $database);
    $mysqli = &$sqlClass->getRef();
    //Connection created

    switch ($method) {
        case 'POST':
            $response = mainCode();
            break;

        case 'GET':
        default:
            // Invalid request method
            $response['status'] = 'error';
            $response['message'] = 'Invalid request method';
            break;
    }
    echo json_encode($response);
} catch (Exception $e) {
    echo buildJSON(buildError($e->getMessage()));
}
// Output JSON response
// echo json_encode(array("method" => $method, "data" => var_dump($dump)));
