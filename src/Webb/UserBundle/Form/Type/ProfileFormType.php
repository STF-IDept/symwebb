<?php
/**
 * src/Webb/UserBundle/Form/Type/ProfileFormType.php
 */

namespace Webb\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Webb\FileBundle\Form\Type\ImageType as ImageType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // We do not actually want to ask for the user password - The user has already used their password in this session.
        $builder->remove('current_password');

        $builder->add('first_name', null, array('label' => 'form.firstname', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('surname', null, array('label' => 'form.surname', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('image', new ImageType, array('label' => 'persona.image', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('bio', null, array('label' => 'persona.bio', 'translation_domain' => 'WebbCharacterBundle'));
    }

    public function getName()
    {
        return 'webb_user_profile';
    }
}