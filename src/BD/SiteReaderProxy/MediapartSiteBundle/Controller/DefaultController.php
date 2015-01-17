<?php

namespace BD\SiteReaderProxy\MediapartSiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BDSiteReaderProxyMediapartSiteBundle:Default:index.html.twig', array('name' => $name));
    }
}
