<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter inputs
$classFilter = isset($_GET['classFilter']) ? $_GET['classFilter'] : '';
$schoolFilter = isset($_GET['schoolFilter']) ? $_GET['schoolFilter'] : '';
$dateFilter = isset($_GET['dateFilter']) ? $_GET['dateFilter'] : '';
$subjectFilter = isset($_GET['subjectFilter']) ? $_GET['subjectFilter'] : '';

// Base SQL query
$sql = "SELECT * FROM students WHERE 1=1";

// Append filters if they are provided
if (!empty($classFilter)) {
    $sql .= " AND class = " . intval($classFilter);
}

if (!empty($schoolFilter)) {
    $sql .= " AND school LIKE '%" . $conn->real_escape_string($schoolFilter) . "%'";
}
if (!empty($dateFilter)) {
    $sql .= " AND coachingDate = '" . $conn->real_escape_string($dateFilter) . "'";
}
if (!empty($subjectFilter)) {
    $sql .= " AND subjects LIKE '%" . $conn->real_escape_string($subjectFilter) . "%'";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output filtered student rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['class']) . "</td>
                <td>" . htmlspecialchars($row['school']) . "</td>
                <td>" . htmlspecialchars($row['contact']) . "</td>
                <td>" . htmlspecialchars($row['subjects']) . "</td>
                <td>" . htmlspecialchars($row['coachingDate']) . "</td>
                <td><button class='delete-btn' onclick='deleteStudent(" . $row['id'] . ")'>Delete</button> </td>
              </tr>";
    }
} else {
    echo "";  // Return an empty string if no students are found
}

$conn->close();
?>
