<?php

namespace App\Http\Controllers;
use App\Models\Shipment;
use App\Models\Status;
use App\Models\Account;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        $status = Status::withCount('shipment')->get();
        $currentMonth = Carbon::now()->month;
        $statusIds = Status::where('id', 1)
        ->orWhere('parent_id', 1)
        ->pluck('id'); // استخراج قيمة الـ id من الاستعلام السابق

        
        $statusActiveWithShipments = Shipment::whereIn('status_id', $statusIds)
                                            ->whereMonth('created_at', $currentMonth)
                                            ->sum('price');
        $accountCount = Account::count();
        $vehicleCount = Vehicle::count();
        
        $statusFinishedWithShipments = Shipment::where('status_id', 4)
        ->whereMonth('created_at', $currentMonth)
        ->sum('price');
        $accountsWithShipmentCounts = Account::withCount('shipments')->get();
        $Accounts = Account::where(function ($query) {
            $query->where('type', 'individual_shipper')
                  ->orWhere('type', 'business_shipper');
        })
        ->pluck('name_arabic', 'id');
        // dd($Accounts);
      
        return view('dashborad' , compact('statusActiveWithShipments',
        'accountCount',
        'vehicleCount' ,
        'statusFinishedWithShipments',
        'status',
        'Accounts',
    ));
    }
}
