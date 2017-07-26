<?php

namespace Etd\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photovoiture
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Etd\EtudiantBundle\Entity\PhotovoitureRepository")
 */
class Photovoiture extends Photosuper
{
  
     /**
     * @ORM\ManyToOne(targetEntity="Etd\EtudiantBundle\Entity\Voiture", inversedBy="voituresphotos")
     * @ORM\JoinColumn(name="voiture_id",nullable=false)
     */
    private $mavoiture;

   
    /**
     * Set voiture
     *
     * @param \Etd\EtudiantBundle\Entity\Voiture $voiture
     * @return Photovoiture
     */
    public function setMavoiture(\Etd\EtudiantBundle\Entity\Voiture $voiture)
    {
        $this->mavoiture = $voiture;

        return $this;
    }

    /**
     * Get voiture
     *
     * @return \Etd\EtudiantBundle\Entity\Voiture 
     */
    public function getMavoiture()
    {
        return $this->mavoiture;
    }
}
