<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_debut',
        'date_retour',
        'is_enabled',
        'trottinete_id',
        'client_id'
    ];

    public function transactions(): HasMany
    {
        return $this->HasMany(Transaction::class);
    }
}
