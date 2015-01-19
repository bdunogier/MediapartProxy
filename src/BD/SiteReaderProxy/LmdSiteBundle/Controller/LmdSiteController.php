<?php
namespace BD\SiteReaderProxy\LmdSiteBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class LmdSiteController
{
    public function uriAction( $uri )
    {
        return new Response( file_get_contents( 'http://www.monde-diplomatique.fr/' . $uri ) );
    }
}
