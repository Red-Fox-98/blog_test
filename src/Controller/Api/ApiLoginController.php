<?php

namespace App\Controller\Api;

use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController {
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user, ParameterBagInterface $parameterBag): Response
    {
        if ($user === null) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $payload = [
            'userId' => $user->getId(),
            'iat' => time()
        ];

        $jwt = JWT::encode($payload, $parameterBag->get('app.jwt.privateKey'), 'RS256');

        return $this->json([
            'result' => 'success',
            'jwt' => $jwt
        ]);
    }
}
