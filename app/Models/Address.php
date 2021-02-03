<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rue',
        'ville',
        'region',
        'code_postal',
        'pays',
        'photo',
        'is_green'
    ];

    public function trottinete(): BelongsTo
    {
        return $this->BelongsTo(Trottinete::class);
    }
}
