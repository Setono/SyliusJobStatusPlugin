<?php

declare(strict_types=1);

namespace Setono\SyliusJobStatusPlugin\Doctrine\ORM;

use Happyr\DoctrineSpecification\Repository\EntitySpecificationRepositoryTrait;
use Setono\JobStatusBundle\Entity\Job;
use Setono\JobStatusBundle\Entity\JobInterface;
use Setono\JobStatusBundle\Repository\JobRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class JobRepository extends EntityRepository implements JobRepositoryInterface
{
    use EntitySpecificationRepositoryTrait;

    public function findRunningJobs(int $limit = 1000, int $offset = null): array
    {
        $jobs = $this->findBy(['state' => Job::STATE_RUNNING], ['updatedAt' => 'DESC'], $limit, $offset);
        Assert::allIsInstanceOf($jobs, Job::class);

        return $jobs;
    }

    public function hasExclusiveRunningJob(string $type): bool
    {
        $res = (int) $this->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->andWhere('o.exclusive = true')
            ->andWhere('o.state = :state')
            ->andWhere('o.type = :type')
            ->setParameter('state', JobInterface::STATE_RUNNING)
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();

        return $res > 0;
    }
}
