<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TaskStatus: string implements TranslatableInterface
{
    case to_do = 'to_do';
    case in_progress = 'in_progress';
    case completed = 'completed';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        $variableName = 'task_status.';
        return $translator->trans(match ($this) {
            self::to_do       => $variableName.self::to_do->value.'.label',
            self::in_progress => $variableName.self::in_progress->value.'.label',
            self::completed   => $variableName.self::completed->value.'.label',
        });
    }
}
