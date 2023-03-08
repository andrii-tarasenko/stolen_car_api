<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Cars;
use SimpleXMLElement;

class CarController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Request
     */
    public function create(Request $request)
    {
        $token = csrf_token();

        // валідація вхідних даних
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'registration_number' => 'required|string',
            'color' => 'string',
            'vin_code' => 'required|string'
        ]);

        if ($validator->fails()) {
            $failsMessage = ['message' => 'Validation error', 'errors' => $validator->errors()];
            $xml = new SimpleXMLElement('<root/>');
            array_walk_recursive($failsMessage, array($xml, 'addChild'));
            $xmlString = $xml->asXML();

            return response($xmlString, 400)->header('Content-Type', 'text/xml');
        }

        // запит на API для отримання інформації про авто
        $url = "https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvaluesextended/{$request->vin_code}?format=json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        $messageResponce = 'Results returned successfully.';

        // перевірка, чи вдалося отримати інформацію про авто
        if (strstr($data['Message'], $messageResponce) == false) {
            $failsMessage = ['message' => 'Error while decoding VIN code'];
            $xml = new SimpleXMLElement('<root/>');
            array_walk_recursive($failsMessage, array($xml, 'addChild'));
            $xmlString = $xml->asXML();

            return response($xmlString, 400)->header('Content-Type', 'text/xml');
        }

        // отримання потрібних даних про авто
        $make = $data['Results'][0]['Make'];
        $model = $data['Results'][0]['Model'];
        $year = $data['Results'][0]['ModelYear'];

        // запис даних до БД
        $cars = new Cars;
        $cars->name = $request->name;
        $cars->registration_number = $request->registration_number;
        $cars->color = $request->color;
        $cars->vin_code = $request->vin_code;
        $cars->make = $make;
        $cars->model = $model;
        $cars->year = $year;

        if ($cars->save()) {
            $succesesResponce = [ 'message' => 'Cars added successfully', 'cars' => $cars];
            $xml = new SimpleXMLElement('<root/>');
            array_walk_recursive($succesesResponce, array($xml, 'addChild'));
            $xmlString = $xml->asXML();

            return response($xmlString, 200)->header('Content-Type', 'text/xml');
        } else {
            $failsMessage = ['message' => 'Cars was not added'];
            $xml = new SimpleXMLElement('<root/>');
            array_walk_recursive($failsMessage, array($xml, 'addChild'));
            $xmlString = $xml->asXML();

            return response($xmlString, 400)->header('Content-Type', 'text/xml');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Request
     */
    public function index(Request $request)
    {
        $query = Cars::query();

        // фільтрація за маркою
        if ($request->has('make')) {
            $query->where('make', $request->make);
        }

        // фільтрація за моделлю
        if ($request->has('model')) {
            $query->where('model', $request->model);
        }

        // фільтрація за роком
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }
        // пошук по імені, номерному знаку, вин-коду
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('registration_number', 'like', "%$search%")
                    ->orWhere('vin_code', 'like', "%$search%");
            });
        }

        // сортування
        $sortField = $request->has('sort_field') ? $request->sort_field : 'id';
        $sortOrder = $request->has('sort_order') ? $request->sort_order : 'asc';
        $query->orderBy($sortField, $sortOrder);

        // пагінація
        $perPage = $request->has('per_page') ? $request->per_page : 10;
        $cars = $query->paginate($perPage);

        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><cars></cars>');
        foreach ($cars as $car) {
            $car_data = $xml_data->addChild('car');
            $car_data->addChild('id', $car->id);
            $car_data->addChild('name', $car->name);
            $car_data->addChild('registration_number', $car->registration_number);
            $car_data->addChild('color', $car->color);
            $car_data->addChild('vin_code', $car->vin_code);
            $car_data->addChild('make', $car->make);
            $car_data->addChild('model', $car->model);
            $car_data->addChild('year', $car->year);
        }

        return response($xml_data->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
