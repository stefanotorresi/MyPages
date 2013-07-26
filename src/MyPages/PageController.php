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
     * @var array|Traversable
     */
    protected $options;

    /**
     * @param $options
     * @throws InvalidArgumentException
     */
    public function __construct($options)
    {
        if (!is_array($options) && !$options instanceof Traversable) {
            throw new InvalidArgumentException(sprintf(
                'Expected array or Traversable object; received "%s"',
                (is_object($options) ? get_class($options) : gettype($options))
            ));
        }

        $this->options = $options;
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
     * @param  MvcEvent $e
     * @return ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $matches    = $e->getRouteMatch();
        $page       = $matches->getParam($this->options['route_param'], null);
        $template   = $this->options['template_dir'] .'/'. $page;

        if (!$page || !$this->getResolver()->resolve($template)) {
            return $this->notFoundAction();
        }

        $model = new ViewModel();
        $model->setTemplate($template);
        $e->setResult($model);

        return $model;
    }
}
