<?php
/**
 * code borrowed from https://github.com/zendframework/zf-web
 *
 * @author Matthew Weier O'Phinney <matthew@weierophinney.net>
 * ************************************************
 */

namespace MyPages;

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
     * @var string
     */
    protected $routeParamName;

    /**
     * @var string
     */
    protected $templateDir;

    /**
     * @param string|null $routeParamName
     * @param string|null $templateDir
     */
    public function __construct($routeParamName = null, $templateDir = null)
    {
        $this->routeParamName = $routeParamName;
        $this->templateDir = $templateDir;
    }

    /**
     * @param  string         $routeParamName
     * @return PageController
     */
    public function setRouteParamName($routeParamName)
    {
        $this->routeParamName = $routeParamName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRouteParamName()
    {
        return $this->routeParamName;
    }

    /**
     * @param  string         $templateDir
     * @return PageController
     */
    public function setTemplateDir($templateDir)
    {
        $this->templateDir = $templateDir;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateDir()
    {
        return $this->templateDir;
    }

    /**
     * @param  ResolverInterface $resolver
     * @return PageController
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

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
     * @param  MvcEvent  $e
     * @return ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $matches    = $e->getRouteMatch();
        $page       = $matches->getParam($this->routeParamName, null);
        $template   = $this->templateDir ? $this->templateDir .'/'. $page : $page;

        if (!$page || !$this->getResolver()->resolve($template)) {
            return $this->notFoundAction();
        }

        $model = new ViewModel();
        $model->setTemplate($template);
        $e->setResult($model);

        return $model;
    }
}
