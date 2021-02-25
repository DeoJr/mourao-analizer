<?php
/**
 * @author      Webjump Developer Team <developer@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace MouraoAnalizer\Configuration;

use MouraoAnalizer\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Bootstrap
{
    public static function buildApp()
    {
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));

        $loader->load('../Config/app.yaml');
        $loader->load('../Config/params.yaml');
        $loader->load('../Config/tasks.yaml');
        $loader->load('../Config/helpers.yaml');
        $loader->load('../Config/commands.yaml');

        $container->compile();

        $container->get(Application::class)->run();
    }
}