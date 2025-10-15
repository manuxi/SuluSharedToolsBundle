<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedTools\Entity\Interfaces;

use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

interface AuthorTranslatableInterface
{
    public function getAuthor(): ?int;
    public function setAuthor(?ContactInterface $author);
}
