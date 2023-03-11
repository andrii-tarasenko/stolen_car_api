<!DOCTYPE html>
<html>
<head>
    <title>Cars Index</title>
    <!-- Підключення необхідних CSS-стилів -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .car {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Cars Index</h1>
    <hr>
    <form action="{{ route('cars.index') }}" method="GET">
        <div class="form-group">
            <label for="maker">Maker:</label>
            <input type="text" name="maker" class="form-control" value="{{ Request::get('maker') }}">
        </div>
        <div class="form-group">
            <label for="model">Model:</label>
            <input type="text" name="model" class="form-control" value="{{ Request::get('model') }}">
        </div>
        <div class="form-group">
            <label for="year">Year:</label>
            <input type="number" name="year" class="form-control" value="{{ Request::get('year') }}">
        </div>
        <div class="form-group">
            <label for="orderBy">Order By:</label>
            <select name="orderBy" class="form-control">
                <option value="id" {{ Request::get('orderBy') == 'id' ? 'selected' : '' }}>ID</option>
                <option value="name" {{ Request::get('orderBy') == 'name' ? 'selected' : '' }}>Name</option>
                <option value="year" {{ Request::get('orderBy') == 'year' ? 'selected' : '' }}>Year</option>
            </select>
        </div>
        <div class="form-group">
            <label for="order">Order:</label>
            <select name="order" class="form-control">
                <option value="asc" {{ Request::get('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ Request::get('order') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
        </div>
        <div class="form-group">
            <label for="search">Search:</label>
            <input type="text" name="search" class="form-control" value="{{ Request::get('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <hr>
    @foreach ($cars as $car)
        <div class="car">
            <h2>{{ $car->name }}</h2>
            <p>{{ $car->carModel->name }}</p>
            <p>{{ $car->carModel->carMaker->name }}</p>
            <p>{{ $car->year }}</p>
            <p>{{ $car->vin }}</p>
            <p>{{ $car->number }}</p>
        </div>
    @endforeach

    {{ $cars->links() }}
</div>
<!-- Підключення необхідних
