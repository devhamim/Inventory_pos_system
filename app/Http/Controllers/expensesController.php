<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Str;

class expensesController extends Controller
{
     /**
     * Display an all purchases.
     */
    public function allExpenses()
    {

        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $expenses = Expenses::filter(request(['search']))
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('expenses.expenses', [
            'expenses' => $expenses
        ]);
    }


     /**
     * Show the form for creating a new resource.
     */
    public function createExpenses()
    {
        return view('expenses.create-expenses');
    }

     /**
     * Store a newly created resource in storage.
     */
    public function storeExpenses(Request $request)
    {
        $rules = [
            'expenses_date' => 'required|string',
            'name' => 'required|string',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'recipient_name' => 'required|string',
            'payment_type' => 'required|string',
            'expenses_note' => '',
        ];

        $validatedData = $request->validate($rules);
        $validatedData['expenses_id'] = 'ERS' . rand(100000, 999999);
        $validatedData['created_at'] = Carbon::now();

        if ($file = $request->file('image')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/expenses/';

            // Store an image to Storage
            $file->storeAs($path, $fileName);
            $validatedData['image'] = $fileName;
        }

        Expenses::insert($validatedData);

        return Redirect::route('expenses.allExpenses')->with('success', 'Expenses has been created!');
    }
}
