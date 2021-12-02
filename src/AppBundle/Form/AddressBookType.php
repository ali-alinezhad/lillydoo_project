<?php


namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class AddressBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr'  => ['class' => "form-control"]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr'  => ['class' => "form-control"]
            ])
            ->add('street', TextType::class, [
                'attr' => ['class' => "form-control"]
            ])
            ->add('zipCode', IntegerType::class, [
                'label' => 'Zip Cod',
                'attr'  => ['class' => "form-control"]
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => "form-control"]
            ])
            ->add('country', TextType::class, [
                'attr' => ['class' => "form-control"]
            ])
            ->add('phoneNumber', IntegerType::class, [
                'label' => 'Phone Number',
                'attr'  => ['class' => "form-control"]
            ])
            ->add('birthday', DateType::class, [
                'attr' => ['class' => "form-control"]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => "form-control"]
            ])
            ->add('picture', FileType::class, [
                'required'    => false,
                'attr'        => ['class' => "form-control", 'onchange' => "readURL(this);"],
                'data_class'  => null,
                'constraints' => [
                    new File([
                        'maxSize'          => '2048k',
                        'mimeTypes'        => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('save', SubmitType::class)
            ->getForm();
    }
}
