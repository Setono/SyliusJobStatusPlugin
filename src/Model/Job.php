<?php

declare(strict_types=1);

namespace Setono\SyliusJobStatusPlugin\Model;

use Setono\JobStatusBundle\Entity\Job as BaseJob;
use Sylius\Component\Resource\Model\ResourceInterface;

class Job extends BaseJob implements ResourceInterface
{
}
