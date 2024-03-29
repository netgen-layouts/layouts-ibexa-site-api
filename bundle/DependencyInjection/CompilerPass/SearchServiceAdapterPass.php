<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsIbexaSiteApiBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SearchServiceAdapterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('netgen_layouts.ibexa_site_api.search_service_adapter')) {
            return;
        }

        $adapterType = $container->getParameter('netgen_layouts.ibexa_site_api.search_service_adapter');

        $searchServiceAdapter = match ($adapterType) {
            'filter' => 'netgen.ibexa_site_api.filter_service.search_adapter',
            'find' => 'netgen.ibexa_site_api.find_service.search_adapter',
            default => null,
        };

        if ($searchServiceAdapter === null || !$container->has($searchServiceAdapter)) {
            return;
        }

        if ($container->hasAlias('netgen_layouts.ibexa.search_service')) {
            $container->setAlias('netgen_layouts.ibexa.search_service', $searchServiceAdapter);
        }

        if ($container->hasAlias('netgen_content_browser.ibexa.search_service')) {
            $container->setAlias('netgen_content_browser.ibexa.search_service', $searchServiceAdapter);
        }
    }
}
