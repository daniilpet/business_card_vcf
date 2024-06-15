<?php

namespace Econnect\Vcard\Properties;

class Note extends Property
{
    public function __construct(protected string $note)
    {
    }

    public function __toString(): string
    {
        return 'NOTE;CHARSET=utf-8:' . preg_replace('/[\r\n]+/', '\n', $this->note);
    }
}
