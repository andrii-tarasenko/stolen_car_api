<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Class StolenCars
 *
 * @package App\carsManufacturer
 *
 * @property int $id ID of the car record in the database
 * @property string $name Car make
 * @property string $registration_number Car registration number
 * @property string $color Car color
 * @property string $make Car manufacturer
 * @property string $model Car model
 * @property string $vin_code Car VIN code
 * @property string $year Car production year
 */
class StolenCars extends Model
{
    use HasFactory;

    /**
     * Назва таблиці бази даних, що відповідає цьому класу
     *
     * @var string
     */
    protected $table = 'stolen_cars';

    /**
     * Список полів таблиці, що можуть бути заповнені через масове присвоєння
     *
     * @var array
     */
    public $fillable = [
        'name',
        'registration_number',
        'color',
        'make',
        'model',
        'vin_code',
        'year'
    ];

    public function decodeVinCode($vinCode)
    {
        $carParameters = [];
        if (strlen($vinCode) == 17) {
            $url = 'https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvaluesextended/' . $vinCode . '?format=json';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $data = json_decode($response, true);
                $messageResponce = 'Results returned successfully.';

                if (strstr($data['Message'], $messageResponce) == false) {
                    $this->xml_data->addChild('message', 'We did not find this car');

                    return response($this->xml_data->asXML(), 400)->header('Content-Type', 'application/xml');
                } else {
                    $carParameters = [
                        'make' => $data['Results'][0]['Make'],
                        'model' => $data['Results'][0]['Model'],
                        'year' => $data['Results'][0]['ModelYear'],
                        ];
                }
            }
        }

        return $carParameters;
    }

    public static function ValidateRequest($request)
    {
        $validator = Validator::make($request, [
            'name' => 'string|max:40',
            'registration_number' => 'string|max:20',
            'color' => 'string|max:26',
            'vin_code' => 'string|size:17',
            'make' => 'string',
            'model' => 'string',
            'year' => ['numeric'],
            'search' => 'string',
            'sort_field' => 'string',
            'sort_order' => 'string',
            'per_page' => 'integer',
        ]);

        if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors(), 'message' => 'Validation error'], 400);
        }
    }
}
