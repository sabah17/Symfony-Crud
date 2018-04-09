<?php
namespace SM\FormulaireBundle\Form;

use SM\FormulaireBundle\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom',TextType::class)
            ->add('age',NumberType::class)
            ->add('genre',ChoiceType::class,array(
                'choices'=>array(
                    'H'=>'Homme',
                    'F'=>'Femme',
                ),'expanded' => true))
            ->add('nationalite',ChoiceType::class,array(
                'choices'=>array(
                    'MA'=>'Maroc',
                    'Fr'=>'France',
                    'IT'=>'Itaty'
                )));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Membre::class,
        ));
    }
}