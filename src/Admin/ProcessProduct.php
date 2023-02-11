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
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class ProcessProduct extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('process', ModelListType::class);
        $form->add('product', ModelListType::class);
        $form->add('currentPrice', MoneyType::class, ['currency' => 'Lei']);
        $form->add('updatedPrice', MoneyType::class, ['currency' => 'Lei']);
        $form->add('status', TextType::class);
        $form->add('reason', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id');
        $datagrid->add('process.id');
        $datagrid->add('product.id');
        $datagrid->add('currentPrice');
        $datagrid->add('updatedPrice');
        $datagrid->add('status');
        $datagrid->add('reason');
        $datagrid->add('dateUpdated');
        $datagrid->add('dateCreated');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->addIdentifier('process.id');
        $list->addIdentifier('product.id');
        $list->addIdentifier('currentPrice');
        $list->addIdentifier('updatedPrice');
        $list->addIdentifier('status');
        $list->addIdentifier('reason');
        $list->addIdentifier('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $list->addIdentifier('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('process.id');
        $show->add('product.id');
        $show->add('currentPrice');
        $show->add('updatedPrice');
        $show->add('status');
        $show->add('reason');
        $show->add('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $show->add('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }
}