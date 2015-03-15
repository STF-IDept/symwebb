<?php

namespace Webb\CharacterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('posision', null, array('label' => 'assignment.posision', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('rostered', null, array('label' => 'assignment.rostered', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('startdate', null, array('label' => 'assignment.startdate', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('enddate', null, array('label' => 'assignment.enddate', 'translation_domain' => 'WebbCharacterBundle'));
    }

    public function getName()
    {
        return 'assignment';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\CharacterBundle\Entity\Assignment',
        ));
    }
}
