<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter;

use Ibexa\Contracts\Core\Repository\Values\Content\Location as IbexaLocation;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\Layouts\Item\ValueConverterInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\Ibexa\Contracts\Core\Repository\Values\Content\Location|\Netgen\IbexaSiteApi\API\Values\Location>
 */
final class LocationValueConverter implements ValueConverterInterface
{
    /**
     * @param \Netgen\Layouts\Item\ValueConverterInterface<\Ibexa\Contracts\Core\Repository\Values\Content\Location> $innerConverter
     */
    public function __construct(private ValueConverterInterface $innerConverter, private LoadService $loadService) {}

    public function supports(object $object): bool
    {
        if ($object instanceof Location) {
            return true;
        }

        return $this->innerConverter->supports($object);
    }

    public function getValueType(object $object): string
    {
        return 'ibexa_location';
    }

    public function getId(object $object): int
    {
        if ($object instanceof Location) {
            return (int) $object->id;
        }

        return (int) $this->innerConverter->getId($object);
    }

    public function getRemoteId(object $object): string
    {
        if ($object instanceof Location) {
            return $object->remoteId;
        }

        return (string) $this->innerConverter->getRemoteId($object);
    }

    public function getName(object $object): string
    {
        if ($object instanceof Location) {
            return (string) $object->contentInfo->name;
        }

        return $this->innerConverter->getName($object);
    }

    public function getIsVisible(object $object): bool
    {
        if ($object instanceof Location) {
            return !$object->invisible;
        }

        return $this->innerConverter->getIsVisible($object);
    }

    public function getObject(object $object): Location
    {
        if ($object instanceof IbexaLocation) {
            return $this->loadService->loadLocation($object->id);
        }

        return $object;
    }
}
