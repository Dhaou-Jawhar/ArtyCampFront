<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,['attr'=>['placeholder'=>'nom','class'=>'form-control','class'=>'ab']])
            ->add('prenom',null,['attr'=>['placeholder'=>'prenom','class'=>'form-control','class'=>'ab']])
            ->add('username',null,['attr'=>['placeholder'=>'username','class'=>'form-control','class'=>'ab']])
            ->add('password', PasswordType::class,['attr'=>['placeholder'=>'password','class'=>'form-control','class'=>'ab']])

            ->add('mail',null,['attr'=>['placeholder'=>'mail','class'=>'form-control','class'=>'ab']])
            ->add('role',ChoiceType::class, array( 'choices'=>array('Artiste'=>'Artiste','Visiteur'=>'Visiteur','Artist NFT'=>'Artist NFT')
            ))
           // ->add('image')
           ->add('image', FileType::class, [
               'label' => 'choose photo Profile',

               // unmapped means that this field is not associated to any entity property
               'mapped' => false,

               // every time you edit the Product details
               'required' => false,


               // unmapped fields can't define their validation using annotations
               // in the associated entity, so you can use the PHP constraint classes
               'constraints' => [
                   new File([
                       'maxSize' => '1024k',
                       'mimeTypes' => [
                           'image/jpeg',
                           'image/gif',
                           'image/jpg',
                           'image/png',
                       ],
                       'mimeTypesMessage' => 'Please upload a valid Image document',
                   ])
               ],
           ])
            ->add('captcha',RecaptchaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
