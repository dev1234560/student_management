<?php
// var_dump($_POST);

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

// Capture and sanitize form data
$name = htmlspecialchars($_POST['name']);
$class = htmlspecialchars($_POST['class']);
$school = htmlspecialchars($_POST['school']);
$contact = htmlspecialchars($_POST['contact']);
$coachingDate = htmlspecialchars($_POST['coachingDate']);

// Fix: Convert the selected subjects array to a string
$subjectsArray = $_POST['subjects']; // This is an array
$subjectsString = implode(",", $subjectsArray);  // Convert array to string


// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Check if subjects are selected
//     if (!empty($_POST['subjects'])) {
//         // Store the selected subjects (this will be an array)
//         $selectedSubjects = $_POST['subjects'];

//         // Display the selected subjects
//         echo "You have selected the following subjects:<br>";
//         foreach ($selectedSubjects as $subject) {
//             echo htmlspecialchars($subject) . "<br>";
//         }
//     } else {
//         echo "No subjects were selected.";
//     }
// }


// Insert new student into the students table
$sql = "INSERT INTO students (name, class, school, contact, subjects, coachingDate) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $class, $school, $contact, $subjectsString, $coachingDate);
$stmt->execute();
$stmt->close();

$isUpdating = $_POST['isUpdating'] ?? '';  // Check if it's an update request

echo "Data to be inserted: <br>";
echo "First Name: $name <br>";
echo "Last Name: $class <br>";
echo "Email: $school <br>";
echo "Contact: $contact <br>";
echo "Address: $subjectsString <br>";
echo "City: $coachingDate <br>";

if (empty($name) || empty($class) || empty($school) || empty($contact) || empty($subjects) || empty($coachingDate) ) {
    die("Some fields are empty. Please fill all fields.");
}


if ($isUpdating === "yes") {
    // Update the existing record
    $updateStmt = $conn->prepare("UPDATE students SET  class=?,school=?, contact=?, subjects=?, coachingDate=? WHERE name=?");
    $updateStmt->bind_param("ssssss",  $class,$school,$contact, $subjects, $coachingDate, $name);
    if ($updateStmt === false) {
        die("Error preparing update statement: " . $conn->error);
    }

    if ($updateStmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $updateStmt->error;
    }
    $updateStmt->close();
} else {
    
    // Insert a new record
    $insertStmt = $conn->prepare("INSERT INTO students (name, class, school, contact,  subjects,coachingDate) VALUES (?, ?, ?, ?, ?, ?)");  
    $insertStmt->bind_param("ssssss", $name, $class, $school, $contact, $subjects , $coachingDate,);


    
    if ($insertStmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $insertStmt->error;
    }
    $insertStmt->close();
}
echo "You have selected the following subjects:<br>";
foreach ($subjectsArray as $subject) {
    echo htmlspecialchars($subject) . "<br>";
}
// Close connection
$conn->close();
?>
