<?php

namespace Basalt\Providers;

use Symfony\Component\Routing\Generator\UrlGenerator;

class TwigServiceProvider extends ServiceProvider
{
    public function provide(){
        $this->app->container->twig = function() {
            $twigLoader = new \Twig_Loader_Filesystem(dirname(dirname(dirname(__FILE__))).'/views');

            $twig = new \Twig_Environment($twigLoader, [
                //'cache' => '../cache/twig',
                'autoescape' => false
            ]);

            $assetFilter = new \Twig_SimpleFilter('asset', function($name) {
                return $this->app->container->mainUrl.'assets/'.$name;
            });
            $urlFilter = new \Twig_SimpleFilter('url', function($name, $parameters = []) {
                $container = $this->app->container;
                return $container->mainUrl.'index.php/'.$container->generator->generate($name, $parameters, UrlGenerator::RELATIVE_PATH); // TODO: Embrace this brothel
            });

            $twig->addFilter($assetFilter);
            $twig->addFilter($urlFilter);

            return $twig;
        };
    }
}