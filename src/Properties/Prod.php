<?php

namespace Econnect\Vcard\Properties;

class Prod extends Property
{
    public function __construct(protected string $prod)
    {
    }

    public function __toString(): string
    {
        return "PRODID:-//{$this->prod}";
    }
}
