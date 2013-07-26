<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyPages;

use Zend\ModuleManager\Feature;
use Zend\Mvc\Controller\ControllerManager;
use ZfcBase\Module\AbstractModule;

class Module extends AbstractModule implements
    Feature\ConfigProviderInterface,
    Feature\ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return array(
            __NAMESPACE__ => array(
                'options' => array(
                    'route_param' => 'page',
                    'template_dir' => 'pages',
                )
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDir()
    {
        return __DIR__ . '/../..';
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * {@inheritdoc}
     */
    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                __NAMESPACE__ . '\PageController' => __NAMESPACE__ . '\PageController',
            ),
            'aliases' => array(
                'page' => __NAMESPACE__ . '\PageController',
            ),
        );
    }
}
