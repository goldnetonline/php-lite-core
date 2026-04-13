<?php
namespace Goldnetonline\PhpLiteCore;

use Goldnetonline\PhpLiteCore\Traits\Singleton;

class Response
{
    use Singleton;

    public $app;
    public $code = 200;
    public $responseText;
    private $contentType;
    public $responseBuffer;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Make arbitrary Response
     * @param string $sresponseText
     * @param int $code    The response code
     */
    public function make($responseText = null, int $code = 200): Self
    {

        if (is_array($responseText)) {
            return $this->json($responseText, $code);
        }

        return $this->html($responseText, $code);

    }

    /**
     * HTML Response
     * @param string $sresponseText
     * @param int $code    The response code
     */
    public function html(?string $responseText = null, int $code = 200): Self
    {
        $this->responseText = $responseText;
        $this->code = $code;
        $this->contentType = 'text/html';

        return $this;
    }

    /**
     * JSON Response
     * @param array $object
     * @param int $code    The response code
     */
    public function json(?array $object = null, int $code = 200): Self
    {
        $this->responseText = \json_encode($object);
        $this->code = $code;
        $this->contentType = 'application/json';

        return $this;
    }

    /**
     * Send the response
     */
    public function send()
    {
        if ($this->responseText && !config('app.debug') && $this->contentType == 'text/html') {
            $this->responseText = \preg_replace("/<!--(.*?)-->|\s\B|\n/", "", $this->responseText);
        }
        http_response_code($this->code);
        header("Content-Type: " . $this->contentType);
        $this->responseBuffer = ob_start();
        echo $this->responseText;
        ob_end_flush();
        exit();
    }
}
