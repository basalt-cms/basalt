<?php

namespace Basalt\Providers;

use Basalt\Auth\Authenticator;
use Basalt\Container;
use Basalt\Database\UserMapper;
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

        $this->container->twig = function(Container $container) {
            $twigLoader = new Twig_Loader_Filesystem(dirname(dirname(dirname(__FILE__))).'/views');

            $twig = new Twig_Environment($twigLoader, [
                //'cache' => '../cache/twig',
                'autoescape' => false
            ]);

            $isLoggedInFunction = new Twig_SimpleFunction('isLoggedIn', function() use($container) {
                $userMapper = new UserMapper($container->pdo);
                $authenticator = new Authenticator($userMapper);

                return $authenticator->isLoggedIn();
            });

            $assetFunction = new Twig_SimpleFunction('asset', function($fileName) {
                return $this->container->urlHelper->mainUrl().'assets/'.$fileName;
            });
            $urlFunction = new Twig_SimpleFunction('url', function($name, $parameters = []) {
                return $this->container->urlHelper->toRoute($name, $parameters);
            });

            $twig->addFunction($assetFunction);
            $twig->addFunction($urlFunction);
            $twig->addFunction($isLoggedInFunction);

            $twig->addExtension(new HtmlHelper($this->container->flash, $this->container->urlHelper));

            return $twig;
        };
    }
}
