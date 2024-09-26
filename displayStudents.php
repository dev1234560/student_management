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

// Fetch all student data
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Student Details</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    padding: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
table, th, td {
    border: 1px solid #ddd;
}
th, td {
    padding: 12px;
    text-align: left;
}
th {
    background-color: #007BFF;
    color: white;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
.delete-btn {
    background-color: #ff4d4d;
    color: white;
    padding: 6px 12px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}
.delete-btn:hover {
    background-color: #ff1a1a;
}
</style>
</head>
<body>

<h2>All Student Details</h2>
<!-- Filter Form -->
<form id="filterForm" action="javascript:void(0);" method="get" onsubmit="performFilter()">
<div>
<label for="classFilter">Class</label>
<input type="number" id="classFilter" name="classFilter" placeholder="Enter class to filter">

<label for="schoolFilter">School</label>
<input type="text" id="schoolFilter" name="schoolFilter" placeholder="Enter school to filter">


<label for="dateFilter" class="form-label">Coaching Date</label>
<input type="date" id="dateFilter" name="dateFilter" class="form-control" placeholder="Enter coaching date to filter">


<label for="subjectFilter" class="form-label">Subjects</label>
<input type="text" id="subjectFilter" name="subjectFilter" class="form-control" placeholder="Enter subjects to filter">


<button type="submit">Filter</button>
</div>
</form>

<br>

<!-- Student List Table -->
<table>
<thead>
<tr>
<th>Name</th>
<th>Class</th>
<th>School</th>
<th>Contact</th>
<th>Subjects</th>
<th>Coaching Date</th>
<th>Actions</th> 
</tr>
</thead>
<tbody id="studentList">
<?php
// Fetch all students
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
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
    echo "<tr><td colspan='6'>No students found</td></tr>";
}
function calculateNextPaymentDate($coachingDate) {
    $nextPaymentDate = date('Y-m-d', strtotime($coachingDate . ' +30 days'));
    return $nextPaymentDate;
}

// Fetch the last payment date for a student
// $studentId = 1;  // Example student ID
// $sql = "SELECT MAX(payment_date) as lastPayment FROM student_fees WHERE student_id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $studentId);
// $stmt->execute();
// $stmt->bind_result($lastPaymentDate);
// $stmt->fetch();
// $stmt->close();

// Check if the next payment is due
// $nextPaymentDate = calculateNextPaymentDate($lastPaymentDate);
// if (date('Y-m-d') >= $nextPaymentDate) {
//     // Calculate the next month's fee and insert into the student_fees table
//     $sql = "INSERT INTO student_fees (student_id, total_fee, payment_date) VALUES (?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ids", $studentId, $totalFee, $nextPaymentDate);
//     $stmt->execute();
//     $stmt->close();
// }

// Fetch the last payment date for each student
// $sql = "SELECT s.name, sf.payment_date, MAX(sf.payment_date) as lastPaymentDate FROM students s
// JOIN student_fees sf ON s.id = sf.student_id
// GROUP BY s.id";

// $result = $conn->query($sql);
// while ($row = $result->fetch_assoc()) {
//     $nextPaymentDue = calculateNextPaymentDate($row['lastPaymentDate']);
//     $status = (date('Y-m-d') >= $nextPaymentDue) ? 'Payment Due' : 'Up to Date';
//     echo "<tr>
//     <td>" . htmlspecialchars($row['name']) . "</td>
//     <td>" . htmlspecialchars($row['lastPaymentDate']) . "</td>
//     <td>" . htmlspecialchars($status) . "</td>
//   </tr>";
// }

?>
</tbody>
</table>

<!-- No Results Message -->
<div id="noResults" class="no-results" style="display: none;">No students found matching your criteria.</div>

<?php
// Close connection
$conn->close();
?>



<script>
// Function to filter students
function performFilter() {
    const classFilter = document.getElementById('classFilter').value;
    const schoolFilter = document.getElementById('schoolFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const subjectFilter = document.getElementById('subjectFilter').value;
    
    // Send AJAX request to filter students
    fetch(`filterStudents.php?classFilter=${classFilter}&schoolFilter=${schoolFilter}&dateFilter=${dateFilter}&subjectFilter=${subjectFilter}`)
    .then(response => response.text())
    .then(data => {
        const studentList = document.getElementById('studentList');
        const noResults = document.getElementById('noResults');
        
        studentList.innerHTML = data;
        
        // Show "No students found" message if no data returned
        if (studentList.innerHTML.trim() === "") {
            noResults.style.display = "block";
        } else {
            noResults.style.display = "none";
        }
    });
}
// Function to delete a student
function deleteStudent(id) {
    if (confirm("Are you sure you want to delete this student?")) {
        fetch(`deleteStudent.php?id=${id}`)
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                document.getElementById('student-' + id).remove();
                alert("Student deleted successfully.");
            } else {
                alert("Error deleting student.");
            }
        });
    }
}
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
