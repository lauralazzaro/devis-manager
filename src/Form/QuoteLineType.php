<?php

namespace App\Form;

use App\Entity\QuoteLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for a single quote line item.
 * Used inside QuoteType as a collection entry.
 */
class QuoteLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Description'],
            ])
            ->add('quantity', NumberType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Qty'],
            ])
            ->add('unitPrice', NumberType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Unit Price'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteLine::class,
        ]);
    }
}
