<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class ManifestController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $routes = collect(Route::getRoutes())
            ->filter(fn($route) => str_starts_with($route->uri(), 'api/'))
            ->map(function ($route) {
                $action = $route->getActionName();
                $action = str_replace('App\\Http\\Controllers\\', '', $action);

                return [
                    'method' => implode('|', array_values(array_diff($route->methods(), ['HEAD']))),
                    'uri' => '/' . $route->uri(),
                    'name' => $route->getName(),
                    'action' => str_replace('::', '@', $action),
                    'middleware' => array_values($route->gatherMiddleware()),
                    'parameters' => $route->parameterNames(),
                ];
            })
            ->values();

        return response()->json([
            'name' => config('app.name', 'API Exemple'),
            'routes' => $routes,
        ]);
    }
}
