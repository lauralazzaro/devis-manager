<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Quote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Enum\QuoteStatus;
use App\Form\QuoteLineType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quoteNumber')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('status', EnumType::class, [
                'class' => QuoteStatus::class,
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'name',
            ])
            ->add('quoteLines', CollectionType::class, [
                'entry_type' => QuoteLineType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }
}
