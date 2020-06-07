<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\UI\Http\Rest\Controller\CommandQueryController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ChangeEmailController extends CommandQueryController
{
    /**
     * @Route(
     *     "/user/{uuid}/changeEmail",
     *     name="user_change_email",
     *     methods={"POST"},
     *     requirements={
     *      "email": "\w+"
     *     }
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Email changed"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @SWG\Parameter(
     *     name="change-email",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="email", type="string")
     *     )
     * )
     * @SWG\Parameter(
     *     name="uuid",
     *     type="string",
     *     in="path"
     * )
     *
     * @SWG\Tag(name="User")
     *
     * @Security(name="Bearer")
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $email = (string) $request->get('email');

        try {
            Assertion::notNull($email, "Email can\'t be null");
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $command = new ChangeEmailCommand($uuid, $email);

        $this->exec($command);

        return new JsonResponse();
    }
}
