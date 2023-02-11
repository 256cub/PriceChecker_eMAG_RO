<?php

namespace App\Admin;

use Doctrine\DBAL\Types\FloatType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class Process extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('status', TextType::class);
        $form->add('report', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id');
        $datagrid->add('status');
        $datagrid->add('report');
        $datagrid->add('dateUpdated');
        $datagrid->add('dateCreated');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->addIdentifier('status');
        $list->addIdentifier('report');
        $list->addIdentifier('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $list->addIdentifier('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('status');
        $show->add('report');
        $show->add('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $show->add('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }
}