 <?php
session_start();
require_once("database.php");

$studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);
$date = filter_input(INPUT_POST, 'date');
$status = filter_input(INPUT_POST, 'status');

if (!$studentID || !$date || !in_array($status, ['Present', 'Absent', 'Late'])) {
    $_SESSION['attendance_error'] = "Invalid attendance data. Please try again.";
    header("Location: add_attendance.php");
    exit;
}

// Check for duplicate entry
$checkQuery = "SELECT COUNT(*) FROM attendance_log WHERE studentID = :studentID AND date = :date";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bindValue(':studentID', $studentID);
$checkStmt->bindValue(':date', $date);
$checkStmt->execute();
$exists = $checkStmt->fetchColumn();
$checkStmt->closeCursor();

if ($exists > 0) {
    $_SESSION['attendance_error'] = "Attendance for this student on this date already exists.";
    header("Location: add_attendance.php");
    exit;
}

$query = "INSERT INTO attendance_log (studentID, date, status)
          VALUES (:studentID, :date, :status)";
$statement = $db->prepare($query);
$statement->bindValue(':studentID', $studentID);
$statement->bindValue(':date', $date);
$statement->bindValue(':status', $status);
$success = $statement->execute();
$statement->closeCursor();

if ($success) {
    $_SESSION['attendance_success'] = "Attendance record added successfully.";
} else {
    $_SESSION['attendance_error'] = "Failed to add attendance. Please try again.";
}

header("Location: add_attendance.php");
exit;
?>
