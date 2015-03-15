<?php
/**
 * src/Webb/UserBundle/Form/Type/ApplicationType.php
 */

namespace Webb\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder->add('activity_rate', 'choice', array(
            'choices' => array(
                'empty_value' => 'Choose an option',
                'Infrequent' => 'Infrequent (once per week or less)',
                'Slow' => 'Slow (1-3 logins per week)',
                'Average' => 'Average (2-5 logins per week)',
                'Regular' => 'Regular (4-7 logins per week)',
                'Frequently' => 'Frequently (daily or almost daily)',
            ),
            'label' => 'form.activityrate', 'translation_domain' => 'WebbUserBundle'
        ));

        $builder->add('academy_ship', 'choice', array(
            'choices' => array(
                'empty_value' => 'Choose an option',
                'Yes' => 'Yes - sign me up for an academy ship as a cadet',
                'No' => 'No - put me directly on a regular ship as an ensign',
            ),
            'label' => 'form.academyship', 'translation_domain' => 'WebbUserBundle'
        ));

        $builder->add('mentor_request', 'choice', array(
            'choices' => array(
                'empty_value' => 'Choose an option',
                'Yes' => 'Yes - I would like to have a mentor',
                'No' => 'No - I do not need a mentor',
            ),
            'label' => 'form.mentorrequest', 'translation_domain' => 'WebbUserBundle'
        ));

        $builder->add('position_first', 'choice', array(
            'choices' => array(
                'empty_value' => 'Choose an option',
                'Engineer' => 'Engineer Officer',
                'Medical' => 'Medical Officer',
                'Science' => 'Science Officer',
                'Security' => 'Security Officer',
            ),
            'label' => 'form.positionfirst', 'translation_domain' => 'WebbUserBundle'
        ));

        $builder->add('position_second', 'choice', array(
            'choices' => array(
                'empty_value' => 'Choose an option',
                'Engineer' => 'Engineer Officer',
                'Medical' => 'Medical Officer',
                'Science' => 'Science Officer',
                'Security' => 'Security Officer',
            ),
            'label' => 'form.positionsecond', 'translation_domain' => 'WebbUserBundle'
        ));

        $builder->add('position_third', 'choice', array(
            'choices' => array(
                'empty_value' => 'Choose an option',
                'Engineer' => 'Engineer Officer',
                'Medical' => 'Medical Officer',
                'Science' => 'Science Officer',
                'Security' => 'Security Officer',
            ),
            'label' => 'form.positionthird', 'translation_domain' => 'WebbUserBundle'
        ));

        $builder->add('hear_about', null, array('label' => 'form.hearabout', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('character_name', null, array('label' => 'form.charactername', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('character_species', null, array('label' => 'form.characterspecies', 'translation_domain' => 'WebbUserBundle'));
        $builder->add('comments', null, array('label' => 'form.comments', 'translation_domain' => 'WebbUserBundle'));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Webb\UserBundle\Entity\Application',
        );
    }

    public function getName()
    {
        return 'application';
    }
}
