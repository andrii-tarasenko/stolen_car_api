<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CarsManufacturer;
use Exception;

class UpdateParamsCarController extends Controller
{
    /**
     * Updates the list of car manufacturers and their models from a remote server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateModelAndMakes()
    {
        $makesUrl = 'https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json';
        $makes = $this->getDate($makesUrl);
            if (!empty($makes)) {
                foreach ($makes as $make) {
                    $modelUrl = 'https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformakeid/' . $make['Make_ID'] . '?format=json';
                    $models = $this->getDate($modelUrl);
                    if (!empty($models[0])) {
                        $manufacturer = CarsManufacturer::firstOrNew(['make_id' => $make['Make_ID']]);
                        $manufacturer->make_name = $make['Make_Name'];

                        if (!empty($models[0]['Model_ID'])) {
                            $manufacturer->model_id = $models[0]['Model_ID'];
                        }

                        if (!empty($models[0]['Model_Name'])) {
                            $manufacturer->model_name = $models[0]['Model_Name'];
                        }
                        $manufacturer->save();

                        die();
                    }
                }
                return response()->json([
                    'message' => 'All makes and models are updated',
                ]);
        }
    }

    /**
     * Get makes or  models
     *
     * @param $url https://vpic.nhtsa.dot.gov/api
     *
     * @return array
     */
    private function getDate($url): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return  json_decode($result, true)['Results'];
    }
}

