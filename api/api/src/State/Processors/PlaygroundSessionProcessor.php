<?php

namespace App\State\Processors;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\PlaygroundSession;
use Doctrine\ORM\EntityManagerInterface;

class PlaygroundSessionProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?PlaygroundSession
    {
        if (!$data instanceof PlaygroundSession) {
            return $data;
        }

        if ($operation->getName() === '_api_/sessions_post') {
            $username = $this->generateUniqueUsername();
            $data->setUsername($username);

            $token = $this->generateSecureToken();
            $data->setToken($token);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    private function generateUniqueUsername(): string
    {
        $adjectives = [
            'Swift', 'Brave', 'Clever', 'Bright', 'Noble', 'Wise', 'Bold', 'Quick',
            'Silent', 'Mighty', 'Gentle', 'Fierce', 'Calm', 'Wild', 'Free', 'True',
            'Dark', 'Light', 'Storm', 'Frost', 'Fire', 'Wind', 'Ocean', 'Sky'
        ];

        $nouns = [
            'Fox', 'Wolf', 'Bear', 'Eagle', 'Lion', 'Tiger', 'Hawk', 'Raven',
            'Dragon', 'Phoenix', 'Falcon', 'Panther', 'Leopard', 'Jaguar', 'Cobra',
            'Viper', 'Shark', 'Whale', 'Dolphin', 'Orca', 'Lynx', 'Puma', 'Cheetah'
        ];

        do {
            $adjective = $adjectives[array_rand($adjectives)];
            $noun = $nouns[array_rand($nouns)];
            $number = random_int(100, 999);
            $username = sprintf('%s%s%d', $adjective, $noun, $number);

            $existing = $this->entityManager
                ->getRepository(PlaygroundSession::class)
                ->findOneBy(['username' => $username]);
        } while ($existing !== null);

        return $username;
    }

    private function generateSecureToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}

