<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedToolsBundle\Entity\Traits;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

trait UserBlameTranslatableTrait
{
    abstract public function getLocale();

    abstract protected function getTranslation(string $locale);

    #[Serializer\VirtualProperty(name: 'creator')]
    public function getCreator(): ?int
    {
        $translation = $this->getTranslation($this->getLocale());
        if (!$translation) {
            return null;
        }

        return $translation->getCreator()->getId();
    }

    public function setCreator(?UserInterface $creator): self
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            $translation = $this->createTranslation($this->locale);
        }

        $translation->setCreator($creator);

        return $this;
    }

    #[Serializer\VirtualProperty(name: 'changer')]
    public function getChanger(): ?int
    {
        $translation = $this->getTranslation($this->getLocale());
        if (!$translation) {
            return null;
        }

        return $translation->getChanger()->getId();
    }

    public function setChanger(?UserInterface $author): self
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            $translation = $this->createTranslation($this->locale);
        }

        $translation->setChanger($author);

        return $this;
    }

    #[Serializer\VirtualProperty(name: 'creatorName')]
    public function getCreatorName(): ?string
    {
        $translation = $this->getTranslation($this->getLocale());
        if (!$translation) {
            return null;
        }

        $creator = $translation->getCreator();
        if (!$creator) {
            return null;
        }

        // User has getContact()->getFullName()
        $contact = $creator->getContact();

        return $contact ? $contact->getFullName() : $creator->getUsername();
    }

    #[Serializer\VirtualProperty(name: 'changerName')]
    public function getChangerName(): ?string
    {
        $translation = $this->getTranslation($this->getLocale());
        if (!$translation) {
            return null;
        }

        $changer = $translation->getChanger();
        if (!$changer) {
            return null;
        }

        // User has getContact()->getFullName()
        $contact = $changer->getContact();

        return $contact ? $contact->getFullName() : $changer->getUsername();
    }
}
