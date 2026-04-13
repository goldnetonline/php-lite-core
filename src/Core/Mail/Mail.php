<?php
namespace Goldnetonline\PhpLiteCore\Mail;

use Goldnetonline\PhpLiteCore\App;
use Goldnetonline\PhpLiteCore\Contracts\Mailer;
use Goldnetonline\PhpLiteCore\Exceptions\InvalidMailDriverException;

class Mail
{
    const SUPPORTED_DRIVERS = [
        'smtp', 'mailgun',
    ];

    private $mailDriversNamespace = "\Goldnetonline\PhpLiteCore\Mail";

    protected $app;
    protected $mailConfig;
    protected $mailEngine;

    public function __construct()
    {
        $this->configure();
    }

    public function configure()
    {
        $this->app = App::getInstance();
        $this->mailConfig = $this->app->getConfig('mail');
        $mailKlass = $this->mailDriversNamespace . "\\" . ucwords($this->mailConfig['driver']);

        if (
            !$this->mailConfig['driver']
            || !in_array($this->mailConfig['driver'], Self::SUPPORTED_DRIVERS)
            || !class_exists($mailKlass)
        ) {
            throw new InvalidMailDriverException($this->mailConfig['driver']);
        }

        $this->mailEngine = new $mailKlass($this->mailConfig[$this->mailConfig['driver']]);
        $this->mailEngine->setViewEngine($this->app->view);
        $this->mailEngine->from($this->mailConfig['default_from'] ?? "example@domain.com");

    }

    public function __call($name, $arguments)
    {
        return $this->mailEngine->{$name}(...$arguments);
    }

}
