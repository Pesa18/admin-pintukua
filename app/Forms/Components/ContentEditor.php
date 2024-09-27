<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class ContentEditor extends Field
{
    protected string $view = 'forms.components.content-editor';

    protected function setUp(): void
    {
        parent::setUp();
    }
}
