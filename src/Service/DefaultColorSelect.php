<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedToolsBundle\Service;

use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultColorSelect
{
    private array $typesMap = [
        'primary' => 'sulu_shared_tools.service.default_color_select.primary',
        'secondary' => 'sulu_shared_tools.service.default_color_select.secondary',
        'success' => 'sulu_shared_tools.service.default_color_select.success',
        'danger' => 'sulu_shared_tools.service.default_color_select.danger',
        'warning' => 'sulu_shared_tools.service.default_color_select.warning',
        'info' => 'sulu_shared_tools.service.default_color_select.info',
        'dark' => 'sulu_shared_tools.service.default_color_select.dark',
        'light' => 'sulu_shared_tools.service.default_color_select.light',
    ];
    private string $defaultValue = 'light';

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getValues(): array
    {
        $values = [];

        foreach ($this->typesMap as $code => $toTrans) {
            $values[] = [
                'name' => $code,
                'title' => $this->translator->trans($toTrans, [], 'admin'),
            ];
        }

        return $values;
    }

    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }
}
