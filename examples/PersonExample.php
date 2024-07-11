<?php

namespace Examples;

class PersonExample
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $age;

    /**
     * @var int
     */
    private int $salary;

    /**
     * Get a salary.
     *
     * @return int
     */
    public function getSalary(): int
    {
        return $this->salary;
    }

    /**
     * Set a salary.
     *
     * @param int $salary
     * @return void
     */
    public function setSalary(int $salary): void
    {
        $this->salary = $salary;
    }

    /**
     * Get a age.
     *
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * Set a age.
     *
     * @param int $age
     * @return void
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * Get a name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set a name.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}