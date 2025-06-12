<?php
session_start();
require_once("database.php");

// 
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
if (!$student_id) {
    header("Location: index.php");
    exit;
}

//
$query = 'SELECT s.*, t.studentType 
          FROM students s 
          LEFT JOIN types t ON s.typeID = t.typeID 
          WHERE studentID = :student_id';
$statement = $db->prepare($query);
$statement->bindValue(':student_id', $student_id);
$statement->execute();
$student = $statement->fetch();
$statement->closeCursor();

if (!$student) {
    echo "Student not found.";
    exit;
}

// Обработка имени изображения
$imageName = $student['imageName'] ?? 'placeholder_100.jpg';
$dotPosition = strrpos($imageName, '.');
$baseName = substr($imageName, 0, $dotPosition);
$extension = substr($imageName, $dotPosition);

if (str_ends_with($baseName, '_100')) {
    $baseName = substr($baseName, 0, -4);
}
$imageName_400 = $baseName . '_400' . $extension;
$imagePath = './images/' . $imageName_400;
if (!file_exists($imagePath)) {
    $imagePath = './images/placeholder_400.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Details</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="container">
    <h2>Student Details</h2>

    <img class="student-image" src="<?php echo htmlspecialchars($imagePath); ?>" 
         alt="<?php echo htmlspecialchars($student['firstName'] . ' ' . $student['lastName']); ?>" />

    <div class="contact-info">
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($student['firstName']); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($student['lastName']); ?></p>
        <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
        <p><strong>Attendance:</strong> <?php echo htmlspecialchars($student['attendance']); ?></p>
        <p><strong>Schedule:</strong> <?php echo htmlspecialchars($student['schedule']); ?></p>
        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($student['startDate']); ?></p>
        <p><strong>Student Type:</strong> <?php echo htmlspecialchars($student['studentType']); ?></p>
    </div>

    <a class="back-link" href="index.php">← Back to Student List</a>
</div>

<?php include("footer.php"); ?>

</body>
</html>
