<?php

namespace Econnect\Vcard\Properties;

class Title extends Property
{
    public function __construct(protected string $title)
    {
    }

    public function __toString(): string
    {
        return "TITLE:{$this->title}";
    }
}
