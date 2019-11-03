<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Auth;

use App\Application\Command\User\SignIn\SignInCommand;
use App\Application\Query\Auth\GetToken\GetTokenQuery;
use App\UI\Http\Rest\Controller\CommandQueryController;
use Assert\Assertion;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class CheckController extends CommandQueryController
{
    /**
     * @Route(
     *     "/auth/check",
     *     name="auth_check",
     *     methods={"POST"},
     *     requirements={
     *      "email": "\w+",
     *      "password": "\w+"
     *     }
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Login success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="ad credentials"
     * )
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="password", type="string"),
     *         @SWG\Property(property="email", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Auth")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $plainPassword = $request->get('password');

        Assertion::notNull($email, 'Email cant\'t be empty');
        Assertion::notNull($plainPassword, 'Password cant\'t be empty');

        $command = new SignInCommand($email, $plainPassword);

        $this->exec($command);

        return JsonResponse::create(
            [
                'token' => $this->ask(new GetTokenQuery($email)),
            ]
        );
    }
}
