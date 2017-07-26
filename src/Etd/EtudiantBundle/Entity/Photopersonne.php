<?php

namespace Etd\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Etd\EtudiantBundle\Entity\PhotopersonneRepository")
 */
class Photopersonne extends Photosuper
{
    /**
     * @ORM\ManyToOne(targetEntity="Etd\EtudiantBundle\Entity\Personne", inversedBy="photos")
     * @ORM\JoinColumn(name="personne_id",nullable=false)
     */
    private $personne;

    /**
     * Set personne
     *
     * @param \Etd\EtudiantBundle\Entity\Personne $personne
     * @return Photopersonne
     */
    public function setPersonne(\Etd\EtudiantBundle\Entity\Personne $personne)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \Etd\EtudiantBundle\Entity\Personne 
     */
    public function getPersonne()
    {
        return $this->personne;
    }
}
