<?php
namespace BD\SiteReaderProxyBundle\DependencyInjection\Factory;

use GuzzleHttp\Cookie\FileCookieJar;

class CookieJarFactory
{
    private $cookieFilePathName;

    public function __construct($cookieFilePathName)
    {
        $this->cookieFilePathName = $cookieFilePathName;
    }

    /**
     * @return \GuzzleHttp\Cookie\CookieJar
     */
    public function createCookieJar()
    {
        return new FileCookieJar($this->cookieFilePathName);
    }
}
