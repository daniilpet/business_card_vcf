<?php

namespace Econnect\Vcard\Properties;

class SocialNetwork extends Property
{
    public function __construct(protected string $type, protected string $url)
    {
    }

    public function __toString(): string
    {
        return "X-SOCIALPROFILE;type=" . implode(":", [$this->type, $this->url]);
    }
}
