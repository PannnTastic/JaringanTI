<?php

namespace App\Filament\Resources\SPKResource\Pages;

use App\Filament\Resources\SPKResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSPK extends CreateRecord
{
    protected static string $resource = SPKResource::class;
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
