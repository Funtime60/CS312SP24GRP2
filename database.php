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
    return json_decode(file_get_contents('php://input'));
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

function q_retrieveColor($color): string
{
    global $table;
    return "SELECT `name` , `hex_value` from `$table` where `name` = '$color';";
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
    public function &getRef(){
        return $this->conn;
    }

    public function isError($result)
    {
        if ($result === false) {
            return ("Unable to execute query: " . $this->conn->connect_error);
        }
        return false;
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
    return array("Error" => $error);
}

// Something unexpected happened. Send message and quit early. 
function terminate(string $msg)
{
    echo buildJSON(buildError($msg));
    exit();
}

$response = array();
// Check the request method
$method = $_SERVER['REQUEST_METHOD'];
// $dump = json_decode("{ \"addName\": \"New color name\", \"addColor\": \"#000000\" }",true);
// echo var_dump($dump);
// 

$sqlClass = new SQL($servername, $username, $password, $database);
$mysqli =& $sqlClass->getRef();
// $mysqli->query("");
//Connection created
function maincode()
{
    global $mysqli;
    $dump = decodeJSON();
    $dump = "{\"addName\":\"black\",\"addColor\":\"#000000\", \"type\":\"submit\"}";
    $dump = json_decode($dump, true);
    // $keys = array_keys($dump);
    // echo var_dump($keys);
    echo var_dump($dump);
    /** @var string $name */
    $name = $dump['addName'];
    /** @var string $color */
    $color = $dump['addColor'] ;
    try{
    switch ($dump['type']) {
        case 'submit':
            $mysqli->query(q_insertValues( $name, $color ));
            // echo q_insertValues( $name, $color );
            break;
        case 'edit':
            break;
        case 'delete':
            break;
        default:
            break;
    }
}catch(Exception $e){
    echo buildJSON(buildError($e->getMessage()));
}
    $dataExists = false; // Replace this with your actual logic to check if data exists

    if ($dataExists) {
        // Data already exists, send error response
        $response['status'] = 'error';
        $response['message'] = 'Data already exists!';
    } else {
        // Data doesn't exist, add it (This should be replaced with your actual logic to add data)
        // For demonstration, let's just store it in an array
        $newData = array(
            'name' => "mockname",
            'color' => "#000000"
        );
        // Your code to add data to your storage mechanism goes here

        // Send success response
        $response['status'] = 'success';
        $response['message'] = 'Data added successfully';
        $response['data'] = ($dump);
        $response['jsonerror'] = json_last_error();
    }
}
switch ($method) {
    case 'POST':
        mainCode();
        break;

    case 'GET':
        // Expected json looks like the $dump string
        // Json_decode will convert to array
        // This array can be accessed using the expected keys
        // $dump = checkRequest();
        // $dump = "{\"addName\":\"black\",\"addColor\":\"#000000\"}";
        // $dump = ""
        // $dump = json_decode($dump);
        // echo var_dump($dump);
        // $mysqli->  

        // $dummyData = array(
        //     array('name' => 'Red', 'color' => '#FF0000'),
        //     array('name' => 'Green', 'color' => '#00FF00'),
        //     array('name' => 'Blue', 'color' => '#0000FF')
        // );

        // // Send success response with retrieved data
        // $response['status'] = 'success';
        // $response['data'] = $dummyData;
        mainCode();
        break;

    default:
        // Invalid request method
        $response['status'] = 'error';
        $response['message'] = 'Invalid request method';
        break;
}

// Set response headers
header('Content-Type: application/json');

// Output JSON response
echo json_encode($response);
// echo json_encode(array("method" => $method, "data" => var_dump($dump)));
