<?php

declare(strict_types=1);

namespace Setono\SyliusJobStatusPlugin\Doctrine\ORM;

use Setono\JobStatusBundle\Entity\Job;
use Setono\JobStatusBundle\Repository\JobRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class JobRepository extends EntityRepository implements JobRepositoryInterface
{
    public function findRunningJobs(int $limit = 1000, int $offset = null): array
    {
        $jobs = $this->findBy(['state' => Job::STATE_RUNNING], ['updatedAt' => 'DESC'], $limit, $offset);
        Assert::allIsInstanceOf($jobs, Job::class);

        return $jobs;
    }
}
