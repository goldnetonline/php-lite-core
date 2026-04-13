<?php

namespace Goldnetonline\PhpLiteCore\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TwigExtensions extends AbstractExtension implements GlobalsInterface
{

    private $loader;

    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    public function getGlobals(): array
    {
        return [
            'app' => app(),
            'request' => request(),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('assets', [$this, 'assets']),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('assets', [$this, 'assets']),
        ];
    }

    public function assets($value)
    {
        $s = preg_replace("/\/$/", "", request()->domain);
        $s .= "/";
        $s .= preg_replace("/^\//", "", $value);

        return $s;
    }
}
