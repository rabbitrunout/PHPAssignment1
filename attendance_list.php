 <?php
session_start();
require_once("database.php");

// Получаем последние 5 записей посещаемости
$query = "
    SELECT a.date, a.status, s.firstName, s.lastName 
    FROM attendance_log a
    JOIN students s ON a.studentID = s.studentID
    ORDER BY a.date DESC
    LIMIT 5
";

$statement = $db->prepare($query);
$statement->execute();
$attendanceRecords = $statement->fetchAll();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Последняя посещаемость</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Последние 5 записей посещаемости</h2>

    <?php if ($attendanceRecords): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Студент</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceRecords as $rec): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rec['date']); ?></td>
                        <td><?php echo htmlspecialchars($rec['lastName'] . ", " . $rec['firstName']); ?></td>
                        <td><?php echo htmlspecialchars($rec['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Записей посещаемости пока нет.</p>
    <?php endif; ?>

    <p><a href="add_attendance.php">Добавить новую запись посещаемости</a></p>
    <p><a href="index.php">← Вернуться к списку студентов</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
