<?php
		require_once "./classes/Student.php";
		require_once "./classes/Teacher.php";
		require_once "./classes/Classroom.php";
		require_once "./classes/ClassTeacher.php";

		session_start();

		if (!isset($_SESSION['students'])) {
			$_SESSION['students'] = [
				new Student("Jan Kowalski", 17, "ZST"),
				new Student("Anna Kowalska", 16, "ZST")
			];

			$_SESSION['teachers'] = [
				new Teacher("Piotr Janicki", 35, "Informatyka"),

			];

			$_SESSION['classTeachers'] = [
				new ClassTeacher("Piotr Janicki", 35, "Informatyka", "3A"),
			];
		}

		// obsługa formularza ze studentem
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST["form_type"] == "student") {
				$name = $_POST["name"];
				$age = $_POST["age"];
				$school = $_POST["school"];

				// $newStudent = new Student($name, $age, $school);

				$_SESSION['students'][] = new Student($name, $age, $school);
			}

			if ($_POST["form_type"] == "teacher") {
				$name = $_POST["name"];
				$age = $_POST["age"];
				$subject = $_POST["subject"];

				$_SESSION['teachers'][] = new Teacher($name, $age, $subject);
			}

			header("Location: " . $_SERVER["PHP_SELF"]);
			exit;
		}

		$classroom = new Classroom("13A");

		foreach ($_SESSION['students'] as $student) {
			$classroom->addStudent($student);
		}

		$teacher1 = new Teacher("Jan", 32, "Matematyka");
		$classTeacher = new ClassTeacher("Ewa", 25, "WF", "13A");
		$classroom->setTeacher($classTeacher);




		echo "<h1>Klasa".$classroom->getHTML()."</h1>";
		// echo "\n<h1>Wychowawca klasy: ".$classTeacher->getInfo()."</h1>";

//	dodanie nauczyciela
	$teacher1 = new Teacher("Jan", 18, "ZSK");

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP - OOP</title>
	  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Uczniowie</h1>

	<!--  ======================= -->
	<!--  FORMULARZ NOWEGO STUDENTA -->
	<!--  ======================= -->

	<h2>Dodaj ucznia</h2>
  <form method="post">
	  <input type="hidden" name="form_type" value="student">

	  <label for="name">Imię:</label>
	  <input type="text" name="name" id="name" autofocus>

	  <label for="age">Wiek:</label>
	  <input type="number" name="age" id="age">

	  <label for="school">Szkoła:</label>
	  <input type="text" name="school" id="school">

	  <button type="submit">Dodaj ucznia</button>
  </form>

  <!--  ======================= -->
  <!--  FORMULARZ NOWEGO NAUCZYCIELA -->
  <!--  ======================= -->

  <h2>Dodaj ucznia</h2>
  <form method="post">
	  <input type="hidden" name="form_type" value="teacher">

	  <label for="name">Imię:</label>
	  <input type="text" name="name" id="name" autofocus>

	  <label for="age">Wiek:</label>
	  <input type="number" name="age" id="age">

	  <label for="subject">Przedmiot:</label>
	  <input type="text" name="subject" id="subject">

	  <button type="submit">Dodaj ucznia</button>
  </form>


<!--  ======================= -->
<!--  INFORMACJE O UCZNIACH -->
<!--  ======================= -->

  <h2>Informacje o uczniach</h2>

  <?php
    foreach ($_SESSION['students'] as $student) {
      echo $student->getInfo()."<br>";
    }
    echo "<hr>";
  ?>

		<h2>Informacje HTML - HEREDOC</h2>
  <?php
	foreach ($_SESSION['students'] as $student) {
		echo $student->getHtml();
	}

	echo "<h3>Nauczyciel:</h3>".$teacher1->getHtml();

// INFORMACJE O NAUCZYCIELACH




    echo "<h2>Informacje o nauczycielach</h2>";
    echo $teacher1->getInfo()."<hr>";

    echo "<h2>Wychowawca</h2>";
    echo $classTeacher->getInfo()."<hr>";
  ?>

  <h2>Informacje HTML - HEREDOC</h2>
  <?php
	  echo $teacher1->getHtml();
	  echo "<h3>Nauczyciel:</h3>".$teacher1->getHtml();

	  if (isset($newTeacher)) {
		  echo "<h3>Dodany nauczyciel:<br>";
		  echo $newTeacher->getHtml();
	  }



  ?>
</body>
</html>

