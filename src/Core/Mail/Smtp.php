<?php
/*
 * File: Smtp.php
 * Project: Mail
 * File Created: Tuesday, 1st June 2021 10:28:38 am
 * Author: Temitayo Bodunrin (temitayo@camelcase.co)
 * -----
 * Last Modified: Tuesday, 1st June 2021 12:17:38 pm
 * Modified By: Temitayo Bodunrin (temitayo@camelcase.co)
 * -----
 * Copyright 2021, CamelCase Technologies Ltd
 */
namespace Goldnetonline\PhpLiteCore\Mail;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class Smtp extends BaseMailDriver
{

    private $transport;
    private $mailer;

    /**
     * Configure the email engine
     */
    protected function configure(): self
    {
        $scheme = (string) $this->config('encryption', 'tls');
        $host = (string) $this->config('host');
        $port = (int) $this->config('port', 587);
        $username = rawurlencode((string) $this->config('username'));
        $password = rawurlencode((string) $this->config('password'));

        $dsn = sprintf('%s://%s:%s@%s:%d', $scheme, $username, $password, $host, $port);
        $this->transport = Transport::fromDsn($dsn);
        $this->mailer = new Mailer($this->transport);
        return $this;
    }

    /**
     * Send the message
     */
    public function send(): self
    {
        $message = (new Email())
            ->from((string) $this->from)
            ->to(...$this->toList)
            ->subject((string) $this->subject)
            ->text(strip_tags((string) $this->messageString))
            ->html((string) $this->messageString);

        $this->mailer->send($message);

        return $this;
    }
}
