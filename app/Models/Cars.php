<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cars
 * @package App\Models
 *
 * @property int $id ID запису про автомобіль в базі даних
 * @property string $name Марка автомобіля
 * @property string $registration_number Реєстраційний номер автомобіля
 * @property string $color Колір автомобіля
 * @property string $make Виробник автомобіля
 * @property string $model Модель автомобіля
 * @property string $vin_code VIN код автомобіля
 * @property string $year Рік випуску автомобіля
 */
class Cars extends Model
{
    use HasFactory;

    /**
     * Назва таблиці бази даних, що відповідає цьому класу
     *
     * @var string
     */
    protected $table = 'cars';

    /**
     * Список полів таблиці, що можуть бути заповнені через масове присвоєння
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'registration_number',
        'color',
        'make',
        'model',
        'vin_code',
        'year'
    ];
}
