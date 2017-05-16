<?php

namespace Bike\Dashboard\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Definition;

use Bike\Common\Exception\Debug\DebugException;

class BikeDashboardExtension extends Extension implements PrependExtensionInterface
{
    private $container;

    public function prepend(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $this->registerDaoConfiguration($config, $container, $loader);

        // service
        $loader->load('service.xml');
        $loader->load('twig.xml');
        $loader->load('security.xml');

        // compile class
        $this->addClassesToCompile(array(

        ));
    }

    protected function registerDaoConfiguration(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        if (!isset($config['dao'])) {
            return;
        }

        foreach ($config['dao'] as $k => $v) {
            $container->setAlias('bike.dashboard.params.dao.' . $k . '.conn_id', $v['conn_id']);
            $container->setParameter('bike.dashboard.params.dao.' . $k. '.db_name', $v['db_name']);
            $container->setParameter('bike.dashboard.params.dao.' . $k. '.prefix', $v['prefix']);
        }

        $loader->load('dao/primary.xml');
    }

    public function getAlias()
    {
        return 'bike_dashboard';
    }
}
