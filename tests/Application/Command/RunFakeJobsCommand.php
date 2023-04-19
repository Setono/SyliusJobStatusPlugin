<?php
declare(strict_types=1);

namespace Tests\Setono\SyliusJobStatusPlugin\Application\Command;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\JobStatusBundle\Event\StepCompletedEvent;
use Setono\JobStatusBundle\Manager\JobManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunFakeJobsCommand extends Command
{
    protected static $defaultName = 'app:run-fake-jobs';

    private JobManagerInterface $jobManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(JobManagerInterface $jobManager, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();

        $this->jobManager = $jobManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = time();
        $secondsToRun = 60;

        $jobs = [];

        $config = self::getJobConfig();
        foreach ($config as $key => $jobConfig) {
            $jobs[$key] = $this->jobManager->start(null, $jobConfig['steps']);
        }

        do {
            $secondsSinceStart = time() - $start;

            foreach ($jobs as $job) {
                $this->eventDispatcher->dispatch(new StepCompletedEvent($job, (int) ceil($job->getSteps() / $secondsToRun)));
            }
            sleep(1);
        } while($secondsSinceStart < $secondsToRun);

        return 0;
    }

    private static function getJobConfig(int $jobs = 10): array
    {
        $config = [];

        for($i = 0; $i < $jobs; $i++) {
            $steps = random_int(500, 1000);

            $config[] = [
                'steps' => $steps
            ];
        }

        return $config;
    }
}
