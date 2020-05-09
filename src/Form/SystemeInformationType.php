<?php

namespace App\Form;

use App\Entity\SystemeInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemeInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usualName')
            ->add('siiName')
            ->add('description')
            ->add('confidentialite')
            ->add('domaine')
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemeInformation::class,
        ]);
    }
}
