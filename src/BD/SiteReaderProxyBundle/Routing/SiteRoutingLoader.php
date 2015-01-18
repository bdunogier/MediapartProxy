<?php
namespace BD\SiteReaderProxyBundle\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;

class SiteRoutingLoader extends Loader
{
    /** @var \Symfony\Component\Finder\Finder */
    private $finder;

    /** @var string */
    private $rootHost;

    /** @var \Symfony\Component\HttpKernel\Bundle\BundleInterface[] */
    private $bundles;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param string $rootHost
     */
    public function __construct( Finder $finder, KernelInterface $kernel, $rootHost )
    {
        $this->finder = $finder;
        $this->bundles = $kernel->getBundles();
        $this->rootHost = parse_url( $rootHost, PHP_URL_HOST );
    }

    public function load( $resource, $type = null )
    {
        // YAO, a custom SiteDomainRouteCollection
        $collection = new RouteCollection();

        foreach ( $this->bundles as $bundle )
        {
            $path = $bundle->getPath() . '/Resources/config/sites_routing/';
            if ( !file_exists( $path ) )
                continue;

            /** @var SplFileInfo $file */
            foreach ( $this->finder->in( $path ) as $file )
            {
                $host = $file->getBasename( '.yml' ) . '.' . $this->rootHost;

                /** @var RouteCollection $routes */
                $routes = $this->import( $file->getPathName(), 'yaml' );
                $routes->setHost( $host );

                $collection->addCollection( $routes );
            }
        }

        return $collection;
    }

    public function supports( $resource, $type = null )
    {
        return $type === 'domain';
    }
}
