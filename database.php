<?php
// phpinfo();

$response = array();
// Check the request method
$method = $_SERVER['REQUEST_METHOD'];
$dump = json_decode(file_get_contents('php://input'), true);
// $dump = json_decode("{ \"addName\": \"New color name\", \"addColor\": \"#000000\" }",true);
// echo var_dump($dump);
switch ($method) {
    case 'POST':
        $dump = json_decode(file_get_contents('php://input'), true);
        // Handle POST request (Adding data)
        $keys = array_keys($dump);
        // Check if data already exists (This logic should be implemented based on your storage mechanism)
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
echo json_encode(array("method"=>$method, "data"=>$dump));

?>