<?php

namespace Webb\ShipBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PositionRowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('customlong', null, array('label' => 'position.customlong', 'translation_domain' => 'WebbShipBundle'));
        $builder->add('customshort', null, array('label' => 'position.customshort', 'translation_domain' => 'WebbShipBundle'));
    }

    public function getName()
    {
        return 'positionrow';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\ShipBundle\Entity\Position',
        ));
    }
}
