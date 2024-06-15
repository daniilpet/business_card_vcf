<?php

namespace Econnect\Vcard\Properties;

class Org extends Property
{
    public function __construct(
        protected ?string $company = null,
        protected ?string $unit = null,
        protected ?string $team = null
    ) {
    }

    public function __toString(): string
    {
        return "ORG;CHARSET=utf-8:{$this->company};{$this->unit};{$this->team}";
    }
}
