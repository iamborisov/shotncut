<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ProjectAdmin extends AbstractAdmin
{

    protected $datagridValues = array(
        '_sort_by' => 'position',
    );

    public function configure() {
        $this->setTemplate('edit', 'admin.html.twig');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('display', 'checkbox');

        $formMapper->add('name', 'text', [
            'attr' => ["class" => "js-url-src"]
        ]);

        $formMapper->add('url', 'text', [
            'attr' => ["class" => "js-url-dst"]
        ]);

        $formMapper->add('logo', 'sonata_type_model_list', [
            'required' => false,
        ], [
            'link_parameters' => ['context' => 'default']
        ]);

        $formMapper->add('annotation', 'sonata_simple_formatter_type', [
            'format' => 'richhtml',
            'ckeditor_context' => 'default'
        ]);

        $formMapper->add('video', 'sonata_type_model_list', [
            'required' => false,
        ], [
            'link_parameters' => ['context' => 'video']
        ]);

        $formMapper->add('description', 'sonata_simple_formatter_type', [
            'required' => false,
            'format' => 'richhtml',
            'ckeditor_context' => 'default'
        ]);

        $formMapper->add('photos', 'sonata_type_collection', [
            'required' => false
        ], [
            'edit' => 'inline',
            'inline' => 'table',
            'sortable'  => 'position',
        ]);

        $formMapper->add('tags', 'entity', [
            'class'     => 'AppBundle:ProjectType',
            'multiple'  => true,
            'required'  => false,
        ]);

        $formMapper->add('created', 'date', [
            'required' => false,
        ]);

        $formMapper->add('time', 'text', [
            'required' => false,
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

    public function prePersist($object)
    {
        foreach ($object->getPhotos() as $image) {
            $image->setProject($object);

            $this->getConfigurationPool()
                ->getContainer()
                ->get('doctrine')
                ->getManager()
                ->persist($image);
        }
    }

    public function preUpdate($object)
    {
        foreach ($object->getPhotos() as $image) {
            $image->setProject($object);

            $this->getConfigurationPool()
                ->getContainer()
                ->get('doctrine')
                ->getManager()
                ->persist($image);
        }
    }
}