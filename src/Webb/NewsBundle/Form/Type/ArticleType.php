<?php
/**
 * src/Webb/NewsBundle/Form/Type/ArticleType.php
 */

namespace Webb\NewsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, array('label' => 'article.title', 'translation_domain' => 'WebbNewsBundle'));
        $builder->add('content', null, array('label' => 'article.content', 'translation_domain' => 'WebbNewsBundle'));
        $builder->add('tags', null, array('label' => 'article.tag', 'translation_domain' => 'WebbNewsBundle'));
    }

    public function getName()
    {
        return 'article';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webb\NewsBundle\Entity\Article',
        ));
    }
}
