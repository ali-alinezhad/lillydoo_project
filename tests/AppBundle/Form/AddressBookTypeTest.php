<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\AddressBookType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\File;


class AddressBookTypeTest extends WebTestCase
{
    public function testBuildForm(): void
    {
        $addressBookType = new AddressBookType();
        $builder         = $this->createMock(FormBuilderInterface::class);
        $form            = $this->createMock(FormInterface::class);

        $builder->expects(static::exactly(11))
            ->method('add')
            ->withConsecutive(
                [
                    'firstName', TextType::class,
                    [
                        'label' => 'First Name',
                        'attr'  => ['class' => "form-control"]
                    ]
                ],
                [
                    'lastName', TextType::class,
                    [
                        'label' => 'Last Name',
                        'attr'  => ['class' => "form-control"]
                    ]
                ],
                [
                    'street', TextType::class,
                    [
                        'attr' => ['class' => "form-control"]
                    ]
                ],
                [
                    'zipCode', IntegerType::class,
                    [
                        'label' => 'Zip Cod',
                        'attr'  => ['class' => "form-control"]
                    ]
                ],
                [
                    'city', TextType::class,
                    [
                        'attr' => ['class' => "form-control"]
                    ]
                ],
                [
                    'country', TextType::class,
                    [
                        'attr' => ['class' => "form-control"]
                    ]
                ],
                [
                    'phoneNumber', IntegerType::class,
                    [
                        'label' => 'Phone Number',
                        'attr'  => ['class' => "form-control"]
                    ]
                ],
                [
                    'birthday', DateType::class,
                    [
                        'attr' => ['class' => "form-control"]
                    ]
                ],
                [
                    'email', EmailType::class,
                    [
                        'attr' => ['class' => "form-control"]
                    ]
                ],
                [
                    'picture', FileType::class,
                    [
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
                    ]
                ],
                [
                    'save', SubmitType::class
                ]
            )
            ->willReturnSelf();

        $builder->expects(static::once())
            ->method('getForm')
            ->willReturn($form);

        $addressBookType->buildForm($builder, []);
    }
}
