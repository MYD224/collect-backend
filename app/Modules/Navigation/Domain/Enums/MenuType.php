<?php

namespace App\Modules\Navigation\Domain\Enums;

enum MenuType: string
{
    case MENU = 'menu';
    case TAB = 'tab';


    public function value(): string
    {
        return $this->value;
    }
}
