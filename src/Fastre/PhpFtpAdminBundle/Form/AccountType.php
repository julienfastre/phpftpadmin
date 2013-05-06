<?php

namespace Fastre\PhpFtpAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountType extends AbstractType
{
    private $state;
    
    public function __construct($state = 'update') {
        $this->state = $state;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->state === 'new') {
            $builder->add('username');
        } else {
            $builder->add('username', 'text', array('read_only' => true));
        }
        
        $builder->add('pass', 'repeated', 
                array(
                    'type' => 'password',
                    'required' => true,
                    'first_options' =>
                        array(
                             'label' => 'Mot de passe'
                            ),
                    'second_options' => 
                        array('label' => 'Répétez votre mot de passe')
                    
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fastre\PhpFtpAdminBundle\Entity\Account'
        ));
    }

    public function getName()
    {
        return 'fastre_phpftpadminbundle_accounttype';
    }
}
