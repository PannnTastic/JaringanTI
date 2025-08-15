<?php

namespace App\Filament\Resources\SubstationResource\Pages;

use App\Models\Substation;
use App\Filament\Resources\SubstationResource;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class Activation extends Page
{
    protected static string $resource = SubstationResource::class;

    protected static string $view = 'filament.resources.substation-resource.pages.activation';
    
    protected static ?string $navigationLabel = 'Aktivasi';
    
    protected static ?string $title = 'Aktivasi Substation';
    
    protected static ?string $navigationGroup = 'Substation';
    
    protected static ?int $navigationSort = 2;
    
    public function getSubstations()
    {
        return Substation::all();
    }
    
    public function toggleActivation($substationId)
    {
        $substation = Substation::find($substationId);
        
        if ($substation) {
            $substation->substation_status = !$substation->substation_status;
            $substation->save();
            
            $status = $substation->substation_status ? 'diaktifkan' : 'dinonaktifkan';
            
            Notification::make()
                ->title("Substation {$substation->substation_name} berhasil {$status}")
                ->success()
                ->send();
        }
    }
}
