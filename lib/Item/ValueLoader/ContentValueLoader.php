<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ibexa\SiteApi\Item\ValueLoader;

use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\IbexaSiteApi\API\Values\ContentInfo;
use Netgen\Layouts\Item\ValueLoaderInterface;
use Throwable;

final class ContentValueLoader implements ValueLoaderInterface
{
    public function __construct(private LoadService $loadService) {}

    public function load($id): ?ContentInfo
    {
        try {
            $contentInfo = $this->loadService->loadContent((int) $id)->contentInfo;
        } catch (Throwable) {
            return null;
        }

        if (!$contentInfo->published || $contentInfo->mainLocationId === null) {
            return null;
        }

        return $contentInfo;
    }

    public function loadByRemoteId($remoteId): ?ContentInfo
    {
        try {
            $contentInfo = $this->loadService->loadContentByRemoteId((string) $remoteId)->contentInfo;
        } catch (Throwable) {
            return null;
        }

        if (!$contentInfo->published || $contentInfo->mainLocationId === null) {
            return null;
        }

        return $contentInfo;
    }
}
