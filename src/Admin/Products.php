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
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class Products extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('sku', TextType::class);
        $form->add('name', TextType::class);
        $form->add('url', UrlType::class);
        $form->add('minimPrice', MoneyType::class, ['currency' => 'Lei']);
        $form->add('companies', ModelListType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id');
        $datagrid->add('sku');
        $datagrid->add('name');
        $datagrid->add('url');
        $datagrid->add('minimPrice');
        $datagrid->add('companies.name');
        $datagrid->add('dateUpdated');
        $datagrid->add('dateCreated');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->addIdentifier('sku');
        $list->addIdentifier('name');
        $list->addIdentifier('url');
        $list->addIdentifier('minimPrice');
        $list->addIdentifier('companies.name');
        $list->addIdentifier('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $list->addIdentifier('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('sku');
        $show->add('name');
        $show->add('url');
        $show->add('minimPrice');
        $show->add('companies.name');
        $show->add('dateUpdated', 'datetime', ['format' => 'Y-m-d H:m:s']);
        $show->add('dateCreated', 'datetime', ['format' => 'Y-m-d H:m:s']);
    }
}