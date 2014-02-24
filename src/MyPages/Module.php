<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyPages;

use Zend\ModuleManager\Feature;
use Zend\Mvc\Controller\ControllerManager;
use MyBase\AbstractModule;

class Module extends AbstractModule implements
    Feature\ConfigProviderInterface,
    Feature\ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return [
            __NAMESPACE__ => [
                'route_param_name' => 'page',
                'template_dir' => 'pages',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getControllerConfig()
    {
        $module = $this;

        return [
            'factories' => [
                __NAMESPACE__ . '\PageController' => function (ControllerManager $cm) use ($module) {
                    $config = $cm->getServiceLocator()->get('config')[__NAMESPACE__];

                    return new PageController($config['route_param_name'], $config['template_dir']);
                }
            ],
            'aliases' => [
                'page' => __NAMESPACE__ . '\PageController',
            ],
        ];
    }
}
