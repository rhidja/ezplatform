<?php


namespace Ram34\CodePostalBundle\FieldType\CodePostal;

use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Ram34\CodePostalBundle\Form\CodePostalValueTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use eZ\Publish\Core\Repository\FieldTypeService;

class FormMapper implements FieldValueFormMapperInterface
{
    /**
     * @var FieldTypeService
     */
    private $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data)
    {
        $fieldDefinition = $data->fieldDefinition;
        $formConfig = $fieldForm->getConfig();
        $names = $fieldDefinition->getNames();
        $label = $fieldDefinition->getName($formConfig->getOption('mainLanguageCode')) ?: reset($names);
        $fieldType = $this->fieldTypeService->getFieldType($fieldDefinition->fieldTypeIdentifier);
        $fieldForm
            ->add(
                $formConfig->getFormFactory()->createBuilder()
                    ->create(
                        'value',
                        TextType::class,
                        [
                            'required' => false,
                            'label' => $label
                        ]
                    )
                    ->setAutoInitialize(false)
                    ->addModelTransformer(new CodePostalValueTransformer($fieldType))
                    ->getForm()
            );
    }
}