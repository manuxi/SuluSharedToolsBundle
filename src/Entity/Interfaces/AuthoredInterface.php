<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedToolsBundle\Entity\Interfaces;

interface AuthoredInterface
{
    public function getAuthored(): ?\DateTime;
    public function setAuthored(?\DateTime $authored);
}
