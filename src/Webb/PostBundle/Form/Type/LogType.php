<?php
/**
 * src/Webb/PostBundle/Form/Type/LogType.php
 */

namespace Webb\PostBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('log', null, array('label' => 'note.log', 'translation_domain' => 'WebbPostBundle', 'required' => false));
    }

    public function getName()
    {
        return 'log';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\PostBundle\Entity\Log',
        ));
    }
}
