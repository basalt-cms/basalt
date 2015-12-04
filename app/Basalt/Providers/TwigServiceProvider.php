<?php

namespace Basalt\Providers;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Basalt\Helpers\HtmlHelper;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

class TwigServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function provide(){
        $this->container->htmlHelper = function() {
            return new HtmlHelper($this->container->flash, $this->container->urlHelper);
        };

        $this->container->twig = function() {
            $twigLoader = new Twig_Loader_Filesystem(dirname(dirname(dirname(__FILE__))).'/views');

            $twig = new Twig_Environment($twigLoader, [
                //'cache' => '../cache/twig',
                'autoescape' => false
            ]);

            $assetFunction = new Twig_SimpleFunction('asset', function($fileName) {
                return $this->container->urlHelper->mainUrl().'assets/'.$fileName;
            });
            $urlFunction = new Twig_SimpleFunction('url', function($name, $parameters = []) {
                return $this->container->urlHelper->toRoute($name, $parameters);
            });

            $twig->addFunction($assetFunction);
            $twig->addFunction($urlFunction);

            $twig->addExtension(new HtmlHelper($this->container->flash, $this->container->urlHelper));

            return $twig;
        };
    }
}
