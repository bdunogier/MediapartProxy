<?php
namespace BD\MediapartProxyBundle\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\RouteCollection;

class SiteRoutingLoader extends Loader
{
    /** @var \Symfony\Component\Finder\Finder */
    private $finder;

    /** @var string */
    private $rootHost;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param string $rootHost
     */
    public function __construct( Finder $finder, $rootHost )
    {
        $this->finder = $finder;

        $this->rootHost = parse_url( $rootHost, PHP_URL_HOST );
    }

    public function load( $resource, $type = null )
    {
        // YAO, a custom SiteDomainRouteCollection
        $collection = new RouteCollection();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ( $this->finder as $file )
        {
            $host = $file->getBasename( '.yml' ) . '.' . $this->rootHost;

            /** @var RouteCollection $routes */
            $routes = $this->import( $file->getPathName(), 'yaml' );
            $routes->setHost( $host );

            $collection->addCollection( $routes );
        }

        return $collection;
    }

    public function supports( $resource, $type = null )
    {
        return $type === 'domain';
    }
}
