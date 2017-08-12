<?php
declare(strict_types = 1);

namespace PommProject\ApiPlatform\DependencyInjection;

use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\HttpKernel\DependencyInjection\Extension;
use \Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PommApiPlatformExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('pomm.yml');
    }
}
