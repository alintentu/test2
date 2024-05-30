<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Prize;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function playGame(User $user): ?Prize
    {
        // Check eligibility to play
        if (!$this->isEligibleToPlay($user)) {
            return null;
        }

        // Generate a random prize
        $prize = $this->generateRandomPrize();

        // Record the prize for the user
        $user->addPrize($prize);
        $this->entityManager->persist($prize);
        $this->entityManager->flush();

        return $prize;
    }

    private function isEligibleToPlay(User $user): bool
    {
        // Implement logic to check eligibility based on user's prize history and current time
        // For example, check if the user has already won a prize today and if the current time is within the allowed time periods
        return true;
    }

    private function generateRandomPrize(): Prize
    {
        // Implement logic to generate a random prize
        // For example, select a random prize from the available options
        $prize = new Prize();
        // Set properties of the prize
        return $prize;
    }
}
