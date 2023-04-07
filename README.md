# stolen_car_api

Url for api

1. Add cars to db method post link - http://localhost/api/add/stolen_cars';
    a) paramenters:
        - name
        - registration_number
         - color
        - vin_code
2. Get cars from db method get - http://localhost/api/get/stolen_cars';
   a) paramenters:
   - make
   - model
   - year
   - search
   - name
   - registration_number
   - color
   - vin_code
   - sort_field
   - sort_order
3. Update cars from db method put - http://localhost/api/update/stolen_cars/{id};
   a) paramenters:
    - id of row in the table
4. Delete car from db method delete - http://localhost/api/delete/stolen_cars/{id};
   a) paramenters:
    - id of row in the table
5. Export xls file of stolen cars method get- http://localhost/api/export/stolen_cars;
   a) paramenters:
    - make
    - model
    - year
    - search
    - name
    - registration_number
    - color
    - vin_code
    - sort_field
    - sort_order
6. Update makes and models method get - http://localhost/api/update;
   a) paramenters:
    - id of row in the table
7. autocomplete makes and get models - http://localhost/api/autocomplete/{make};
   a) paramenters:
    - make (ford of for..)

All endPoints was write in App\Http\Controllers\API\CarController exept autocomplete(App\Http\Controllers\API\CarController\UpdateParamsCarController)
Updating the database is performed once a month using a scheduler and a created command. It can also be done remotely by request.
