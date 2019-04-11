<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ez\SiteApi\Item\ValueLoader;

use Netgen\EzPlatformSiteApi\API\LoadService;
use Netgen\Layouts\Item\ValueLoaderInterface;
use Throwable;

final class ContentValueLoader implements ValueLoaderInterface
{
    /**
     * @var \Netgen\EzPlatformSiteApi\API\LoadService
     */
    private $loadService;

    public function __construct(LoadService $loadService)
    {
        $this->loadService = $loadService;
    }

    public function load($id): ?object
    {
        try {
            $contentInfo = $this->loadService->loadContent((int) $id)->contentInfo;
        } catch (Throwable $t) {
            return null;
        }

        if (!$contentInfo->published || $contentInfo->mainLocationId === null) {
            return null;
        }

        return $contentInfo;
    }

    public function loadByRemoteId($remoteId): ?object
    {
        try {
            $contentInfo = $this->loadService->loadContentByRemoteId((string) $remoteId)->contentInfo;
        } catch (Throwable $t) {
            return null;
        }

        if (!$contentInfo->published || $contentInfo->mainLocationId === null) {
            return null;
        }

        return $contentInfo;
    }
}
