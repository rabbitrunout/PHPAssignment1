<?php 
session_start();

$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$course = filter_input(INPUT_POST, 'course');
$attendance = filter_input(INPUT_POST, 'attendance');
$schedule = filter_input(INPUT_POST, 'schedule'); // Selected radio button
$start_date = filter_input(INPUT_POST, 'start_date');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['image'];

require_once('database.php');

// Проверка на дубликат
$queryStudents = 'SELECT * FROM students';
$statement1 = $db->prepare($queryStudents);
$statement1->execute();
$students = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($students as $student) {
    if ($first_name === $student["firstName"] &&
        $last_name === $student["lastName"] &&
        $student_id !== $student["studentID"]) {
        $_SESSION["add_error"] = "Duplicate student name. Try again.";
        header("Location: error.php");
        die();
    }
}

// Проверка на пустые поля
if ($first_name === null || $last_name === null || $course === null ||
    $attendance === null || $schedule === null || $start_date === null || $type_id === null) {
    $_SESSION["add_error"] = "Invalid student data. Check all fields and try again.";
    header("Location: error.php");
    die();
}

require_once('image_util.php');

// Получение текущего изображения
$query = 'SELECT imageName FROM students WHERE studentID = :studentID';
$statement = $db->prepare($query);
$statement->bindValue(':studentID', $student_id);
$statement->execute();
$current = $statement->fetch();
$current_image_name = $current['imageName'];
$statement->closeCursor();

$image_name = $current_image_name;

if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $base_dir = 'images/';
    
    // Удаление старых изображений
    if ($current_image_name) {
        $dot = strrpos($current_image_name, '_100.');
        if ($dot !== false) {
            $original_name = substr($current_image_name, 0, $dot) . substr($current_image_name, $dot + 4);
            $original = $base_dir . $original_name;
            $img_100 = $base_dir . $current_image_name;
            $img_400 = $base_dir . substr($current_image_name, 0, $dot) . '_400' . substr($current_image_name, $dot + 4);

            if (file_exists($original)) unlink($original);
            if (file_exists($img_100)) unlink($img_100);
            if (file_exists($img_400)) unlink($img_400);
        }
    }

    // Загрузка и обработка нового изображения
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    $dot_position = strrpos($original_filename, '.');
    $name_without_ext = substr($original_filename, 0, $dot_position);
    $extension = substr($original_filename, $dot_position);
    $image_name = $name_without_ext . '_100' . $extension;
}

// Обновление записи студента
$query = 'UPDATE students
    SET firstName = :firstName,
        lastName = :lastName,
        course = :course,
        attendance = :attendance,
        schedule = :schedule,
        startDate = :startDate,
        typeID = :typeID,
        imageName = :imageName
    WHERE studentID = :studentID';

$statement = $db->prepare($query);
$statement->bindValue(':firstName', $first_name);
$statement->bindValue(':lastName', $last_name);
$statement->bindValue(':course', $course);
$statement->bindValue(':attendance', $attendance);
$statement->bindValue(':schedule', $schedule);
$statement->bindValue(':startDate', $start_date);
$statement->bindValue(':typeID', $type_id);
$statement->bindValue(':imageName', $image_name);
$statement->bindValue(':studentID', $student_id);
$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;
header("Location: update_confirmation.php");
die();
?>
