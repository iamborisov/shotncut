<?php

namespace AppBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

abstract class ContentAdmin extends AbstractAdmin
{
    protected $group = '';

    protected $richEdit = true;

    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $query */
        $query = parent::createQuery($context);

        $query->andWhere(
            $query->expr()->like($query->getRootAliases()[0] . '.name', ':group')
        );
        $query->setParameter('group', $this->group ? $this->group . '.%' : '%');

        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text',  [
            'attr' => [
                'readonly' => true
            ]
        ]);

        if ($this->richEdit) {
            $formMapper->add('value', 'sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'attr'   => ['class' => 'ckeditor'],
            ]);
        } else {
            $formMapper->add('value', 'text');
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', null, [
            'header_style' => 'width: 30%',
        ]);
        $listMapper->addIdentifier('value', null, [
            'header_style' => 'width: 70%',
        ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
    }
}