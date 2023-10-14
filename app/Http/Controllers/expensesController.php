<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Exception;

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

        $expenses_id = IdGenerator::generate([
            'table' => 'expenses',
            'field' => 'expenses_id',
            'length' => 10,
            'prefix' => 'ERS-'
        ]);

        $validatedData = $request->validate($rules);

        $validatedData['expenses_status'] = 0; // 0 = pending, 1 = approved
        $validatedData['expenses_id'] = $expenses_id;
        // $validatedData['expenses_id'] = 'ERS-' . rand(100000, 999999);
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

     /**
     * Display a purchase details.
     */
    public function expensesDetails(String $expenses_id)
    {
        $expenses = Expenses::with(['recipient_name','name'])
            ->where('id', $expenses_id)
            ->first();
        return view('expenses.details-expenses', [
            'expenses' => $expenses,
        ]);
    }

    // deleteExpenses
    public function deleteExpenses(String $expenses_id)
    {
        Expenses::where([
            'id' => $expenses_id,
            'expenses_status' => '0'
        ])->delete();

        return Redirect::route('expenses.allExpenses')->with('success', 'Expenses has been deleted!');
    }


        /**
     * Display an all purchases.
     */
    public function dailyExpensesReport()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $expenses = Expenses::filter(request(['search']))
            ->where('expenses_date', Carbon::now()->format('Y-m-d')) // 1 = approved
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('expenses.approved-expenses', [
            'expenses' => $expenses
        ]);
    }


        /**
     * Show the form input date for purchase report.
     */
    public function getExpensesReport()
    {
        return view('expenses.report-expenses');
    }


    /**
     * Handle request to get purchase report
     */
    public function exportExpensesReport(Request $request)
    {
        $rules = [
            'start_date' => 'required|string|date_format:Y-m-d',
            'end_date' => 'required|string|date_format:Y-m-d',
        ];

        $validatedData = $request->validate($rules);

        $sDate = $validatedData['start_date'];
        $eDate = $validatedData['end_date'];

        // $purchaseDetails = DB::table('purchases')
        //     ->whereBetween('purchases.purchase_date',[$sDate,$eDate])
        //     ->where('purchases.purchase_status','1')
        //     ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
        //     ->get();

        // $expenses = DB::table('expenses')
        //     ->join('expenses', '=', 'expenses.id')
        //     ->whereBetween('expenses.expenses_date',[$sDate,$eDate])
        //     ->select( 'expenses.expenses_id', 'expenses.expenses_date', 'expenses.name', 'expenses.category', 'expenses.amount', 'expenses.recipient_name')
        //     ->get();

        $expenses = DB::table('expenses as e1') // Assign alias 'e1' to the primary 'expenses' table
            ->join('expenses as e2', 'e1.id', '=', 'e2.id') // Assign alias 'e2' to the second 'expenses' table
            ->whereBetween('e1.expenses_date', [$sDate, $eDate])
            ->select('e1.expenses_id', 'e1.expenses_date', 'e1.name', 'e1.category', 'e1.amount', 'e1.recipient_name')
            ->get();



        $purchase_array [] = array(
            'Date',
            'expenses_id',
            'Name',
            'Category',
            'Recipient Name',
            'Amount',
        );

        foreach($expenses as $expense)
        {
            $expense_array[] = array(
                'Date'              => $expense->expenses_date,
                'Expenses id'       => $expense->expenses_id,
                'Name'              => $expense->name,
                'Category'          => $expense->category,
                'Recipient Name'    => $expense->recipient_name,
                'Amount'            => $expense->amount,
            );
        }

        $this->exportExcel($expense_array);
    }

    /**
     *This function loads the customer data from the database then converts it
     * into an Array that will be exported to Excel
     */
    public function exportExcel($expenses){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($expenses);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="purchase-report.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }
}
