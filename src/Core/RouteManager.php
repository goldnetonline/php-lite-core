<?php
namespace Goldnetonline\PhpLiteCore;

use Goldnetonline\PhpLiteCore\Traits\Singleton;

class RouteManager
{
    use Singleton;

    public $app;

    const ALLOWED_METHODS = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTION',
    ];

    /**
     * @var $routeFile
     *
     * The route file to load
     */
    private $routeFile;

    /**
     * @var array $routeCollection
     *
     * store all routes here
     */
    protected $routeCollection = [];

    /**
     * @var array $routes
     *
     * The raw routes declarations
     */

    protected $routes = [];

    public function __construct($app)
    {
        $this->app = $app;

        $this->getRoutes();
    }

    /**
     * Get all dynamic routes
     */
    protected function getRoutes()
    {

        if (sizeOf($this->routes)) {
            return $this->routes;
        }

        $this->routeFile = $this->app->getConfig('app.route_file');

        if ($this->routeFile && \file_exists($this->routeFile)) {
            $this->routes = include_once $this->routeFile;
        } else {
            $this->routes = [];
        }

        return $this->parseRoutes();
    }

    public function isAllowedMethod(string $method): bool
    {
        return in_array(strtoupper($method), self::ALLOWED_METHODS);
    }

    /**
     *Parse the routes and specify their methods
     */
    protected function parseRoutes()
    {

        // Routes must be an array and must contain content
        if (!$this->routes || !is_array($this->routes) || !sizeOf($this->routes)) {
            return;
        }

        foreach ($this->routes as $route => $handler) {
            // Determine route method
            $pipeBreak = explode("|", $route);
            if (sizeOf($pipeBreak) === 1) {
                $this->routeCollection[$route] = [
                    'methods' => ['GET'],
                    'handler' => $handler,
                ];
                continue;
            }

            $this->addMultiMethodRoute($pipeBreak, $handler);
            // Other routes that does not fall in will be ignored
        }
    }

    private function addMultiMethodRoute(array $pipeBreak, mixed $handler): void
    {
        if (sizeOf($pipeBreak) < 2) {
            return;
        }

        $allowedMethods = $this->parseAllowedMethods($pipeBreak[0]);
        if (!$allowedMethods) {
            return;
        }

        $this->routeCollection[$pipeBreak[1]] = [
            'methods' => $allowedMethods,
            'handler' => $handler,
        ];
    }

    private function parseAllowedMethods(string $methods): array
    {
        $allowedMethods = [];
        foreach (explode(',', $methods) as $method) {
            $method = trim($method);
            if ($this->isAllowedMethod($method)) {
                $allowedMethods[] = strtoupper($method);
            }
        }

        return array_values(array_unique($allowedMethods));
    }

    /**
     * Find a route handler from the routeCollection
     *
     * @param Request $request  The request object
     * @return mixed
     */
    public function getRouteHandler(Request $request)
    {
        $route = $request->uri;
        $method = $request->method;

        if (
            !array_key_exists($route, $this->routeCollection)
            || !in_array(strtoupper($method), $this->routeCollection[$route]['methods'], true)
        ) {
            return null;
        }

        return $this->routeCollection[$route]['handler'];
    }
}
