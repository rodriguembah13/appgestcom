<?php

namespace App\Form;

use App\Entity\Fournisseur;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('sku')
            ->add('on_sale')
            ->add('supplier', EntityType::class, [
        'class' => Fournisseur::class,
        'multiple' => false,
        'placeholder' => 'product.table.supply',
        'translation_domain' => 'product',
        'label' => 'product.table.supply',
        'required' => true,
        'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
    ])
            ->add('is_decomposable')
            ->add('stockQuantity')
            ->add('salePrice')
            ->add('minQuantitySale')
            ->add('maxQuantitySale')
            ->add('regularPrice')
            ->add('description')
            ->add('productCategories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
