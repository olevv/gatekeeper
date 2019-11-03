<?php declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Query\User\GetUsers\GetUsersQuery;
use App\UI\Http\Rest\Controller\QueryController;
use Assert\Assertion;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class GetUsersController extends QueryController
{
    /**
     * @Route(
     *     "/users",
     *     name="users",
     *     methods={"GET"}
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns all users"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @SWG\Tag(name="User")
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 50);
        $offset = $request->get('offset', 0);

        Assertion::numeric($limit, 'Limit results must be an integer');
        Assertion::numeric($offset, 'Offset results must be an integer');

        $query = new GetUsersQuery((int) $offset, (int) $limit);

        $this->denyAccessUnlessGranted(null, $query);

        return $this->jsonArray($this->ask($query));
    }
}
