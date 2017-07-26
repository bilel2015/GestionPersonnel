<?php

namespace Etd\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personne
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Etd\EtudiantBundle\Entity\PersonneRepository")
 */
class Personne
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30)
     */
    private $prenom;

       /**
     * @var string
     *
     * @ORM\Column(name="cne", type="string", length=30,unique=true)
     */
    private $cne;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateajout", type="datetime")
     */
    private $dateajout;
    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="fonction", type="string", length=30)
     */
    private $fonction;
     /**
   * @ORM\OneToMany(targetEntity="Etd\EtudiantBundle\Entity\Voiture", mappedBy="personne", cascade={"remove"})
   * @ORM\JoinColumn(nullable=false)
   */
    private $voitures;
  
    /**
   * @ORM\OneToMany(targetEntity="Etd\EtudiantBundle\Entity\Photopersonne", mappedBy="personne", cascade={"remove"})
   * @ORM\JoinColumn(nullable=false)
   */
    private $photos;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Personne
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return Personne
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     * @return Personne
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string 
     */
    public function getFonction()
    {
        return $this->fonction;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->voitures = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add voitures
     *
     * @param \Etd\EtudiantBundle\Entity\Voiture $voitures
     * @return Personne
     */
    public function addVoiture(\Etd\EtudiantBundle\Entity\Voiture $voitures)
    {
        $this->voitures[] = $voitures;

        return $this;
    }

    /**
     * Remove voitures
     *
     * @param \Etd\EtudiantBundle\Entity\Voiture $voitures
     */
    public function removeVoiture(\Etd\EtudiantBundle\Entity\Voiture $voitures)
    {
        $this->voitures->removeElement($voitures);
    }

    /**
     * Get voitures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVoitures()
    {
        return $this->voitures;
    }


    /**
     * Add photos
     *
     * @param \Etd\EtudiantBundle\Entity\Photopersonne $photos
     * @return Personne
     */
    public function addPhoto(\Etd\EtudiantBundle\Entity\Photopersonne $photos)
    {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Etd\EtudiantBundle\Entity\Photopersonne $photos
     */
    public function removePhoto(\Etd\EtudiantBundle\Entity\Photopersonne $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set cne
     *
     * @param string $cne
     * @return Personne
     */
    public function setCne($cne)
    {
        $this->cne = $cne;

        return $this;
    }

    /**
     * Get cne
     *
     * @return string 
     */
    public function getCne()
    {
        return $this->cne;
    }

  


    /**
     * Set dateajout
     *
     * @param \DateTime $dateajout
     * @return Personne
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
    public function getDateajout($format = 'Y-m-d H:i:s')
    {
         return $this->dateajout->format($format);
    }
}
