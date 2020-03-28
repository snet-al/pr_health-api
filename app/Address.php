<?php

namespace App;

use App\Traits\ChangeState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Address extends Model
{
    use ChangeState, SoftDeletes;

    protected $attributes = [
        'is_default' => false,
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'address',
        'city',
        'country',
        'zip_code',
        'is_default',
        'is_active',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getStreetAddress(Request $request)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($request->latitude).','.trim($request->longitude).'&sensor=false&key=AIzaSyBwAMPGGmh3NjVw31JMrDeTnzsmWY-I6CY&callback=initMap';
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        if ($status == 'OK') {
            return $data->results[0]->formatted_address;
        }

        return config('settings.default_address');
    }
}
