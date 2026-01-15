<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'isbn',
        'published_year',
        'stock',
    ];

    public function authors(): BelongsToMany{
        return $this->belongsToMany(Author::class);
    }
    public function loans(): HasMany{
        return $this->hasMany(Loan::class);
    }
}
