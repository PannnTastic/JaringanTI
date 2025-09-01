<?php

namespace App\Filament\Resources\NotaDinasResource\Pages;

use App\Filament\Resources\NotaDinasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotaDinas extends EditRecord
{
    protected static string $resource = NotaDinasResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
