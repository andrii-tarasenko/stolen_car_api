<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class carsManufacturer
 * @package App\carsManufacturer
 *
 * @property int $id
 * @property int $make_id ID of make of the car
 * @property int $model_id ID of model of the car
 * @property string $model_name The name of the model
 * @property string $make_name The name of the make
 */
class CarsManufacturer extends Model
{
    use HasFactory;

    /**
     * The table name id DB
     *
     * @var string
     */
    protected $table = 'cars_manufacturer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'make_id',
        'make_name',
        'model_id',
        'model_name',
    ];


}
