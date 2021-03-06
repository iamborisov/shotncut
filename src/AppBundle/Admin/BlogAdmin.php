<?php

namespace AppBundle\Admin;

use AppBundle\Entity\BlogPhoto;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BlogAdmin extends AbstractAdmin
{
    public function configure() {
        $this->setTemplate('edit', 'admin.html.twig');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('display', 'checkbox');

        $formMapper->add('title', 'text', [
            'attr' => ["class" => "js-url-src"]
        ]);

        $formMapper->add('url', 'text', [
            'attr' => ["class" => "js-url-dst"]
        ]);

        $formMapper->add('created', 'date', []);

        $formMapper->add('background', 'sonata_type_model_list', [
            'required' => false
        ], [
            'link_parameters' => ['context' => 'default']
        ]);

        $formMapper->add('annotation', 'sonata_simple_formatter_type', [
            'format' => 'richhtml',
            'ckeditor_context' => 'default'
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

        $formMapper->add('description2', 'sonata_simple_formatter_type', [
            'required' => false,
            'format' => 'richhtml',
            'ckeditor_context' => 'default'
        ]);

        $formMapper->add('video', 'sonata_type_model_list', [
            'required' => false
        ], [
            'link_parameters' => ['context' => 'video']
        ]);

        $formMapper->add('description3', 'sonata_simple_formatter_type', [
            'required' => false,
            'format' => 'richhtml',
            'ckeditor_context' => 'default'
        ]);

        $formMapper->add('metaTitle', 'text', [
            'required' => false,
        ]);

        $formMapper->add('metaDescription', 'text', [
            'required' => false,
        ]);

        $formMapper->add('metaKeywords', 'text', [
            'required' => false,
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', null, [
            'header_style' => 'width: 95%',
        ]);
    }

    public function prePersist($object)
    {
        foreach ($object->getPhotos() as $image) {
            if ($image->getPhoto() == null) {
                continue;
            }

            $image->setBlog($object);

            $this->getConfigurationPool()
                ->getContainer()
                ->get('doctrine')
                ->getManager()
                ->persist($image);
        }
    }

    public function preUpdate($object)
    {
        $manager = $this->getConfigurationPool()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        foreach ($object->getPhotos() as $i => $image) {
            if ($image->getPhoto()) {
                $image->setBlog($object);

                $manager->persist($image);
            } else {
                $object->getPhotos()->remove($i);

                $manager->remove($image);
            }
        }
    }

    public function preRemove($object)
    {
        /** @var BlogPhoto $image */
        foreach ($object->getPhotos() as $image) {
            $this->getConfigurationPool()
                ->getContainer()
                ->get('doctrine')
                ->getManager()
                ->remove($image);
        }
    }
}