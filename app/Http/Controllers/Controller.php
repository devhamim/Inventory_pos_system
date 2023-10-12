<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    // dashboard
    function dashboard(){
        // Get the current date and extract the current month and year
        $currentDate = now();
        $currentMonth = $currentDate->format('m');
        $currentYear = $currentDate->format('Y');

        // Query the database to sum the purchase amounts for the current month
        $currentMonthpurchases = DB::table('purchases')
            ->whereYear('purchase_date', $currentYear)
            ->whereMonth('purchase_date', $currentMonth)
            ->sum('total_amount');
            
        // Query the database to sum the purchase amounts for the current month
        $currentMonthexpenses = DB::table('expenses')
            ->whereYear('expenses_date', $currentYear)
            ->whereMonth('expenses_date', $currentMonth)
            ->sum('amount');
            
            $currentmonth = $currentMonthpurchases+$currentMonthexpenses;


        // total pay
        $currentMonthorder = DB::table('orders')
            ->whereYear('order_date', $currentYear)
            ->whereMonth('order_date', $currentMonth)
            ->sum('pay');
            
        // total due
        $currentMonthdue = DB::table('orders')
            ->whereYear('order_date', $currentYear)
            ->whereMonth('order_date', $currentMonth)
            ->sum('due');

            // total order
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        
            // Query the database to count orders within the current month
            $totalOrders = DB::table('orders')
                ->whereBetween('order_date', [$startDate, $endDate])
                ->count();

        return view('dashboard.index', [
            'currentmonth'=>$currentmonth,
            'currentMonthorder'=>$currentMonthorder,
            'currentMonthdue'=>$currentMonthdue,
            'totalOrders'=>$totalOrders,
        ]);
    }
}
