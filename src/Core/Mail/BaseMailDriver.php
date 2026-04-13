<?php
namespace Goldnetonline\PhpLiteCore\Mail;

use Goldnetonline\PhpLiteCore\Contracts\Mailer;

abstract class BaseMailDriver implements Mailer
{
    protected $config;
    protected $viewEngine;
    protected $toList = [];
    protected $messageString = null;
    protected $subject;
    protected $from;

    public function __construct(array $config)
    {
        $this->config = $config;

        $this->configure();
    }

    protected function config($key, $default = null)
    {
        return isset($this->config[$key]) && !in_array($this->config[$key], ["null"]) ? $this->config[$key] : $default;
    }

    /**
     * Configure the email engine
     */
    protected function configure(): Self
    {
        return $this;
    }

    public function setViewEngine($engine)
    {
        $this->viewEngine = $engine;
        return $this;
    }

    /**
     * The email from
     *
     * @param string $from
     */
    public function from(string $from): Self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Single to or an array of to
     *
     * @param string|array $to
     */
    public function to($to): Self
    {
        $this->toList[] = $to;
        return $this;
    }

    /**
     * The subject of the email
     *
     * @param string $subject
     */
    public function subject(string $subject): Self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * The string message to send
     *
     * @param string $message
     */

    public function message(string $message): Self
    {
        $this->messageString = $message;
        return $this;

    }

    /**
     * Compile a message tempplate to string
     *
     * @param string $template
     */
    public function template(string $template, array $context = []): Self
    {
        $this->messageString = $this->viewEngine->make($template, $context);
        return $this;

    }

    /**
     * Send the message
     */
    public function send(): Self
    {
        return $this;
    }
}
