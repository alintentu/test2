<?php
namespace App\Controller;

use App\Entity\Prize;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security; //

class GameController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/play', name: 'play_game', methods: ['GET'])]
    public function play(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $now = new \DateTime();
        $start = (clone $now)->setTime(9, 0);
        $end = (clone $now)->setTime(20, 0);

        if ($now < $start || $now > $end) {
            return new JsonResponse(['error' => 'Game is not available at this time'], JsonResponse::HTTP_FORBIDDEN);
        }

        if ($user->getLastPlayedAt() && $user->getLastPlayedAt()->format('Y-m-d') === $now->format('Y-m-d')) {
            return new JsonResponse(['error' => 'You have already played today'], JsonResponse::HTTP_FORBIDDEN);
        }

        $prize = $this->entityManager->getRepository(Prize::class)->findOneBy(['awardedAt' => null]);

        if (!$prize) {
            return new JsonResponse(['error' => 'No prizes available'], JsonResponse::HTTP_NOT_FOUND);
        }

        $prize->setAwardedAt($now);
        $prize->setAwardedTo($user);
        $user->setLastPlayedAt($now);

        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Congratulations! You have won a prize!',
            'prize' => $prize->getName(),
            'partner' => $prize->getPartner()->getName()
        ]);
    }

    #[Route('/play/status', name: 'check_play_status', methods: ['GET'])]
    public function checkStatus(): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $prize = $this->entityManager->getRepository(Prize::class)->findOneBy(['awardedTo' => $user, 'awardedAt' => new \DateTime()]);

        if (!$prize) {
            return new JsonResponse(['message' => 'You have not played today']);
        }

        return new JsonResponse([
            'message' => 'You have already played today',
            'prize' => $prize->getName(),
            'partner' => $prize->getPartner()->getName()
        ]);
    }
}
