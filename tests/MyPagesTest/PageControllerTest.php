<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyPagesTest;

use MyPages\Module;
use MyPages\PageController;
use PHPUnit_Framework_TestCase;
use Zend\Http\Request;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;

class PageControllerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function testControllerIsInstantiable()
    {
        $controller = new PageController(array());

        $this->assertInstanceOf('\MyPages\PageController', $controller);

        return $controller;
    }

    /**
     * @depends testControllerIsInstantiable
     */
    public function testCanDispatch(PageController $controller)
    {
        $sm = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $mm = $this->getMock('\Zend\ModuleManager\ModuleManager', array(), array(array()));

        $mm->expects($this->once())
            ->method('getModule')
            ->with($this->equalTo('MyPages'))
            ->will($this->returnValue(new Module()));

        $sm->expects($this->once())
            ->method('get')
            ->with($this->equalTo('ModuleManager'))
            ->will($this->returnValue($mm));
        $controller->setServiceLocator($sm);

        $request    = new Request();
        $routeMatch = new RouteMatch(array('controller' => 'page', 'page' => 'test'));
        $event      = new MvcEvent();

        $event->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $controller->dispatch($request);
        $result = $this->event->getResult();

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertEquals('pages/test', $result->getTemplate());
    }
}
