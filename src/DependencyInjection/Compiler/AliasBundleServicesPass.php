<?php

declare(strict_types=1);

namespace Setono\SyliusJobStatusPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This class will alias services from the bundle to services in the plugin
 */
final class AliasBundleServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $mapping = [
            'setono_job_status.factory.job' => 'setono_sylius_job_status.factory.job',
            'setono_job_status.repository.job' => 'setono_sylius_job_status.repository.job',
        ];

        foreach ($mapping as $bundleServiceId => $pluginServiceId) {
            if (!$container->hasDefinition($bundleServiceId)) {
                continue;
            }

            if (!$container->hasDefinition($pluginServiceId)) {
                continue;
            }

            $container->setAlias($bundleServiceId, $pluginServiceId);
        }
    }
}
