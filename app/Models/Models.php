<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Models
 * @package App\Models
 *
 * @property int $id ID of model in DB
 * @property int $make_id ID of make of the car
 * @property string $name The name of the make
 */
class Models extends Model
{
    use HasFactory;

    /**
     * The table name id DB
     *
     * @var string
     */
    protected $table = 'models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'make_id',
        'name',
    ];
}
