<?php

namespace Etd\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voiture
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Etd\EtudiantBundle\Entity\VoitureRepository")
 */
class Voiture {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateajout", type="datetime")
     */
    private $dateajout;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=30)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=30)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=30)
     */
    private $matricule;

    /**
     * @ORM\ManyToOne(targetEntity="Etd\EtudiantBundle\Entity\Personne" , inversedBy="voitures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personne;

    /**
     * @ORM\OneToMany(targetEntity="Etd\EtudiantBundle\Entity\Photovoiture", mappedBy="mavoiture", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $voituresphotos;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set marque
     *
     * @param string $marque
     * @return Voiture
     */
    public function setMarque($marque) {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string 
     */
    public function getMarque() {
        return $this->marque;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return Voiture
     */
    public function setModel($model) {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string 
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * Set matricule
     *
     * @param string $matricule
     * @return Voiture
     */
    public function setMatricule($matricule) {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string 
     */
    public function getMatricule() {
        return $this->matricule;
    }

    /**
     * Set personne
     *
     * @param \Etd\EtudiantBundle\Entity\Personne $personne
     * @return Voiture
     */
    public function setPersonne(\Etd\EtudiantBundle\Entity\Personne $personne) {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \Etd\EtudiantBundle\Entity\Personne 
     */
    public function getPersonne() {
        return $this->personne;
    }

    public function __toString() {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->voituresphotos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add voituresphotos
     *
     * @param \Etd\EtudiantBundle\Entity\Photo $voituresphotos
     * @return Voiture
     */
    public function addVoituresphoto(\Etd\EtudiantBundle\Entity\PhotoVoiture $voituresphotos) {
        $this->voituresphotos[] = $voituresphotos;

        return $this;
    }

    /**
     * Remove voituresphotos
     *
     * @param \Etd\EtudiantBundle\Entity\Photo $voituresphotos
     */
    public function removeVoituresphoto(\Etd\EtudiantBundle\Entity\PhotoVoiture $voituresphotos) {
        $this->voituresphotos->removeElement($voituresphotos);
    }

    /**
     * Get voituresphotos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVoituresphotos() {
        return $this->voituresphotos;
    }


    /**
     * Set dateajout
     *
     * @param \DateTime $dateajout
     * @return Voiture
     */
    public function setDateajout($dateajout)
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    /**
     * Get dateajout
     *
     * @return \DateTime 
     */
    public function getDateajout()
    {
        return $this->dateajout;
    }
}
