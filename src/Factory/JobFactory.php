<?php

declare(strict_types=1);

namespace Setono\SyliusJobStatusPlugin\Factory;

use Setono\JobStatusBundle\Entity\JobInterface;
use Setono\JobStatusBundle\Factory\JobFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class JobFactory implements JobFactoryInterface, FactoryInterface
{
    private FactoryInterface $decorated;

    public function __construct(FactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function createNew(): JobInterface
    {
        $job = $this->decorated->createNew();
        Assert::isInstanceOf($job, JobInterface::class);

        return $job;
    }
}
