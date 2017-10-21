<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaAdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request = null)
    {
        $this->admin->checkAccess('create');

        $pool = $this->get('sonata.media.pool');

        $provider = $request->get('provider');
        $providers = $pool->getProvidersByContext(
            $request->get('context', $pool->getDefaultContext())
        );

        // if the context has only one provider, set it into the request
        // so the intermediate provider selection is skipped
        if (count($providers) == 1 && null === $provider) {
            $provider = array_shift($providers)->getName();
            $this->getRequest()->query->set('provider', $provider);
        }

        if (!$request->get('provider') && $request->isMethod('get')) {
            return $this->render('SonataMediaBundle:MediaAdmin:select_provider.html.twig', array(
                'providers' => $providers,
                'action' => 'create',
            ));
        }

        return parent::createAction();
    }

    /**
     * {@inheritdoc}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['media_pool'] = $this->get('sonata.media.pool');
        $parameters['persistent_parameters'] = $this->admin->getPersistentParameters();

        return parent::render($view, $parameters, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function listAction(Request $request = null)
    {
        $this->admin->checkAccess('list');

        if ($listMode = $request->get('_list_mode', 'mosaic')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();

        $filters = $request->get('filter');

        // set the default context
        if (!$filters || !array_key_exists('context', $filters)) {
            $context = $this->admin->getPersistentParameter('context', $this->get('sonata.media.pool')->getDefaultContext());
        } else {
            $context = $filters['context']['value'];
        }

        $datagrid->setValue('context', null, $context);

        $rootCategory = null;
        if ($this->has('sonata.media.manager.category')) {
            $rootCategory = $this->get('sonata.media.manager.category')->getRootCategory($context);
        }

        if (null !== $rootCategory && !$filters) {
            $datagrid->setValue('category', null, $rootCategory->getId());
        }
        if ($this->has('sonata.media.manager.category') && $request->get('category')) {
            $category = $this->get('sonata.media.manager.category')->findOneBy(array(
                'id' => (int) $request->get('category'),
                'context' => $context,
            ));

            if (!empty($category)) {
                $datagrid->setValue('category', null, $category->getId());
            } else {
                $datagrid->setValue('category', null, $rootCategory->getId());
            }
        }

        $formView = $datagrid->getForm()->createView();

        $this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action' => 'list',
            'form' => $formView,
            'datagrid' => $datagrid,
            'root_category' => $rootCategory,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ));
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     *
     * @param FormView $formView
     * @param string   $theme
     */
    private function setFormTheme(FormView $formView, $theme)
    {
        $twig = $this->get('twig');

        // BC for Symfony < 3.2 where this runtime does not exists
        if (!method_exists('Symfony\Bridge\Twig\AppVariable', 'getToken')) {
            $twig->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
                ->renderer->setTheme($formView, $theme);

            return;
        }
        $twig->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')->setTheme($formView, $theme);
    }
}
