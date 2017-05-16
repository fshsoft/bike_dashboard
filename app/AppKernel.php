<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);
    }

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),

            new Symfony\Bundle\MonologBundle\MonologBundle(),

            new Symfony\Bundle\TwigBundle\TwigBundle(),

            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

            new Symfony\Bundle\SecurityBundle\SecurityBundle(),

            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

            new Bike\Dashboard\BikeDashboardBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();

            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/' . $this->getEnvironment() . '/config.yml');
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }
}
