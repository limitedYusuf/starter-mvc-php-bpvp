<?php

class Router
{
    protected $routes = [];

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute($method, $uri, $action)
    {
        $this->routes[$method][$this->parseUri($uri)] = $action;
    }

    public function dispatch()
    {
        $uri = $this->getUri();
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUri => $action) {
                if ($this->matchUri($routeUri, $uri)) {
                    list($controller, $method) = explode('@', $action);
                    $controllerInstance = new $controller();
                    $request = new Request();
                    $controllerInstance->$method($request);
                    return;
                }
            }
        }

        $this->handleNotFound($uri);
    }

    protected function parseUri($uri)
    {
        return '^' . preg_quote($uri, '#') . '$';
    }

    protected function matchUri($routeUriPattern, $requestedUri)
    {
        return preg_match('#' . $routeUriPattern . '#', $requestedUri);
    }

    protected function getUri()
    {
        $scriptDirectory = dirname($_SERVER['SCRIPT_NAME']);
        $baseUri = str_replace($scriptDirectory, '', $_SERVER['REQUEST_URI']);
        return trim(parse_url($baseUri, PHP_URL_PATH), '/');
    }

    protected function extractParams($routeUri, $requestedUri)
    {
        preg_match("#^$routeUri$#", $requestedUri, $matches);
        return $matches;
    }

    protected function handleNotFound($uri)
    {
        header("HTTP/1.1 404 Not Found");
        echo "Requested URI: $uri<br>";
        echo "404 Not Found";
        exit;
    }
}
