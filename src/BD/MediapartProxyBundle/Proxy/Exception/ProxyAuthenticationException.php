<?php
namespace BD\MediapartProxyBundle\Proxy\Exception;

use Exception;

class ProxyAuthenticationException extends Exception
{
    public function __construct( $uri )
    {
        parent::__construct( "Authentication failed for $uri" );
    }
}
