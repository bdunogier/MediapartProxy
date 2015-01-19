<?php
namespace BD\SiteReaderProxy\LmdSiteBundle;

use BD\SiteReaderProxyBundle\Proxy\AbstractFormBasedAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CredentialsBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\FormBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\UrlBased;
use DOMDocument;
use DOMXPath;

class LmdAuthenticator
    extends AbstractFormBasedAuthenticator
    implements WebsiteAuthenticator, CredentialsBased, UrlBased, FormBased
{
    public function getUsernameFieldName()
    {
        return 'email';
    }

    public function getPasswordFieldName()
    {
        return 'mot_de_passe';
    }

    public function getExtraFormFields()
    {
        return [
            'page' => 'connexion',
            'formulaire_action' => 'identification_lecteur',
            'formulaire_action_args' => $this->getLoginFormHash(),
            'retour' => 'http://www.monde-diplomatique.fr/',
            'site_distant' => 'http://www.monde-diplomatique.fr/',
            'email' => 'email@example.com',
            'mot_de_passe' => 'abcdefgh',
            'valider' =>  'valider'
        ];
    }

    private function getLoginFormHash()
    {
        $doc = new DOMDocument;
        @$doc->loadHTML(
            file_get_contents( 'http://www.monde-diplomatique.fr/?page=core-connexion&retour=http%3A%2F%2Fwww.monde-diplomatique.fr%2F' ),
            LIBXML_NOCDATA | LIBXML_NOWARNING | LIBXML_NOERROR
        );

        $xpath = new DOMXPath( $doc );
        $domNodeList = $xpath->query( "//form//input[@name='formulaire_action_args']" );
        $domNode = $domNodeList->item( 0 );
        $hash = $domNode->attributes->getNamedItem( 'value' )->nodeValue;

        return $hash;
    }
}
