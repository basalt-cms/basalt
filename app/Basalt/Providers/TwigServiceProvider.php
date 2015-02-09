<?php

namespace Basalt\Providers;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Basalt\Helpers\HtmlHelper;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

class TwigServiceProvider extends ServiceProvider
{
    public function provide(){
        $this->app->container->htmlHelper = function() {
            return new HtmlHelper($this->app);
        };

        $this->app->container->twig = function($container) {
            $twigLoader = new Twig_Loader_Filesystem(dirname(dirname(dirname(__FILE__))).'/views');

            $twig = new Twig_Environment($twigLoader, [
                //'cache' => '../cache/twig',
                'autoescape' => false
            ]);

            $assetFunction = new Twig_SimpleFunction('asset', function($fileName) {
                return $this->app->container->mainUrl.'assets/'.$fileName;
            });
            $urlFunction = new Twig_SimpleFunction('url', function($name, $parameters = []) {
                $container = $this->app->container;
                return $container->mainUrl.'index.php/'.$container->generator->generate($name, $parameters, UrlGenerator::RELATIVE_PATH); // TODO: Embrace this brothel
            });
            $getFlashFunction = new Twig_SimpleFunction('getFlash', function($name) use($container) {
                return $container->flash->get($name);
            });

            $twig->addFunction($assetFunction);
            $twig->addFunction($urlFunction);
            $twig->addFunction($getFlashFunction);

            $twig->addExtension(new HtmlHelper($this->app));

            return $twig;
        };
    }
}