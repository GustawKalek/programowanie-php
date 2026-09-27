<?php
require_once "./classes/Student.php";
require_once "./classes/Teacher.php";

$student = null;
$teacher = null;
$teacher1 = new Teacher("Jan Kowalski", 45, "Programowanie");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["submit_student"])) {
        $name = htmlspecialchars($_POST["name"]);
        $age = htmlspecialchars($_POST["age"]);
        $school = htmlspecialchars($_POST["school"]);

        $student = new Student($name, (int) $age, $school);
    }

    if (isset($_POST["submit_teacher"])) {
        $teacherName = htmlspecialchars($_POST["teacher_name"]);
        $teacherAge = htmlspecialchars($_POST["teacher_age"]);
        $subject = htmlspecialchars($_POST["subject"]);

        $teacher = new Teacher($teacherName, (int) $teacherAge, $subject);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP - OOP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Uczniowie</h1>

    <h2>Informacje o nauczycielu</h2>
    <?php echo $teacher1->getInfo(); ?>

    <h2>Dodaj nowego studenta</h2>

    <form method="post" action="">
        <label for="name">Imię:</label>
        <input type="text" id="name" name="name" required>

        <label for="age">Wiek:</label>
        <input type="number" id="age" name="age" required>

        <label for="school">Szkoła:</label>
        <input type="text" id="school" name="school" required>

        <button type="submit" name="submit_student">Dodaj</button>
    </form>

    <?php
    if ($student) {
        echo "<div class=\"student\">" . $student->getInfo() . "</div>";
    }
    ?>

    <h2>Dodaj nowego nauczyciela</h2>

    <form method="post" action="">
        <label for="teacher_name">Imię:</label>
        <input type="text" id="teacher_name" name="teacher_name" required>

        <label for="teacher_age">Wiek:</label>
        <input type="number" id="teacher_age" name="teacher_age" required>

        <label for="subject">Przedmiot:</label>
        <input type="text" id="subject" name="subject" required>

        <button type="submit" name="submit_teacher">Dodaj</button>
    </form>

    <?php
    if ($teacher) {
        echo "<div class=\"student\">" . $teacher->getInfo() . "</div>";
    }
    ?>
</body>

</html>