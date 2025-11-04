<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedToolsBundle\Entity\Interfaces;

interface UserBlameTranslatableInterface
{
    /**
     * Returns the user id from the translation object which created it.
     */
    public function getCreator(): ?int;

    /**
     * Returns the user id from the translation object that changed it the last time.
     */
    public function getChanger(): ?int;

    /**
     * Returns the users fullname for displaying purposes only.
     */
    public function getCreatorName(): ?string;

    /**
     * Returns the users fullname for displaying purposes only.
     */
    public function getChangerName(): ?string;
}
