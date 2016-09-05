<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function getAction()
    {
        return $this->render('CoreBundle:Default:index.html.twig', [
            'version' => '1.0.3',
        ]);
    }
}
