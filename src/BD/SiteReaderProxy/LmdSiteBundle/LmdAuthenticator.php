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
    public function getUri()
    {
        return "https://lecteurs.mondediplo.net/?page=connexion";
    }

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
            'valider' =>  'valider'
        ];
    }

    private function getLoginFormHash()
    {
        static $hash;

        if ( $hash === null )
        {
            $doc = new DOMDocument;
            @$doc->loadHTML(
                file_get_contents( $this->getUri() ),
                LIBXML_NOCDATA | LIBXML_NOWARNING | LIBXML_NOERROR
            );

            $xpath = new DOMXPath( $doc );
            $domNodeList = $xpath->query( "//form//input[@name='formulaire_action_args']" );
            $domNode = $domNodeList->item( 0 );
            $hash = $domNode->attributes->getNamedItem( 'value' )->nodeValue;
        }

        return $hash;
    }

    public function verifyHeaders( array $responseHeaders )
    {
        if ( !isset( $responseHeaders['Set-Cookie'] ) )
        {
            return null;
        }

        if ( is_array( $responseHeaders['Set-Cookie'] ) )
        {
            if ( count( $responseHeaders['Set-Cookie'] ) === 0 )
            {
                return null;
            }
            $cookie = $responseHeaders['Set-Cookie'][0];
        }

        if ( !preg_match( '/(spip_session=[^;]+)/', $cookie, $m ) )
        {
            return null;
        }

        return $m[1];
    }
}
