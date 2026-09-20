<?php
require_once "./classes/Student.php";
require_once "./classes/Teacher.php";

$student1 = new Student("Jan", 18, "ZSK");
$student2 = new Student("Anna", 20, "ZSŁ");
$teacher = new Teacher("Piotr", 35, "Informatyka");

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
    <h2>Informacje o uczniach</h2>

    <?php
    echo $student1->getInfo() . "<br />" . $student2->getInfo() . "<hr />"
    ?>

    <h2>Informacje o nauczycielu</h2>

    <?php
    echo $teacher->getInfo();
    ?>

    <h2>Innformacje HTML - HEREDOC</h2>

    <?php
    echo $student1->getHtml();
    echo $student2->getHtml();
    ?>
</body>

</html>