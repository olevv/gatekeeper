<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Query\Query;
use App\Application\Query\QueryBus;
use App\Domain\User\ViewModel\SerializableView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class QueryController extends AbstractController
{
    private QueryBus $queryBus;

    private UrlGeneratorInterface $router;

    public function __construct(QueryBus $queryBus, UrlGeneratorInterface $router)
    {
        $this->queryBus = $queryBus;
        $this->router = $router;
    }

    /**
     * @return mixed
     *
     * @throws \Throwable
     */
    protected function ask(Query $query)
    {
        return $this->queryBus->ask($query);
    }

    protected function jsonResponse(SerializableView $view): JsonResponse
    {
        return new JsonResponse($view->toArray());
    }

    protected function jsonArray(SerializableView ...$views): JsonResponse
    {
        return new JsonResponse(array_map(fn (SerializableView $view) => $view->toArray(), $views));
    }

    protected function route(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
