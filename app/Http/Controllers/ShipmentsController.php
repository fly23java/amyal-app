<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShipmentsFormRequest;
use App\Models\City;
use App\Models\Goods;
use App\Models\Shipment;
use App\Models\Status;
use App\Models\StatusChange;
use App\Models\Account;
use App\Models\User;
use App\Models\VehicleType;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\ShipmentDeliveryDetail;
use App\Models\Vehicle;
use Exception;
use View;
use Auth;
use App\Mail\CreateShipmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Http\Services\ShipmentService;
use App\Traits\ApiResponse;
use App\Traits\AuditLog;

class ShipmentsController extends Controller
{
    use ApiResponse, AuditLog;

    /**
     * The shipment service instance.
     *
     * @var \App\Http\Services\ShipmentService
     */
    protected $shipmentService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Http\Services\ShipmentService  $shipmentService
     * @return void
     */
    public function __construct(ShipmentService $shipmentService)
    {
        $this->middleware('auth');
        $this->middleware('permission:view_shipments')->only(['index', 'show']);
        $this->middleware('permission:create_shipments')->only(['create', 'store']);
        $this->middleware('permission:edit_shipments')->only(['edit', 'update']);
        $this->middleware('permission:delete_shipments')->only(['destroy']);
        
        $this->shipmentService = $shipmentService;
    }

    /**
     * Display a listing of the shipments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Shipment::with([
                'account:id,name_arabic',
                'status:id,name_arabic',
                'loadingCity:id,name_arabic',
                'unloadingCity:id,name_arabic',
                'vehicleType:id,name_arabic',
                'goods:id,name_arabic',
                'supervisor:id,name',
                'deliveryDetail.vehicle.account:id,name_arabic'
            ]);

            // Apply search
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Apply filters
            if ($request->filled('filters')) {
                $query->filter($request->filters);
            }

            // Apply sorting
            if ($request->filled('sort_by')) {
                $query->sort($request->sort_by, $request->get('sort_direction', 'desc'));
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Get paginated results
            $perPage = $request->get('per_page', 15);
            $shipments = $query->paginate($perPage);

            // Get additional data for the view
            $vehicles = Vehicle::select('id', 'plate_number', 'account_id')
                             ->with('account:id,name_arabic')
                             ->get();
            
            $statuses = Status::withCount('shipments')->get();
            
            $statistics = Shipment::getStatistics();

            // Log the action
            $this->logAction('view', 'shipments', null, 'عرض قائمة الشحنات');

            if ($request->wantsJson()) {
                return $this->successResponse([
                    'shipments' => $shipments,
                    'vehicles' => $vehicles,
                    'statuses' => $statuses,
                    'statistics' => $statistics
                ], 'تم جلب الشحنات بنجاح');
            }

            return view('shipments.index', compact(
                'shipments',
                'vehicles',
                'statuses',
                'statistics'
            ));

        } catch (Exception $e) {
            Log::error('Error in shipments index: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء جلب الشحنات', 500);
            }

            return back()->with('error', 'حدث خطأ أثناء جلب الشحنات');
        }
    }

    /**
     * Show the form for creating a new shipment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $accounts = Account::whereIn('type', ['individual_shipper', 'business_shipper'])
                             ->select('id', 'name_arabic', 'type')
                             ->get()
                             ->groupBy('type');

            $users = User::active()->select('id', 'name', 'type')->get();
            $cities = City::select('id', 'name_arabic', 'country_id')->get();
            $vehicleTypes = VehicleType::select('id', 'name_arabic', 'capacity')->get();
            $goods = Goods::select('id', 'name_arabic', 'category')->get();
            $statuses = Status::active()->select('id', 'name_arabic', 'color')->get();

            // Get default values
            $defaultStatus = Status::where('is_default', true)->first();
            $defaultVehicleType = VehicleType::where('is_default', true)->first();

            return view('shipments.create', compact(
                'accounts',
                'users',
                'cities',
                'vehicleTypes',
                'goods',
                'statuses',
                'defaultStatus',
                'defaultVehicleType'
            ));

        } catch (Exception $e) {
            Log::error('Error in shipments create: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل صفحة إنشاء الشحنة');
        }
    }

    /**
     * Store a new shipment in the storage.
     *
     * @param  \App\Http\Requests\ShipmentsFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(ShipmentsFormRequest $request)
    {
        try {
            DB::beginTransaction();

            // Generate serial number if not provided
            if (!$request->filled('serial_number')) {
                $request->merge(['serial_number' => Shipment::generateSerialNumber()]);
            }

            // Store the shipment
            $shipmentData = $this->shipmentService->store($request->all());
            
            if (!$shipmentData['success']) {
                throw new Exception($shipmentData['message']);
            }

            $shipmentId = $shipmentData['shipmentId'];
            $shipment = Shipment::findOrFail($shipmentId);

            // Send notifications
            $this->sendShipmentNotifications($shipment, 'created');

            // Log the action
            $this->logAction('create', 'shipments', $shipmentId, 'إنشاء شحنة جديدة: ' . $shipment->serial_number);

            DB::commit();

            if ($request->wantsJson()) {
                return $this->successResponse([
                    'shipment' => $shipment->load(['account', 'status', 'loadingCity', 'unloadingCity']),
                    'redirect_url' => route('shipments.show', $shipmentId)
                ], 'تم إنشاء الشحنة بنجاح');
            }

            return redirect()->route('shipments.show', $shipmentId)
                           ->with('success', 'تم إنشاء الشحنة بنجاح');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in shipments store: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء إنشاء الشحنة: ' . $e->getMessage(), 500);
            }

            return back()->withInput()
                        ->with('error', 'حدث خطأ أثناء إنشاء الشحنة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified shipment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $shipment = Shipment::with([
                'account',
                'status',
                'loadingCity',
                'unloadingCity',
                'vehicleType',
                'goods',
                'supervisor',
                'deliveryDetail.vehicle.account',
                'deliveryDetail.driver',
                'statusChanges.changedBy',
                'statusChanges.oldStatus',
                'statusChanges.newStatus'
            ])->findOrFail($id);

            // Check permissions
            if (!auth()->user()->can('view_shipments') && 
                !$shipment->supervisor_user_id === auth()->id()) {
                abort(403, 'ليس لديك صلاحية لعرض هذه الشحنة');
            }

            // Get related data
            $relatedShipments = Shipment::where('account_id', $shipment->account_id)
                                      ->where('id', '!=', $id)
                                      ->limit(5)
                                      ->get();

            // Log the action
            $this->logAction('view', 'shipments', $id, 'عرض الشحنة: ' . $shipment->serial_number);

            if (request()->wantsJson()) {
                return $this->successResponse([
                    'shipment' => $shipment,
                    'related_shipments' => $relatedShipments
                ], 'تم جلب الشحنة بنجاح');
            }

            return view('shipments.show', compact('shipment', 'relatedShipments'));

        } catch (Exception $e) {
            Log::error('Error in shipments show: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء جلب الشحنة', 500);
            }

            return back()->with('error', 'حدث خطأ أثناء جلب الشحنة');
        }
    }

    /**
     * Show the form for editing the specified shipment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $shipment = Shipment::findOrFail($id);

            // Check permissions
            if (!auth()->user()->can('edit_shipments') && 
                !$shipment->supervisor_user_id === auth()->id()) {
                abort(403, 'ليس لديك صلاحية لتعديل هذه الشحنة');
            }

            // Check if shipment can be edited
            if (!$shipment->canBeEdited()) {
                return back()->with('error', 'لا يمكن تعديل هذه الشحنة في حالتها الحالية');
            }

            $accounts = Account::whereIn('type', ['individual_shipper', 'business_shipper'])
                             ->select('id', 'name_arabic', 'type')
                             ->get()
                             ->groupBy('type');

            $users = User::active()->select('id', 'name', 'type')->get();
            $cities = City::select('id', 'name_arabic', 'country_id')->get();
            $vehicleTypes = VehicleType::select('id', 'name_arabic', 'capacity')->get();
            $goods = Goods::select('id', 'name_arabic', 'category')->get();
            $statuses = Status::active()->select('id', 'name_arabic', 'color')->get();

            return view('shipments.edit', compact(
                'shipment',
                'accounts',
                'users',
                'cities',
                'vehicleTypes',
                'goods',
                'statuses'
            ));

        } catch (Exception $e) {
            Log::error('Error in shipments edit: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل صفحة تعديل الشحنة');
        }
    }

    /**
     * Update the specified shipment in the storage.
     *
     * @param  \App\Http\Requests\ShipmentsFormRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(ShipmentsFormRequest $request, $id)
    {
        try {
            $shipment = Shipment::findOrFail($id);

            // Check permissions
            if (!auth()->user()->can('edit_shipments') && 
                !$shipment->supervisor_user_id === auth()->id()) {
                abort(403, 'ليس لديك صلاحية لتعديل هذه الشحنة');
            }

            // Check if shipment can be edited
            if (!$shipment->canBeEdited()) {
                throw new Exception('لا يمكن تعديل هذه الشحنة في حالتها الحالية');
            }

            DB::beginTransaction();

            // Store old values for audit
            $oldValues = $shipment->toArray();

            // Update the shipment
            $shipmentData = $this->shipmentService->update($id, $request->all());
            
            if (!$shipmentData['success']) {
                throw new Exception($shipmentData['message']);
            }

            // Refresh the model
            $shipment->refresh();

            // Send notifications if status changed
            if (isset($oldValues['status_id']) && $oldValues['status_id'] != $shipment->status_id) {
                $this->sendShipmentNotifications($shipment, 'status_changed');
            }

            // Log the action
            $this->logAction('update', 'shipments', $id, 'تعديل الشحنة: ' . $shipment->serial_number, $oldValues, $shipment->toArray());

            DB::commit();

            if ($request->wantsJson()) {
                return $this->successResponse([
                    'shipment' => $shipment->load(['account', 'status', 'loadingCity', 'unloadingCity']),
                    'redirect_url' => route('shipments.show', $id)
                ], 'تم تحديث الشحنة بنجاح');
            }

            return redirect()->route('shipments.show', $id)
                           ->with('success', 'تم تحديث الشحنة بنجاح');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in shipments update: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء تحديث الشحنة: ' . $e->getMessage(), 500);
            }

            return back()->withInput()
                        ->with('error', 'حدث خطأ أثناء تحديث الشحنة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified shipment from the storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $shipment = Shipment::findOrFail($id);

            // Check permissions
            if (!auth()->user()->can('delete_shipments')) {
                abort(403, 'ليس لديك صلاحية لحذف الشحنات');
            }

            // Check if shipment can be deleted
            if (!$shipment->canBeDeleted()) {
                throw new Exception('لا يمكن حذف هذه الشحنة في حالتها الحالية');
            }

            DB::beginTransaction();

            // Store old values for audit
            $oldValues = $shipment->toArray();

            // Delete the shipment
            $shipment->delete();

            // Log the action
            $this->logAction('delete', 'shipments', $id, 'حذف الشحنة: ' . $shipment->serial_number, $oldValues);

            DB::commit();

            if (request()->wantsJson()) {
                return $this->successResponse(null, 'تم حذف الشحنة بنجاح');
            }

            return redirect()->route('shipments.index')
                           ->with('success', 'تم حذف الشحنة بنجاح');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in shipments destroy: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return $this->errorResponse('حدث خطأ أثناء حذف الشحنة: ' . $e->getMessage(), 500);
            }

            return back()->with('error', 'حدث خطأ أثناء حذف الشحنة: ' . $e->getMessage());
        }
    }

    /**
     * Get shipment statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        try {
            $statistics = Shipment::getStatistics();
            
            return $this->successResponse($statistics, 'تم جلب الإحصائيات بنجاح');

        } catch (Exception $e) {
            Log::error('Error in shipments statistics: ' . $e->getMessage());
            return $this->errorResponse('حدث خطأ أثناء جلب الإحصائيات', 500);
        }
    }

    /**
     * Export shipments to various formats.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'excel');
            $filters = $request->get('filters', []);

            $query = Shipment::with([
                'account:id,name_arabic',
                'status:id,name_arabic',
                'loadingCity:id,name_arabic',
                'unloadingCity:id,name_arabic'
            ]);

            // Apply filters
            if (!empty($filters)) {
                $query->filter($filters);
            }

            $shipments = $query->get();

            // Log the action
            $this->logAction('export', 'shipments', null, 'تصدير الشحنات بصيغة: ' . $format);

            switch ($format) {
                case 'excel':
                    return $this->exportToExcel($shipments);
                case 'pdf':
                    return $this->exportToPdf($shipments);
                case 'csv':
                    return $this->exportToCsv($shipments);
                default:
                    throw new Exception('صيغة التصدير غير مدعومة');
            }

        } catch (Exception $e) {
            Log::error('Error in shipments export: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تصدير الشحنات');
        }
    }

    /**
     * Send shipment notifications.
     *
     * @param  \App\Models\Shipment  $shipment
     * @param  string  $type
     * @return void
     */
    protected function sendShipmentNotifications(Shipment $shipment, string $type)
    {
        try {
            switch ($type) {
                case 'created':
                    // Send to admin users
                    $adminUsers = User::where('type', 'admin')
                                    ->where('status', 'active')
                                    ->get();
                    
                    foreach ($adminUsers as $user) {
                        Mail::to($user->email)->queue(new CreateShipmentMail($shipment, $user));
                    }
                    break;

                case 'status_changed':
                    // Send to shipment supervisor
                    if ($shipment->supervisor) {
                        // Send status change notification
                        // Mail::to($shipment->supervisor->email)->queue(new StatusChangeMail($shipment));
                    }
                    break;
            }
        } catch (Exception $e) {
            Log::error('Error sending shipment notifications: ' . $e->getMessage());
        }
    }

    /**
     * Export shipments to Excel.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $shipments
     * @return \Illuminate\Http\Response
     */
    protected function exportToExcel($shipments)
    {
        // Implementation for Excel export
        // You can use Laravel Excel package here
        return response()->json(['message' => 'Excel export not implemented yet']);
    }

    /**
     * Export shipments to PDF.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $shipments
     * @return \Illuminate\Http\Response
     */
    protected function exportToPdf($shipments)
    {
        // Implementation for PDF export
        // You can use DomPDF or other PDF packages here
        return response()->json(['message' => 'PDF export not implemented yet']);
    }

    /**
     * Export shipments to CSV.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $shipments
     * @return \Illuminate\Http\Response
     */
    protected function exportToCsv($shipments)
    {
        // Implementation for CSV export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="shipments.csv"',
        ];

        $callback = function() use ($shipments) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'الرقم التسلسلي',
                'الحساب',
                'الحالة',
                'مدينة التحميل',
                'مدينة التفريغ',
                'السعر',
                'تاريخ الإنشاء'
            ]);

            // Add data
            foreach ($shipments as $shipment) {
                fputcsv($file, [
                    $shipment->serial_number,
                    $shipment->account->name_arabic ?? '',
                    $shipment->status->name_arabic ?? '',
                    $shipment->loadingCity->name_arabic ?? '',
                    $shipment->unloadingCity->name_arabic ?? '',
                    $shipment->price,
                    $shipment->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
