<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Sponsor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomev')
            ->add('description')
            ->add('datedeb')
            ->add('datefin')
            ->add('sponsor',EntityType::class,['class'=>Sponsor::class,'choice_label'=>'nomSociete','multiple'=>true,'expanded'=>true])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de l event (des images uniquement)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
