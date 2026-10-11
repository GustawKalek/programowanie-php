<?php
require_once "Person.php";

class Student extends Person
{
    private string $school;

    public function __construct(string $name, int $age, string $school)
    {
        $this->school = $school;
        $this->name = $name;
        $this->age = $age;
    }


    public function getInfo()
    {
        return "Imię: {$this->name}, wiek: {$this->age}, szkoła: {$this->school}";
    }

    public function getHtml(): string
    {
        return <<<HTML
            <div class="student">
                <h2>{$this->name}</h2>
                <p>Wiek: {$this->age}</p>
                <p>Szkoła: {$this->school}</p>
            </div>
        HTML;
    }
}
