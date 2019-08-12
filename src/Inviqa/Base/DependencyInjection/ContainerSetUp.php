<?php

namespace Inviqa\Base\DependencyInjection;

use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\GlobFileLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;

class ContainerSetUp
{
    const CONFIG_PATH = __DIR__.'/../../../../app/config';

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public static function bootstrapContainer(array $additionalConfig = [])
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new CommandPass('inviqa-project-reporter.console'));

        $locator = new FileLocator(self::CONFIG_PATH);
        $resolver = new LoaderResolver(array(
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new GlobFileLoader($locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ));

        $loader = new DelegatingLoader($resolver);

        $loader->load('config'.self::CONFIG_EXTS, 'glob');
        $loader->load('packages/*'.self::CONFIG_EXTS, 'glob');
        $loader->load('services/*'.self::CONFIG_EXTS, 'glob');
        foreach ($additionalConfig as $config) {
            $loader->load($config);
        }

        $container->compile();

        /**
         * Not sure this is really the right way to go with this - but right now I can't get the env vars to import
         * correctly.
         */
        $p = new \Symfony\Component\DependencyInjection\Dumper\PhpDumper($container);
        eval(substr($p->dump(), 5));

        return new \ProjectServiceContainer(); // return $container;
    }
}
