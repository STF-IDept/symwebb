<?php
/**
 * src/Webb/ShipBundle/Form/Type/ShipType.php
 */

namespace Webb\ShipBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'ship.name', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('shortname', null, array('label' => 'ship.shortname', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('type', null, array('label' => 'ship.type', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('speed', null, array('label' => 'ship.speed', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('acceptsNew', null, array('label' => 'ship.acceptsNew', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('fleet', null, array('label' => 'ship.fleet', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('description', null, array('label' => 'ship.description', 'translation_domain' => 'WebbShipBundle'));
    }

    public function getName()
    {
        return 'ship';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\ShipBundle\Entity\Ship',
        ));
    }
}