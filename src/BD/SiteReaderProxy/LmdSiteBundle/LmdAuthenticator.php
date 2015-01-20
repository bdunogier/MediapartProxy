<?php
namespace BD\SiteReaderProxy\LmdSiteBundle;

use BD\SiteReaderProxyBundle\Proxy\AbstractFormBasedAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CookieBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CredentialsBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\FormBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\UrlBased;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class LmdAuthenticator
    extends AbstractFormBasedAuthenticator
    implements WebsiteAuthenticator, CredentialsBased, UrlBased, FormBased, CookieBased
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

    public function verifyCookies( CookieJar $cookieJar )
    {
        $gotCookie = false;

        /** @var \GuzzleHttp\Cookie\SetCookie $cookie */
        foreach ( $cookieJar as $cookie )
        {
            if ( $cookie->getName() == 'spip_session' )
            {
                $gotCookie = true;
            }
        }

        if ( !$gotCookie )
        {
            throw new ProxyAuthenticationException( $this->getUri() );
        }
    }
}
