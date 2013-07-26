<?php
/**
 * code borrowed from https://github.com/zendframework/zf-web
 *
 * @author Matthew Weier O'Phinney <matthew@weierophinney.net>
 * ************************************************
 */

namespace MyPages;

use InvalidArgumentException;
use Traversable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\ResolverInterface;

class PageController extends AbstractActionController
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @return ResolverInterface
     */
    public function getResolver()
    {
        if (!$this->resolver) {
            $this->resolver = $this->getServiceLocator()->get('ViewResolver');
        }

        return $this->resolver;
    }

    /**
     * @param $option
     * @return mixed
     */
    public function getOption($option)
    {
        /** @var Module $module */
        $module = $this->getServiceLocator()->get('ModuleManager')->getModule(__NAMESPACE__);

        return $module->getOption($option);
    }

    /**
     * Listen to dispatch event
     *
     * Retrieves "page" parameter from route matches. If none found, assumes
     * a 404 status code and page.
     *
     * Checks to see if the retrieved page can be resolved by the resolver. If
     * not, assumes a 404 code and page.
     *
     * Otherwise, returns a view model with a template matching the page from
     * this module.
     *
     * @param  MvcEvent $e
     * @return ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $matches    = $e->getRouteMatch();
        $page       = $matches->getParam($this->getOption('route_param'), null);
        $template   = $this->getOption('template_dir') .'/'. $page;

        if (!$page || !$this->getResolver()->resolve($template)) {
            return $this->notFoundAction();
        }

        $model = new ViewModel();
        $model->setTemplate($template);
        $e->setResult($model);

        return $model;
    }
}
