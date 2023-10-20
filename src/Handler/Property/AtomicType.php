<?php

declare(strict_types=1);

namespace EntityGenerator\Handler\Property;

use EntityGenerator\Type\PropertyMetadata;

/**
 * @author Mounir Mouih <mounir.mouih@gmail.com>
 */
class AtomicType implements PropertyHandlerInterface
{
    public function __construct(private array $parameters)
    {
    }

    public function handle(PropertyMetaData $propertyMetaData, string $propertyType): void
    {
        $property = $propertyMetaData->property;
        $property->setNullable(true);
        if (true === $this->parameters['property.type']) {
            $this->handleTypeDefinition($propertyMetaData, $propertyType);
        }

        if (true === $this->parameters['property.phpdoc']) {
            $property->addComment(sprintf('@var %s', $propertyType));
        }
    }

    private function handleTypeDefinition(PropertyMetaData $propertyMetaData, string $propertyType): void
    {
        // We check if the type is scalar or object and format the type accordinly
        if ($propertyMetaData->definition->hasSchema()) {
            // todo: loading the proper type right from the start could be a good idea !
            $type = sprintf('%s\%s', $propertyMetaData->namespace->getName(), $propertyType);
        } else {
            $type = $propertyType;
        }

        $propertyMetaData->property->setType($type);
    }
}