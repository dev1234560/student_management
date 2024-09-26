<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_GET['name'];
$response = array();

if (!empty($name)) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['success'] = true;
        $response['name'] = $row['name'];
        $response['class'] = $row['class'];
        $response['school'] = $row['school'];
        $response['contact'] = $row['contact'];
        $response['subjects'] = $row['subjects'];
        $response['coachingDate'] = $row['coachingDate'];
    } else {
        $response['success'] = false;
    }
    
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
