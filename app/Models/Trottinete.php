<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trottinete extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price_per_minute',
    ];

    public function clients(): BelongsToMany
    {
        return $this->BelongsToMany(Client::class, "reservations");
    }

    public function address(): HasOne
    {
        return $this->HasOne(Address::class);
    }
}
