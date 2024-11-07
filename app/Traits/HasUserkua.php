<?php

namespace App\Traits;

use Filament\Facades\Filament;
use Spatie\Permission\Traits\HasRoles;

trait HasUserkua
{
    use HasRoles;
    public function isUserkua()
    {
        // dd($this->hasRole());
        if (Filament::getTenant()->id == 3) {
            return true;
        }
        return false;
    }
}
