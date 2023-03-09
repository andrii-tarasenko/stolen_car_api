<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VinCodeExists;
use App\Models\StolenCars;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SimpleXMLElement;

class CarController extends Controller
{
    /**
     * The table name associated with class.
     *
     * @var object SimpleXMLElement
     */
    public $xml_data;

    public function __construct()
    {
        $this->xml_data = new SimpleXMLElement('<?xml version="1.0"?><cars></cars>');
    }

    /**
     * Store a newly created stolen car in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validated = StolenCars::ValidateRequest($request->all());

        if (isset($validated->original['errors'])) {
            return $validated;
        }

        $cars = new StolenCars();
        $carParameters = $cars->decodeVinCode($request->vin_code);

        $cars->name = $request->name;
        $cars->registration_number = $request->registration_number;
        $cars->color = $request->color;
        $cars->vin_code = $request->vin_code;
        $cars->make = $carParameters['make'];
        $cars->model = $carParameters['model'];
        $cars->year = $carParameters['year'];

        if ($cars->save()) {
            $this->xml_data->addChild('message', 'Cars added successfully');

            foreach ($cars->toArray() as $key => $car) {
                $this->xml_data->addChild($key, $car);
            }
            return response($this->xml_data->asXML(), 200)->header('Content-Type', 'application/xml');
        } else {
            $this->xml_data->addChild('message', 'Cars was not added');

            return response($this->xml_data->asXML(), 200)->header('Content-Type', 'application/xml');
        }
    }

    /**
     * Display a listing of the stolen cars.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $validated = StolenCars::ValidateRequest($request->all());

        if (isset($validated->original['errors'])) {
            return $validated;
        }

        $query = StolenCars::query();

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

        $this->xml_data = new SimpleXMLElement('<?xml version="1.0"?><cars></cars>');
        foreach ($cars as $car) {
            $car_data = $this->xml_data->addChild('car');
            $car_data->addChild('id', $car->id);
            $car_data->addChild('name', $car->name);
            $car_data->addChild('registration_number', $car->registration_number);
            $car_data->addChild('color', $car->color);
            $car_data->addChild('vin_code', $car->vin_code);
            $car_data->addChild('make', $car->make);
            $car_data->addChild('model', $car->model);
            $car_data->addChild('year', $car->year);
        }

        return response($this->xml_data->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Update the specified stolen car in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $validated = StolenCars::ValidateRequest($request->all());

        if (isset($validated->original['errors'])) {
            return $validated;
        }

        $car = StolenCars::findOrFail($id);

        foreach ($request->all() as $key => $value) {
            $car->$key = $request->$key;
        }

        if ($car->save()) {
            return response()->json([
                'message' => 'Stolen car updated successfully!',
                'data' => $car
            ]);
        } else {
            return response()->json([
                'message' => 'Stolen car was not updated!',
            ]);
        }
    }

    /**
     * Remove the specified stolen car from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $stolenCar = StolenCars::findOrFail($id);
        $stolenCar->delete();

        return response()->json(['message' => 'Stolen car successfully deleted']);
    }
}
