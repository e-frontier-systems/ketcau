<?php

namespace Ketcau\Form\Type;

use Ketcau\Common\KetcauConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class NameType extends AbstractType
{
    protected $ketcauConfig;


    public function __construct(KetcauConfig $ketcauConfig)
    {
        $this->ketcauConfig = $ketcauConfig;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['lastname_options']['required'] = $options['required'];
        $options['firstname_options']['required'] = $options['required'];

        if ($options['required']) {
            $options['lastname_options']['constraints'] = array_merge([
                new Assert\NotBlank(),
            ], $options['lastname_options']['constraints']);
            $options['firstname_options']['constraints'] = array_merge([
                new Assert\NotBlank(),
            ], $options['firstname_options']['constraints']);
        }

        if (!isset($options['options']['error_bubbling'])) {
            $options['options']['error_bubbling'] = $options['error_bubbling'];
        }

        if (empty($options['lastname_name'])) {
            $options['lastname_name'] = $builder->getName(). '01';
        }
        if (empty($options['firstname_name'])) {
            $options['firstname_name'] = $builder->getName(). '02';
        }

        $builder
            ->add($options['lastname_name'], TextType::class, array_merge_recursive($options['options'], $options['lastname_options']))
            ->add($options['firstname_name'], TextType::class, array_merge_recursive($options['options'], $options['firstname_options']))
        ;

        $builder->setAttribute('lastname_name', $options['lastname_name']);
        $builder->setAttribute('firstname_name', $options['firstname_name']);
    }


    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $builder = $form->getConfig();
        $view->vars['lastname_name'] = $builder->getAttribute('lastname_name');
        $view->vars['firstname_name'] = $builder->getAttribute('firstname_name');
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'options' => [],
            'lastname_options' => [
                'attr' => [
                    'placeholder' => 'common.last_name',
                ],
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->ketcauConfig['ketcau_name_len'],
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[^\s ]+$/u',
                        'message' => 'form_error.not_contain_spaces',
                    ]),
                ],
            ],
            'firstname_options' => [
                'attr' => [
                    'placeholder' => 'common.first_name',
                ],
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->ketcauConfig['ketcau_name_len'],
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[^\s ]+$/u',
                        'message' => 'form_error.not_contain_spaces',
                    ]),
                ],
            ],
            'lastname_name' => '',
            'firstname_name' => '',
            'error_bubbling' => false,
            'inherit_data' => true,
            'trim' => true,
        ]);
    }


    public function getBlockPrefix()
    {
        return 'name';
    }
}