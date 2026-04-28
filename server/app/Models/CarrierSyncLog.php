<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierSyncLog extends Model
{
    protected $fillable = [
        'rmis_id',
        'mc_number',
        'action',
        'message',
        'api_timestamp',
    ];

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'rmis_id', 'rmis_id');
    }
}
