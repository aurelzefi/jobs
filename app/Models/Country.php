<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webpatser\Countries\Countries;

class Country extends Countries
{
    use HasFactory;

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
