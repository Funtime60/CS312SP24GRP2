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


$response = array();
// Check the request method
$method = $_SERVER['REQUEST_METHOD'];
// $dump = json_decode("{ \"addName\": \"New color name\", \"addColor\": \"#000000\" }",true);
// echo var_dump($dump);

function checkRequest(): array
{
    return json_decode(file_get_contents('php://input'), true);
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
    $sql = "INSERT INTO `$table` (`name`, `hex_value`) VALUES (\'$color\', \'$hex\');";
}
function q_retrieveTable(): string
{
    global $table;
    $sql = "SELECT `name`, `hex_value` FROM `$table`;";
}

function q_retrieveColor($color): string
{
    global $table;
    $sql = "SELECT `name` , `hex_value` from `$table` where `name` = \'$color\';";
    return $sql;
}

function q_deleteColor($color): string
{
    global $table;
    $sql = "SET @rowCount = (SELECT COUNT(*) FROM `$table`);
            IF @rowCount > 2 THEN
                DELETE FROM `$table` WHERE `name` = \'$color\';
            ELSE
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = \'Error: Cannot delete remaining colors! Must have atleast two.\';
            END IF;";
    return $sql;
}


class SQL
{
    private $conn;
    public function __construct($server, $user, $pass, $database)
    {
        $this->conn = new mysqli($server, $user, $pass, $database);
        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
            return false;
        }
        return true;
    }

    public function checkError($result)
    {
        if ($result === false) {
            die("Unable to execute query: " . $this->conn->connect_error);
        }
    }
}

switch ($method) {
    case 'POST':
        $dump = checkRequest();         // Handle POST request (Adding data)

        $keys = array_keys($dump);

        $dataExists = false; // Replace this with your actual logic to check if data exists

        if ($dataExists) {
            // Data already exists, send error response
            $response['status'] = 'error';
            $response['message'] = 'Data already exists!';
        } else {
            // Data doesn't exist, add it (This should be replaced with your actual logic to add data)
            // For demonstration, let's just store it in an array
            $newData = array(
                'name' => $addName,
                'color' => $addColor
            );
            // Your code to add data to your storage mechanism goes here

            // Send success response
            $response['status'] = 'success';
            $response['message'] = 'Data added successfully';
            $response['data'] = $newData;
        }
        break;

    case 'GET':
        // Handle GET request (Retrieve data)
        // Your code to retrieve data (This should be replaced with your actual logic to retrieve data)
        // For demonstration, let's just return dummy data
        $dummyData = array(
            array('name' => 'Red', 'color' => '#FF0000'),
            array('name' => 'Green', 'color' => '#00FF00'),
            array('name' => 'Blue', 'color' => '#0000FF')
        );

        // Send success response with retrieved data
        $response['status'] = 'success';
        $response['data'] = $dummyData;
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
// echo json_encode($response);
echo json_encode(array("method" => $method, "data" => $dump));
