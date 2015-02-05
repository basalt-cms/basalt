<?php

namespace Basalt\Providers;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Basalt\Helpers\HtmlHelper;

class TwigServiceProvider extends ServiceProvider
{
    public function provide(){
        $this->app->container->htmlHelper = function() {
            return new HtmlHelper($this->app);
        };

        $this->app->container->twig = function($container) {
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
            $getFlashFunction = new \Twig_SimpleFunction('getFlash', function($name) use($container) {
                return $container->flash->get($name);
            });
            $formFunction = new \Twig_SimpleFunction('form', function($route, $parameters = []) use($container) {
                return $container->htmlHelper->form($route, $parameters);
            });
            $endFormFunction = new \Twig_SimpleFunction('endForm', function() use($container) {
                return $container->htmlHelper->endForm();
            });

            $twig->addFilter($assetFilter);
            $twig->addFilter($urlFilter);
            $twig->addFunction($getFlashFunction);
            $twig->addFunction($formFunction);
            $twig->addFunction($endFormFunction);

            return $twig;
        };
    }
}