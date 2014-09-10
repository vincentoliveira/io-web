<?php

namespace IO\DefaultBundle\Utils;
use Sdz\BlogBundle\Entity\Tag;

class CommandeTraiteur
{
    private $id;
    private $date;
    private $nom;
    private $prenom;
    private $adress;
    private $phone;
    private $number;
    private $place;
    private $placeadress;
    private $description;
    
    public function __construct()
    {
        $this->date         = new \DateTime;
    }
    
    public function setId($i)
    {
        $this->id = $i;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setDate($i)
    {
        $this->date = $i;
        return $this;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function setNom($i)
    {
        $this->nom = $i;
        return $this;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function setPrenom($i)
    {
        $this->prenom = $i;
        return $this;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function setAdress($i)
    {
        $this->adress = $i;
        return $this;
    }
    public function getAdress()
    {
        return $this->adress;
    }
    public function setPhone($i)
    {
        $this->phone = $i;
        return $this;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function setNumber($i)
    {
        $this->number = $i;
        return $this;
    }
    public function getNumber()
    {
        return $this->number;
    }
    public function setPlace($i)
    {
        $this->place = $i;
        return $this;
    }
    public function getPlace()
    {
        return $this->place;
    }
    public function setPlaceadress($i)
    {
        $this->placeadress = $i;
        return $this;
    }
    public function getPlaceadress()
    {
        return $this->placeadress;
    }
    public function setDescription($i)
    {
        $this->description = $i;
        return $this;
    }
    public function getDescription()
    {
        return $this->description;
    }
    
}

