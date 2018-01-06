<?php
namespace Rcnchris\App\Controllers;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->getTwig()->getLoader()->addPath(dirname(__DIR__) . '/Views/Pages/');
        $this->getTwig()->getEnvironment()->addGlobal('appCharset', $this->getContainer()->get('app.charset'));
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

    public function render(ResponseInterface $response, $file, $params = [])
    {
        $this->getTwig()->render($response, $file . '.twig', $params);
    }

    public function phpRender(ResponseInterface $response, $file, $params = [])
    {
        $this->getContainer()->get('phpView')->render($response, $file, $params);
    }

    /**
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Obtenir l'instance de Twig
     *
     * @return \Slim\Views\Twig
     */
    public function getTwig()
    {
        return $this->container->get('view');
    }

    /**
     * Obtenir le chemin racine du projet
     *
     * @return string
     */
    public function rootPath()
    {
        return dirname(dirname(__DIR__));
    }
}