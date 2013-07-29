<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyPagesTest;

use MyPages\PageController;
use PHPUnit_Framework_TestCase;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Resolver\TemplateMapResolver;

class PageControllerTest extends PHPUnit_Framework_TestCase
{

    public function testConstructorAndAccessors()
    {
        $controller = new PageController();

        $this->assertInstanceOf('\MyPages\PageController', $controller);

        $controller = new PageController('page', 'pages');
        $this->assertEquals('page', $controller->getRouteParamName());
        $this->assertEquals('pages', $controller->getTemplateDir());

        $controller->setRouteParamName('foo')->setTemplateDir('bar');
        $this->assertEquals('foo', $controller->getRouteParamName());
        $this->assertEquals('bar', $controller->getTemplateDir());
    }

    public function testLazyLoadingResolverFromServiceManager()
    {
        $controller = new PageController();

        $resolver = self::templateResolverFactory();

        $sm = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $sm->expects($this->once())
            ->method('get')
            ->with($this->equalTo('ViewResolver'))
            ->will($this->returnValue($resolver));
        $controller->setServiceLocator($sm);

        $this->assertEquals($resolver, $controller->getResolver());
    }

    /**
     * @depends testConstructorAndAccessors
     */
    public function testCanDispatch()
    {
        $page = 'testPage';
        $controller = new PageController('testParam');
        $controller->setResolver(self::templateResolverFactory($page));

        $request    = new Request();
        $routeMatch = new RouteMatch(array($controller->getRouteParamName() => $page));
        $event      = new MvcEvent();

        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $controller->dispatch($request);
        $result = $event->getResult();

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertEquals($page, $result->getTemplate());

        return $controller;
    }

    /**
     * @depends testCanDispatch
     */
    public function testNotFoundAction(PageController $controller)
    {
        $request    = new Request();
        $routeMatch = new RouteMatch(array($controller->getRouteParamName() => 'idontexist'));
        $event      = new MvcEvent();

        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $controller->dispatch($request);
        $response = $event->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @depends testConstructorAndAccessors
     */
    public function testCanUseTemplateDir()
    {
        $page = 'testPage';
        $controller = new PageController('testParam', 'testDir');
        $fullTemplateName = $controller->getTemplateDir().'/'.$page;

        $controller->setResolver(self::templateResolverFactory($fullTemplateName));

        $request    = new Request();
        $routeMatch = new RouteMatch(array($controller->getRouteParamName() => $page));
        $event      = new MvcEvent();

        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $controller->dispatch($request);
        $result = $event->getResult();

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertEquals($fullTemplateName, $result->getTemplate());
    }

    public static function templateResolverFactory($page = null)
    {
        return new TemplateMapResolver(array(
            $page => __DIR__ . '/TestAssets/test.phtml'
        ));
    }
}
