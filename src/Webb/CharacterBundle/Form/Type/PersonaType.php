<?php

namespace Webb\CharacterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Webb\FileBundle\Form\Type\ImageType as ImageType;

class PersonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('rank', null, array('label' => 'persona.rank', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('name', null, array('label' => 'persona.name', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('species', null, array('label' => 'persona.species', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('weight', null, array('label' => 'persona.weight', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('height', null, array('label' => 'persona.height', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('age', null, array('label' => 'persona.age', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('image', new ImageType, array('label' => 'persona.image', 'translation_domain' => 'WebbCharacterBundle'));
        $builder->add('bio', null, array('label' => 'persona.bio', 'translation_domain' => 'WebbCharacterBundle'));
    }

    public function getName()
    {
        return 'persona';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\CharacterBundle\Entity\Persona',
        ));
    }
}