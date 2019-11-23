<?php
namespace EzSystems\TweetFieldTypeBundle\eZ\Publish\FieldType\Tweet;

use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use EzSystems\TweetFieldTypeBundle\Form\StringToArrayTransformer;
use EzSystems\TweetFieldTypeBundle\Form\TweetValueTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use eZ\Publish\Core\Repository\FieldTypeService;
use EzSystems\RepositoryForms\Data\FieldDefinitionData;
use EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface;


/**
 * Class FormMapper
 * @package EzSystems\TweetFieldTypeBundle\eZ\Publish\FieldType\Tweet
 */
class FormMapper implements FieldDefinitionFormMapperInterface, FieldValueFormMapperInterface
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

    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data)
    {
        $fieldDefinitionForm
            ->add(
                $fieldDefinitionForm->getConfig()->getFormFactory()->createBuilder()
                    ->create('authorList', TextType::class, [
                        'required' => false,
                        'property_path' => 'validatorConfiguration[TweetValueValidator][authorList]',
                        'translation_domain' => 'eztweet_fieldtype',
                        'label' => 'field_definition.eztweet.authorList'
                    ])
                    ->addModelTransformer(new StringToArrayTransformer())
                    // Deactivate auto-initialize as you're not on the root form.
                    ->setAutoInitialize(false)
                    ->getForm()
            );
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
                        TextType::class,
                        [
                            'required' => false,
                            'label' => $label
                        ]
                    )
                    // Deactivate auto-initialize as you're not on the root form.
                    ->setAutoInitialize(false)
                    ->addModelTransformer(new TweetValueTransformer($fieldType))
                    ->getForm()
            );
    }
}