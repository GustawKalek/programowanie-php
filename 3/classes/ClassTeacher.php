<?php
    require_once "Teacher.php";

    class ClassTeacher extends Teacher {
        private string $className;

        public function __construct(string $name, int $age, string $subject, string $className) {
            parent::__construct($name, $age, $subject);
            $this->className = $className;
        }

        public function getInfo(): string {
            return parent::getInfo() . ", wychowawca klasy: {$this->className}";
        }

        public function getHtml(): string
        {
            return <<<HTML
                <div class="class-teacher">
                    <h2>{$this->name}</h2>
                    <p>Wiek: {$this->age}</p>
                    <p>Klasa: {$this->className}</p>
                </div>
            HTML;
        }
    }
