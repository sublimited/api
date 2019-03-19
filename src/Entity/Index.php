<?php
// src/Entity/Index.php
namespace App\Entity;

class Index
{
    protected $name;
    protected $date_of_birth;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(\DateTime $date_of_birth = null)
    {
        $this->date_of_birth = $date_of_birth;
    }
}
