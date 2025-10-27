<?php

namespace App\EventListener;

use App\Entity\Note;
use App\Entity\Task;
use App\Entity\UserStats;
use App\Repository\UserStatsRepository;
use App\Security\PlaygroundUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Psr\Log\LoggerInterface;

class StatsEventListener
{
    public function __construct(
        private UserStatsRepository $userStatsRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private LoggerInterface $logger
    ) {
    }


    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return; // ignore sub-requests
        }

        $event->getRequest()->attributes->set('_stats_start_time', microtime(true));
    }


    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Task) {
            $this->handleTaskCreated($entity);
        } elseif ($entity instanceof Note) {
            $this->handleNoteCreated($entity);
        }

        $this->flushIfOpen();
    }


    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Task) {
            $this->handleTaskUpdate($entity);
        }

        $this->flushIfOpen();
    }

    private function handleTaskCreated(Task $task): void
    {
        if ($username = $task->getUserId()) {
            $this->getOrCreateUserStats($username)->incrementTasksCreated();
        }
    }

    private function handleTaskUpdate(Task $task): void
    {
        if (!($username = $task->getUserId())) {
            return;
        }

        $changeset = $this->entityManager->getUnitOfWork()->getEntityChangeSet($task);

        if (isset($changeset['completed']) && $task->isCompleted()) {
            $this->getOrCreateUserStats($username)->incrementTasksCompleted();
        }
    }

    private function handleNoteCreated(Note $note): void
    {
        if ($username = $note->getUserId()) {
            $this->getOrCreateUserStats($username)->incrementNotesCreated();
        }
    }


    public function onKernelTerminate(TerminateEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return; 
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();


        if (str_starts_with($path, '/_profiler') || str_starts_with($path, '/_wdt')) {
            return;
        }

        if (!str_starts_with($path, '/api/')) {
            return;
        }

        // Don't count the stats endpoint itself to avoid double-counting
        if ($path === '/api/stats/me') {
            return;
        }


        if ($request->attributes->get('_stats_processed', false)) {
            return;
        }
        $request->attributes->set('_stats_processed', true);

        $user = $this->security->getUser();
        if (!$user instanceof PlaygroundUser) {
            return;
        }

        $username = $user->getUserIdentifier();
        $stats = $this->getOrCreateUserStats($username);

        $statusCode = $event->getResponse()->getStatusCode();


        if ($statusCode >= 200 && $statusCode < 400) {
            $stats->incrementRequestsMade();
            $stats->incrementSuccessfulRequests();
        }


        $startTime = $request->attributes->get('_stats_start_time', microtime(true));
        $responseTimeMs = (int)((microtime(true) - $startTime) * 1000);
        $stats->addResponseTime($responseTimeMs);

        $stats->setLastActivity(new \DateTimeImmutable());

        $this->logger->info('EVENTLOGGER :: Response time (ms): ' . $responseTimeMs);

        $this->flushIfOpen();
    }

    private function getOrCreateUserStats(string $username): UserStats
    {
        $stats = $this->userStatsRepository->findOneBy(['username' => $username]);

        if (!$stats) {
            $stats = new UserStats();
            $stats->setUsername($username);
            $this->entityManager->persist($stats);
        }

        return $stats;
    }

    private function flushIfOpen(): void
    {
        if ($this->entityManager->isOpen()) {
            $this->entityManager->flush();
        }
    }
}
