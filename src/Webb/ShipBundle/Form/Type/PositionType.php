<?php

namespace Webb\ShipBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('positions', 'collection', array(
                'type'   => new PositionRowType(),
                'allow_add' => false,
                'allow_delete' => false,
                'by_reference' => false
            ));
        $builder->add('save', 'submit');
    }

    public function getName()
    {
        return 'position';
    }
}
