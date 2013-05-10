<?php
/**
 * src/Webb/ShipBundle/Form/Type/PersonaType.php
 */

namespace Webb\ShipBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FleetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'fleet.name', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('shortname', null, array('label' => 'fleet.shortname', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('description', null, array('label' => 'fleet.description', 'translation_domain' => 'WebbShipBundle'));
    }

    public function getName()
    {
        return 'fleet';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\ShipBundle\Entity\Fleet',
        ));
    }
}