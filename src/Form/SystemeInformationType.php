<?php

namespace App\Form;

use App\Entity\SystemeInformation;
use App\Entity\Confidentialite;
use App\Entity\Domaine;
use App\Entity\TypologyMI;
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
            ->add('confidentialite', null, ['choice_label' => 'confidentialiteName'])
            ->add('domaine', null, ['choice_label' => 'DomaineName'])
            ->add('type', null, ['choice_label' => 'shortName'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemeInformation::class,
        ]);
    }
}
