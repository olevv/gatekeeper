<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Command\User\ChangePassword\ChangePasswordCommand;
use App\UI\Http\Rest\Controller\CommandQueryController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ChangePasswordController extends CommandQueryController
{
    /**
     * @Route(
     *     "/user/{uuid}/changePassword",
     *     name="change_password",
     *     methods={"POST"},
     *     requirements={
     *      "password": "\w+"
     *     }
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Password changed successfully"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
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
        $password = (string) $request->get('password');

        try {
            Assertion::notNull($password, 'New password cant\'t be empty');
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $command = new ChangePasswordCommand($uuid, $password);

        $this->denyAccessUnlessGranted($uuid, $command);

        $this->exec($command);

        return new JsonResponse();
    }
}
