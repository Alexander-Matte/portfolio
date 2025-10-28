<?php

namespace App\Service;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Psr\Log\LoggerInterface;

class ActivityPublisher
{
    private EntityManagerInterface $em;
    private HubInterface $hub;

    public function __construct(
        EntityManagerInterface $em,
        HubInterface $hub,
        private LoggerInterface $logger)
    {
        $this->em = $em;
        $this->hub = $hub;
    }

    public function publish(Activity $activity): ?string
    {
        try {
            // Persist activity
            $this->em->persist($activity);
            $this->em->flush();
    
            // Send Mercure update
            $update = new Update(
                topics: ['http://localhost/topics/activities'],
                data: json_encode([
                    'id' => $activity->getId(),
                    'type' => $activity->getType(),
                    'username' => $activity->getUsername(),
                    'message' => $activity->getMessage(),
                    'data' => $activity->getData(),
                    'timestamp' => $activity->getTimestamp()->format('c'),
                ]),
                private: false
            );
    
            $updateId = $this->hub->publish($update);
    
            // Log the Mercure publication
            $this->logger->info('Activity published to Mercure', [
                'activityId' => $activity->getId(),
                'mercureUpdateId' => $updateId,
            ]);
    
            return $updateId;
        } catch (\Throwable $e) {
            $this->logger->error('Failed to publish activity to Mercure', [
                'error' => $e->getMessage(),
            ]);
    
            return null;
        }
    }
    
}
