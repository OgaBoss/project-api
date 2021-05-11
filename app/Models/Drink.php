<?php


namespace App\Models;


use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    use Uuids;
    use HasFactory;

    protected $fillable = ['name', 'safe_level'];

    protected $casts = [
        'safe_level' => 'integer'
    ];
}
