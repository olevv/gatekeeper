<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Domain\User\ViewModel\SerializableView;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class QueryController extends AbstractController
{
    /**
     * @var CommandBus
     */
    private $queryBus;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(CommandBus $queryBus, UrlGeneratorInterface $router)
    {
        $this->queryBus = $queryBus;
        $this->router = $router;
    }

    protected function ask($query)
    {
        return $this->queryBus->handle($query);
    }

    /**
     * @param SerializableView $view
     *
     * @return JsonResponse
     */
    protected function jsonResponse(SerializableView $view): JsonResponse
    {
        return JsonResponse::create($view->toArray());
    }

    /**
     * @param SerializableView[] $views
     *
     * @return JsonResponse
     */
    protected function jsonArray(array $views): JsonResponse
    {
        return JsonResponse::create($this->serializeViews($views));
    }

    /**
     * @param SerializableView[] $views
     *
     * @return array
     */
    private function serializeViews(array $views): array
    {
        return array_map(
            static function (SerializableView $view) {
                return $view->toArray();
            },
            $views
        );
    }

    protected function route(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
