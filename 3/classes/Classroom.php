<?php
    require_once "Student.php";
    require_once "Teacher.php";
    class Classroom {
        private string $name;
        private array $students = [];
        private ?Teacher $teacher = null;
        public function __construct(string $name) {
            $this->name = $name;
        }

        public function addStudent(Student $student) {
            $this->students[] = $student;
        }

        public function setTeacher(Teacher $teacher) {
            $this->teacher = $teacher;
        }

        public function getHTML(): string {
            $html = "<h2>Klasa: {$this->name}</h2>";

            if ($this->teacher !== null) {
                $html .= "<h3>Wychowawca</h3>";
                $html .= $this->teacher->getHtml();
            }

            $html .= "<h3>Uczniowie</h3>";
            foreach ($this->students as $student) {
                $html .= $student->getHTML();
            }

            return $html;
        }

    }
