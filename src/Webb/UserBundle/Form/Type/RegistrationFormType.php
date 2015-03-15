<?php
/**
 * src/Webb/UserBundle/Form/Type/RegistrationFormType.php
 */

namespace Webb\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('first_name', null, array('label' => 'form.firstname', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('surname', null, array('label' => 'form.surname', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('application', new ApplicationType());
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Webb\UserBundle\Entity\User',
        );
    }

    public function getName()
    {
        return 'webb_user_registration';
    }
}
