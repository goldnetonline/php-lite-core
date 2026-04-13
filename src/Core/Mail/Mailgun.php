<?php
namespace Goldnetonline\PhpLiteCore\Mail;

use Mailgun\Mailgun as MailgunEngine;

class Mailgun extends BaseMailDriver
{

    private $apiKey;
    private $mailer;

    /**
     * Configure the email engine
     */
    protected function configure(): Self
    {
        $this->apiKey = trim($this->config('api_key'));
        $this->mailer = MailgunEngine::create($this->apiKey);
        $this->from($this->config('mail_from'));

        return $this;
    }

    /**
     * Send the message
     */
    public function send(): Self
    {

        $this->mailer->messages()->send(
            $this->config('domain_name'), [
                'from' => $this->from,
                'to' => $this->toList,
                'subject' => $this->subject,
                'text' => strip_tags($this->messageString),
                'html' => $this->messageString,
            ]
        );

        return $this;
    }
}
