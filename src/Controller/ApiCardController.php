<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/card', name: 'api_card_')]
#[OA\Tag(name: 'Card', description: 'Routes for all about cards')]
class ApiCardController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {
    }
    #[Route('/all', name: 'List all cards', methods: ['GET'])]
    #[OA\Parameter(name: 'setCode', description: 'Filter by set code', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Get(description: 'Return all cards in the database')]
    #[OA\Response(response: 200, description: 'List all cards')]
    public function cardAll(Request $request): Response {
        $setCode = $request->query->get('setCode');
        if ($setCode) {
            $cards = $this->entityManager->getRepository(Card::class)->findBy(['setCode' => $setCode]);
        } else {
            $cards = $this->entityManager->getRepository(Card::class)->findAll();
        }
        return $this->json($cards);
    }

    #[Route('/search', name: 'Search cards', methods: ['GET'])]
    #[OA\Parameter(name: 'name', description: 'Name of the card to search', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'setCode', description: 'Filter by set code', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Get(description: 'Search cards by name')]
    #[OA\Response(response: 200, description: 'List of cards matching the search')]
    public function search(Request $request): Response {
        $name = $request->query->get('name');
        $setCode = $request->query->get('setCode');

        if (!$name || strlen($name) < 3) {
            return $this->json([], 200);
        }
        
        $cards = $this->entityManager->getRepository(Card::class)->searchByName($name, $setCode);
        return $this->json($cards);
    }

    #[Route('/sets', name: 'List all set codes', methods: ['GET'])]
    #[OA\Get(description: 'Return all available set codes')]
    #[OA\Response(response: 200, description: 'List of set codes')]
    public function listSets(): Response {
        $sets = $this->entityManager->getRepository(Card::class)->getAllSetCodes();
        return $this->json($sets);
    }

    #[Route('/{uuid}', name: 'Show card', methods: ['GET'])]
    #[OA\Parameter(name: 'uuid', description: 'UUID of the card', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Put(description: 'Get a card by UUID')]
    #[OA\Response(response: 200, description: 'Show card')]
    #[OA\Response(response: 404, description: 'Card not found')]
    public function cardShow(string $uuid): Response
    {
        $card = $this->entityManager->getRepository(Card::class)->findOneBy(['uuid' => $uuid]);
        if (!$card) {
            return $this->json(['error' => 'Card not found'], 404);
        }
        return $this->json($card);
    }
}
