<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Animal::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $options = request()->json()->all();
        return response()->json([
            "success" => true,
            "data" => Animal::getList(isset($options["page"]) ? $options["page"] : 1, 50, $options),
            "message" => "Fetched",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json([
            "success" => true,
            "message" => Animal::createRecord(new Animal($request->json()->all())) ? "Created" : "Not Created",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function show(Animal $animal)
    {
        return response()->json([
            "success"   => true,
            "data"      => $animal,
            "message"   => "Fetched"
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        return response()->json([
            "success" => true,
            "message" => Animal::updateRecord($animal->animal_id, $animal->fill($request->json()->all())) ? "Updated" : "Not Updated",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        return response()->json([
            "success" => true,
            "message" => Animal::deleteRecord($animal->animal_id) ? "Deleted" : "Not Deleted",
        ]);
    }
}
