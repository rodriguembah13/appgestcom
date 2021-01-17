<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentMethod')
            ->add('currency')
            ->add('order_key')
            ->add('total')
            ->add('refundTotal')
            ->add('shippingTotal')
            ->add('discountTotal')
            ->add('totalTax')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('date_paid')
            ->add('status')
            ->add('createdBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
