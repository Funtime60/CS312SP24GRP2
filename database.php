<?php
// phpinfo();
include ("db_cred.php");
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $servername = "localhost";
}
$table = "colors";

// https://www.php.net/manual/en/mysqlinfo.api.choosing.php

// I think it may be best to use prepared style statements
// Both apis seem very similar. Ill go with mysqli.

// Pull json from php's input buffer
function decodeJSON() {
    // return $_GET;
    return json_decode(file_get_contents('php://input'), true);
    // return json_decode(var_dump($_POST));
}
function q_buildTable() {
    global $table;
    $sql = "CREATE TABLE IF NOT EXISTS `$table`("
        . "    id INT AUTO_INCREMENT PRIMARY KEY,"
        . "    name VARCHAR(42) NOT NULL UNIQUE,"
        . "    color VARCHAR(7) NOT NULL UNIQUE"
        . ");";
    return $sql;
}
function q_insertDefaultColors() {
    $colors = array(
        array('name' => 'Black', 'color' => '#000000'),
        array('name' => 'Red', 'color' => '#FF0000'),
        array('name' => 'Orange', 'color' => '#FFA500'),
        array('name' => 'Yellow', 'color' => '#FFFF00'),
        array('name' => 'Green', 'color' => '#00FF00'),
        array('name' => 'Blue', 'color' => '#0000FF'),
        array('name' => 'Purple', 'color' => '#800080'),
        array('name' => 'Grey', 'color' => '#808080'),
        array('name' => 'Brown', 'color' => '#964B00'),
        array('name' => 'Teal', 'color' => '#008080')
    );

    $queries = array();

    // Iterate over each color
    foreach ($colors as $color) {
        // Call q_insertValues() to generate SQL INSERT statement
        $query = q_insertValues($color['name'], $color['color']);

        // Add the generated query to the array
        $queries[] = $query;
    }

    // Return the array of SQL queries
    return $queries;
}

function q_insertValues($color, $hex) {
    global $table;
    return "INSERT INTO `$table` (`name`, `color`) VALUES ('$color', '$hex');";
}
function q_retrieveTable(): string {
    global $table;
    return "SELECT `name`, `color` FROM `$table`;";
}

function q_retrieveColor($color, $hex): string {
    global $table;
    if (empty($color) && !empty($hex)) {
        return "SELECT `name` , `color` from `$table` where `color` = '$hex';";
    } else if (!empty($color) && empty($hex)) {
        return "SELECT `name` , `color` from `$table` where `name` = '$color';";
    }
    // return "SELECT `name` , `color` from `$table` where `name` = '$color';";
}

function q_retrieveHex($color): string {
    global $table;
    return "SELECT `color` from `$table` where `name` = '$color';";
}
function q_modifyColor($color, $hex): string {
    global $table;
    return "UPDATE `colors` SET `color` = '$hex' WHERE `name` = '$color';";
}

function q_deleteColor($color): string {
    global $table;
    $sql = "DELETE FROM `$table` WHERE `name` = '$color';";
    return $sql;
}

function q_countRows() {
    global $table;
    $sql = "(SELECT COUNT(*) AS row_count FROM `$table`);";
    return $sql;
}

class SQL {
    public $conn;
    public function __construct($server, $user, $pass, $database) {
        $this->conn = new mysqli($server, $user, $pass, $database);
        if ($this->conn->connect_errno) {
            terminate("Database Connection Failed: " . $this->conn->connect_error);
        }
    }
    public function &getRef() {
        return $this->conn;
    }
    public function checkTableExistence($table) {
        $sql = "SHOW TABLES LIKE '{$this->conn->real_escape_string($table)}'";
        $result = $this->conn->query("SHOW TABLES LIKE '{$table}'");
        if ($result->num_rows > 0) {
            $exists = true;
        } else {
            $exists = false;
        }
        $result->free();
        return $exists;
    }
}
// encode array as json string
function buildJSON(array $arr): string {
    return json_encode($arr);
}
//Create array for json response
function buildError(string $error): array {
    return array(
        "status" => "error",
        "message" => $error
    );
}

// Something unexpected happened. Send message and quit early. 
function terminate(string $msg) {
    echo buildJSON(buildError($msg));
    exit();
}

function maincode(): array {
    global $mysqli, $sqlClass, $table;
    $dump = decodeJSON();
    // $dump = "{\"edtName\":\"black\",\"edtColor\":\"#fffaf0\", \"type\":\"edit\"}";
    // $dump = json_decode($dump, true);
    // $dump['type'] = 'getColor';
    // $dump['name'] = 'red';
    // $dump['color'] = '#ffaadd';
    $response = array();
    try {
        switch ($dump['type']) {
            case 'add':
                $name = $dump['addName'];
                $color = $dump['addColor'];
                $result = $mysqli->query(q_insertValues($name, $color));
                if(!$result){
                    throw new Exception("Duplicate value!");
                }
                $response['message'] = 'Data added successfully';
                // echo q_insertValues( $name, $color );
                break;
            case 'edit':
                $name = $dump['edtName'];
                $color = $dump['edtColor'];
                $result = $mysqli->query(q_modifyColor($name, $color));
                $response['message'] = 'Update Successful';
                break;
            case 'delete':
                $name = $dump['delName'];
                // $color = $dump['delColor'];
                $result = $mysqli->query(q_countRows());
                $row = $result->fetch_assoc();
                if ($row['row_count'] > 2) {
                    $result = $mysqli->query(q_deleteColor($name));
                    if (!$result) {
                        throw new Exception("Error deleting row: " . $mysqli->error);
                    } else {
                        $response['message'] = 'Color removed successfully';
                    }
                } else {
                    throw new Exception("Cannot delete anymore values. Must have atleast 2!");
                }

                break;
            case 'getTable':
                if (!$sqlClass->checkTableExistence($table)) {
                    $mysqli->query(q_buildTable());
                    foreach (q_insertDefaultColors() as $q) {
                        if ($mysqli->query($q)) {
                            $response['message'] .= "Error inserting value: $mysqli->error \n";
                        }
                    }
                }
                $result = $mysqli->query(q_retrieveTable());
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
                $response['message'] = 'Table retrieved';
                $response['data'] = $rows; // = json_encode($rows);
                break;
            case 'getColor':
                $name = "";
                $color = "";
                if (isset($dump['name'])) {
                    $name = $dump['name'];
                } else if (isset($dump['color'])) {
                    $color = $dump['color'];
                }
                $result = $mysqli->query(q_retrieveColor($name, $color));
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
                $response['message'] = 'Color retrieved';
                $response['data'] = $rows; // = json_encode($rows);
                break;
            default:
                throw new Exception("Invalid type!");
        }

        $response['status'] = 'success';
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
            http_response_code(405);
            break;
    }
    echo json_encode($response);
} catch (Exception $e) {
    echo buildJSON(buildError($e->getMessage()));
}
// Output JSON response
// echo json_encode(array("method" => $method, "data" => var_dump($dump)));
exit();
