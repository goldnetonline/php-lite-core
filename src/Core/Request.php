<?php
namespace Goldnetonline\PhpLiteCore;

use Goldnetonline\PhpLiteCore\Traits\Singleton;

class Request
{

    use Singleton;

    public $app;
    public $slug;
    public $uri;
    public $url;
    public $server;
    public $host;
    public $https;
    public $port;
    public $serverIp;
    public $scheme;
    public $domain;
    public $requestIp;
    public $method;
    public $rawQueryString;
    public $queryStringArray;
    public $rawRequest;

    public function __construct($app)
    {
        $this->server = $_SERVER;

        $uri = urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );

        $this->uri = $uri === "/" ? $uri : \preg_replace("/\/$/", '', $uri);
        if ($this->uri !== '/') {
            $this->uri = \preg_replace("/^\//", '', $this->uri);
        }

        $this->url = $this->server['REQUEST_URI'];
        $this->app = $app;

        $this->slug = \preg_replace('/[_\s\/]/', '-', $this->uri);

        $this->host = $this->server['HTTP_HOST'];
        $this->https = isset($this->server['HTTPS']) && $this->server['HTTPS'] === 'on' ? true : false;
        $this->scheme = $this->https ? "https" : "http";
        $this->port = $this->server['SERVER_PORT'];
        $this->domain = $this->scheme . "://" . $this->host;
        if (!in_array($this->port, ['80', '443'])) {
            $this->domain .= ":{$this->port}";
        }
        $this->serverIp = $this->server['SERVER_ADDR'] ?? null;
        $this->requestIp = $this->server['REMOTE_ADDR'] ?? null;
        $this->method = $this->server['REQUEST_METHOD'];
        $this->rawQueryString = $this->server['QUERY_STRING'] ?? '';
        $this->queryStringArray = $this->parseQueryString($this->rawQueryString);
    }

    /**
     * Parse the query string in the request
     *
     * @param string $query    The full query string to parse
     *
     * @return array
     */
    private function parseQueryString(string $query): array
    {
        $stringArr = explode("&", $query);
        if (!sizeOf($stringArr)) {
            return [];
        }

        $qs = [];
        foreach ($stringArr as $qsv) {
            $split = explode("=", $qsv);
            if (sizeof($split)) {
                $qs[$split[0]] = $split[1] ?? null;
            }
        }

        return $qs;
    }

    /**
     * Read all input into the app
     *
     * @return array
     */
    public function all(): array
    {
        return array_merge([], $_REQUEST, $this->queryStringArray);
    }

    /**
     * Get an input of the request
     * @param string $key       The input key to get
     * @param string $default   The default value
     */
    public function input($key = null, $default = null)
    {

        if (!$key) {
            return $this->all();
        }

        return $this->all()[$key] ?? $default;
    }

    /**
     * Read from the query string
     * @param string $key       The key to get
     * @param string $default   The default value
     */
    public function query($key, $default = null)
    {
        return $this->queryStringArray[$key] ?? $default;
    }

    /**
     * Get the raw request
     */
    public function getContent()
    {
        return $this->rawRequest;
    }

    public function accepts($key): bool
    {
        $accept = explode(",", $this->server['HTTP_ACCEPT']);
        return $accept[$key] ? true : false;
    }

    /**
     * Check if the request is Ajax
     */
    public function isAjax(): bool
    {

    }

    /**
     * Debug the request
     */
    public function dump()
    {
        return dd($this);
    }

}
