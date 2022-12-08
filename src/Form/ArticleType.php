<?php

namespace App\Form;

use App\Entity\ArticleArtiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nomA',TextType::class,['label'=>'Nom'])
            ->add('descriptionA',TextareaType::class,['label'=>'Description'])

                 ->add('imageFile', VichImageType::class, [
                    'label'=>'Image (JPG or PNG fichier)',
                     'required' => false,
                     'allow_delete' => false,
                 'download_uri' => false,
                     'imagine_pattern'=>'squared_thumbnail_small'
             ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleArtiste::class,
        ]);
    }
}
