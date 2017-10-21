<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class GalleryAdmin extends AbstractAdmin
{
    protected $datagridValues = array(
        '_sort_by' => 'position',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('display', 'checkbox');

        $formMapper->add('name', 'text');

        $formMapper->add('photo', 'sonata_type_model_list', [], [
            'link_parameters' => ['context' => 'default']
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', null, [
            'header_style' => 'width: 95%',
        ]);
        $listMapper->add('_action', null, [
            'header_style' => 'width: 5%; min-width: 150px',
            'actions' => [
                'move' => [
                    'template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig'
                ],
            ]
        ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
    }
}