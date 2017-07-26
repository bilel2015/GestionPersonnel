<?php

namespace Etd\EtudiantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EtdEtudiantBundle:Accueil:index.html.twig', array('zaid' => 'zaid'));
    }
}
