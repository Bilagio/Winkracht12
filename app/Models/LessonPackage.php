<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPackage extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
        'max_participants',
        'is_active'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the reservations for this lesson package.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
