<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Command\User\Block\BlockCommand;
use App\UI\Http\Rest\Controller\CommandQueryController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class BlockController extends CommandQueryController
{
    /**
     * @Route(
     *     "/user/{uuid}/block",
     *     name="user_block",
     *     methods={"POST"}
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="User blocked successfully"
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
     *
     * @param string  $uuid
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {
            Assertion::notNull($uuid, 'Uuid cannot be empty');
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $command = new BlockCommand($uuid);

        $this->denyAccessUnlessGranted(null, $command);

        $this->exec($command);

        return JsonResponse::create();
    }
}
