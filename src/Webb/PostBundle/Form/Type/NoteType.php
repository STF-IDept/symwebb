<?php
/**
 * src/Webb/PostBundle/Form/Type/NoteType.php
 */

namespace Webb\PostBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('location', null, array('label' => 'note.location', 'translation_domain' => 'WebbPostBundle'));
        $builder->add('activity', null, array('label' => 'note.activity', 'translation_domain' => 'WebbPostBundle'));
        $builder->add('assignment', null, array('label' => 'note.assignment', 'translation_domain' => 'WebbPostBundle'));
        $builder->add('content', null, array('label' => 'note.content', 'translation_domain' => 'WebbPostBundle'));
    }

    public function getName()
    {
        return 'note';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\PostBundle\Entity\Note',
        ));
    }
}