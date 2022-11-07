<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\Repository;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;

final class ContentProvider implements ValueObjectProviderInterface
{
    private Repository $repository;

    private LoadService $loadService;

    public function __construct(Repository $repository, LoadService $loadService)
    {
        $this->repository = $repository;
        $this->loadService = $loadService;
    }

    public function getValueObject($value): ?object
    {
        try {
            /** @var \Netgen\IbexaSiteApi\API\Values\Content $content */
            return $this->repository->sudo(
                fn (Repository $repository): Content => $this->loadService->loadContent((string) $value),
            );
        } catch (NotFoundException $e) {
            return null;
        }
    }
}