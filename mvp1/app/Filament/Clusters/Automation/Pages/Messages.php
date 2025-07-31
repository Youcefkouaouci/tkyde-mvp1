<?php

namespace App\Filament\Clusters\Automation\Pages;

use App\Filament\Clusters\Automation;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class Messages extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.clusters.automation.pages.messages';

    protected static ?string $cluster = Automation::class;

    protected static ?int $navigationSort = 2;

    public function getExtraBodyAttributes(): array
    {
        return [
            'class' => 'no-title-page no-heading-page',
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
