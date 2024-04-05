<?php

namespace App\Http\Controllers;


use App\Models\Account;
use App\Models\Status;

use App\Models\Shipment;

use Illuminate\Http\Request;

class ReportShipmentController extends Controller
{
    //


    public function index(){
        $Accounts = Account::pluck('name_arabic','id')->all();
        $Statuses = Status::pluck('name_arabic','id')->all();
        // dd($Statuses);

        return view('reports.invoices_report', compact('Accounts','Statuses'));
    }
    public function shipmentByStautasResult(Request $request){
        $Accounts = Account::pluck('name_arabic','id')->all();
        $Statuses = Status::pluck('name_arabic','id')->all();
        // $request->validate([
        //     'account_id' => 'required',
        //     'start_date' => 'required|date',
        //     'end_date' => 'required|date',
        //     'status_id' => 'required',
        // ]);
    
        // dd($request->all());
        $accountId = $request->input('account_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusId = $request->input('status_id');

         // تنفيذ الاستعلام للبحث في جدول الشحنات باستخدام نموذج الشحنة
            $shipments = Shipment::where('account_id', $accountId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status_id', $statusId)
            ->get();


                $accountName = Account::find($request->account_id)->name_arabic;
                $status = Status::find($request->status_id)->name_arabic;
                $startDate = $request->start_date;
                $endDate = $request->end_date;
            // dd($shipments);
        // إعادة النتائج كاستجابة JSON مثلاً
        return view('reports.invoices_report', compact('shipments','Accounts','Statuses', 'accountName', 'status', 'startDate', 'endDate'));
    }
}
