<?php


namespace Ram34\CityFieldTypeBundle\Form\Type;


use EzSystems\EzPlatformMatrixFieldtype\FieldType\Value\Row;
use EzSystems\EzPlatformMatrixFieldtype\FieldType\Value\RowsCollection;
use EzSystems\EzPlatformMatrixFieldtype\Form\Transformer\FieldTypeModelTransformer;
use EzSystems\EzPlatformMatrixFieldtype\Form\Type\FieldType\MatrixCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CityFieldType extends AbstractType
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getBlockPrefix();
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'ezplatform_fieldtype_ramcity';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {

    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ville', TextType::class)
            ->add('codePostal', TextType::class)
            ->add('codeInsee', TextType::class);
    }
}