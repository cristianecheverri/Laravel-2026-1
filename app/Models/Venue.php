<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = 'id_venue';

    protected $fillable = [
        'venue_name',
        'venue_address',
        'venue_max_capacity',
        'venue_image',
        'deleted_by_venue'
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'fk_venue_event', 'id_venue');
    }
}
