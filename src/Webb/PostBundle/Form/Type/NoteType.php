<?php
/**
 * src/Webb/PostBundle/Form/Type/NoteType.php
 */

namespace Webb\PostBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('location', null, array('label' => 'note.location', 'translation_domain' => 'WebbPostBundle'));
        $builder->add('activity', null, array('label' => 'note.activity', 'translation_domain' => 'WebbPostBundle'));
        $builder->add('assignment', 'entity', array('label' => 'note.assignment', 'translation_domain' => 'WebbPostBundle', 'class' => 'WebbCharacterBundle:Assignment',
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('assignment')
                    ->select('assignment, position, parent, persona, rank')
                    ->innerJoin('assignment.position', 'position')
                    ->innerJoin('position.parent', 'parent')
                    ->innerJoin('assignment.persona', 'persona')
                    ->innerJoin('persona.rank', 'rank')
                    ->where('position.ship = :ship_id')
                    ->andWhere('persona.user = :user_id')
                    ->setParameter('ship_id', $options['ship'])
                    ->setParameter('user_id', $options['user']);
            },
    ));


        $builder->add('content', null, array('label' => false, 'translation_domain' => 'WebbPostBundle'));
        $builder->add('log', new LogType(), array('label' => false));
    }

    public function getName()
    {
        return 'note';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\PostBundle\Entity\Note',
            'ship' => false,
            'user' => false,
        ));
    }
}