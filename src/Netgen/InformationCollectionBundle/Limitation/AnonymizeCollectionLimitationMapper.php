<?php

declare(strict_types=1);

namespace Netgen\InformationCollectionBundle\Limitation;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Values\User\Limitation;
use EzSystems\RepositoryForms\Limitation\LimitationValueMapperInterface;
use EzSystems\RepositoryForms\Limitation\Mapper\MultipleSelectionBasedMapper;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class AnonymizeCollectionLimitationMapper extends MultipleSelectionBasedMapper implements LimitationValueMapperInterface
{
    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    protected $contentTypeService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * AnonymizeCollectionLimitationMapper constructor.
     * @param ContentTypeService $contentTypeService
     * @param LoggerInterface|null $logger
     */
    public function __construct(ContentTypeService $contentTypeService, ?LoggerInterface $logger = null)
    {
        $this->contentTypeService = $contentTypeService;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param Limitation $limitation
     * @return array|mixed[]
     */
    public function mapLimitationValue(Limitation $limitation)
    {
        $values = [];
        print_r($limitation->limitationValues);

        foreach ($limitation->limitationValues as $identifier) {
            try {
                $values[] = $this->contentTypeService->loadContentTypeByIdentifier($identifier);
            } catch (NotFoundException $e) {
                $this->logger->error(sprintf('Could not map limitation value: Content Type with identifier = %s not found', $identifier));
            }
        }

        return $values;
    }

    protected function getSelectionChoices()
    {
        $contentTypeChoices = [];
        foreach ($this->contentTypeService->loadContentTypeGroups() as $group) {
            foreach ($this->contentTypeService->loadContentTypes($group) as $contentType) {
                foreach ($contentType->getFieldDefinitions() as $fieldDefinition) {
                    if ($fieldDefinition->isInfoCollector) {
                        $contentTypeChoices[$contentType->identifier] = $contentType->getName($contentType->mainLanguageCode);

                        break;
                    }
                }
            }
        }

        return $contentTypeChoices;
    }
}