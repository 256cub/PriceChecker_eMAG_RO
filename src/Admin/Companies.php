<?php

namespace App\Admin;

use Doctrine\DBAL\Types\FloatType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class Companies extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class);
        $form->add('url', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id');
        $datagrid->add('name');
        $datagrid->add('url');
        $datagrid->add('dateUpdated');
        $datagrid->add('dateCreated');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->addIdentifier('name');
        $list->addIdentifier('url');
        $list->addIdentifier('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $list->addIdentifier('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('name');
        $show->add('url');
        $show->add('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $show->add('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }
}