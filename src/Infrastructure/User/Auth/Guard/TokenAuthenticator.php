<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Auth\Guard;

use App\Application\Query\User\FindByToken\FindByTokenQuery;
use App\Domain\User\Exception\InvalidAccessTokenException;
use App\Domain\User\ValueObject\AccessToken;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Role;
use App\Domain\User\ValueObject\Status;
use App\Domain\User\ViewModel\UserView;
use App\Infrastructure\User\Auth\Auth;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

final class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private const PREFIX = 'Bearer';

    private const NAME = 'Authorization';

    private CommandBus $queryBus;

    public function __construct(CommandBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        $response->setData(
            [
                'error' => 'UNAUTHORIZED_ERROR',
                'message' => null !== $authException
                    ? $authException->getMessage()
                    : 'Authentication Required',
            ]
        );

        return $response;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has(self::NAME);
    }

    public function getCredentials(Request $request): string
    {
        if (!$this->supports($request)) {
            throw new AuthenticationException();
        }

        $authorizationHeader = $request->headers->get(self::NAME);

        $headerParts = explode(' ', $authorizationHeader);

        if (!(2 === count($headerParts) && 0 === strcasecmp($headerParts[0], self::PREFIX))) {
            throw new AuthenticationException();
        }

        return $headerParts[1];
    }

    /**
     * @param string $credentials
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        try {
            /** @var UserView $userView */
            $userView = $this->queryBus->handle(new FindByTokenQuery($credentials));

            $credentials = new Credentials(
                Email::fromString($userView->email),
                HashedPassword::fromHash($userView->password_hash)
            );

            $accessToken = AccessToken::createWithExpiresAt(
                $userView->access_token,
                new \DateTimeImmutable($userView->access_token_expires)
            );

            return new Auth(
                Uuid::fromString($userView->uuid),
                $credentials,
                new Role($userView->role),
                new Status($userView->status),
                $accessToken
            );
        } catch (InvalidAccessTokenException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $response->setData(
            [
                'error' => 'AUTHENTICATION_ERROR',
                'message' => $exception->getMessage(),
            ]
        );

        return $response;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
