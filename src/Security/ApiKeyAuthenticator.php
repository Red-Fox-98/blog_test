<?php

namespace App\Security;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly string $publicKey
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('jwt');
    }

    public function authenticate(Request $request): Passport
    {
        $jwt = $request->headers->get('jwt');
        if (null === $jwt) {
            throw new CustomUserMessageAuthenticationException('No JWT token provided');
        }

        try {
            $decoded = (array)JWT::decode($jwt, new Key($this->publicKey, 'RS256'));
        } catch (SignatureInvalidException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials');
        }

        $userId = $decoded['userId'] ?? null;

        if ($userId === null) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials');
        }

        $user = $this->userRepository->find($userId);
        if ($user === null) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials');
        }

        return new SelfValidatingPassport(new UserBadge($user->getEmail()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
