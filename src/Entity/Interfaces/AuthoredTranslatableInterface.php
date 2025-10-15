<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedToolsBundle\Entity\Interfaces;

interface AuthoredTranslatableInterface
{
    public function getAuthored(): ?\DateTime;
    public function setAuthored(?\DateTime $authored);
}
