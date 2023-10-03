<?php

namespace App\Http\Controllers;

use App\Models\manufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per_page parameter must be an integer between 1 and 100.');
        }

        $manufacturers = manufacturer::filter(request(['search']))
          ->sortable()
          ->paginate($row)
          ->appends(request()->query());
        return view('manufacturer.index', [
            'manufacturers'=>$manufacturers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manufacturer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:manufacturers,name',
            'slug' => 'required|unique:manufacturers,slug|alpha_dash',
        ];

        $validatedData = $request->validate($rules);

        manufacturer::create($validatedData);

        return Redirect::route('manufacturer.index')->with('success', 'Manufacturer has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(400);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(manufacturer $manufacturer)
    {
        return view('manufacturer.edit',[
            'manufacturer'=>$manufacturer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, manufacturer $manufacturer)
    {
        // print_r($manufacturer);
        // die();
        $rules = [
            'name' => 'required|unique:manufacturers,name,'.$manufacturer->id,
            'slug' => 'required|alpha_dash|unique:manufacturers,slug,'.$manufacturer->id,
        ];

        $validatedData = $request->validate($rules);

        manufacturer::where('slug', $manufacturer->slug)->update($validatedData);

        return Redirect::route('manufacturer.index')->with('success', 'Manufacturer has been updated!');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(manufacturer $manufacturer)
    {
        manufacturer::destroy($manufacturer->id);

        return Redirect::route('manufacturer.index')->with('success', 'Manufacturer has been deleted!');
    }
}
