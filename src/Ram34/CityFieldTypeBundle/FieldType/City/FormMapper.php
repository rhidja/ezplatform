<?php

namespace Ram34\CityFieldTypeBundle\FieldType\City;

use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Ram34\CityFieldTypeBundle\Form\Transformer\CityValueTransformer;
use Ram34\CityFieldTypeBundle\Form\Type\CityFieldType;
use Symfony\Component\Form\FormInterface;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use eZ\Publish\Core\Repository\FieldTypeService;


/**
 * Class FormMapper
 * @package Ram34\CityFieldTypeBundle\FieldType\City
 */
class FormMapper implements FieldValueFormMapperInterface
{
    /**
     * @var FieldTypeService
     */
    private $fieldTypeService;

    /**
     * FormMapper constructor.
     * @param FieldTypeService $fieldTypeService
     */
    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    /**
     * @param FormInterface $fieldForm
     * @param FieldData $data
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
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
                        CityFieldType::class,
                        [
                            'required' => false,
                            'label' => $label
                        ]
                    )
                    // Deactivate auto-initialize as you're not on the root form.
                    ->setAutoInitialize(false)
                    ->addModelTransformer(new CityValueTransformer($fieldType))
                    ->getForm()
            );
    }
}