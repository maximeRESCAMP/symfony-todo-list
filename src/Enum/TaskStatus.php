<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TaskStatus: string implements TranslatableInterface
{
    case ToDo = 'to_do';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('task_status.' . $this->value);
    }
}
