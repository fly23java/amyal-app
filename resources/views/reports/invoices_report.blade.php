@extends('layouts.dashbord-layout')



@section('style')
<style>
        /* يخفي العناصر غير المرغوب في طباعتها */
        @media print {
        /* تنسيق الزر عند الطباعة */
        .btn-print {
            display: none; /* يخفي الزر عند الطباعة */
        }
        
        /* تنسيق مربع الملخص عند الطباعة */
        .summary-box {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        #shipmentForm{
            display: none;
        }
        .no-print{
            display: none;
        }
        .text-end{
            display: none;
        }
    }
    </style>
@endsection
@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<!-- row -->
<div class="row">

    <div class="col-xl-12">
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">التقارير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تقرير
                        التقرير العام</span>
                </div>
            </div>
        </div>
        <div class="card mg-b-20">


           <div class="row">
                <div class="col-12">
                    <div class="card-header pb-0">
                    <form id="shipmentForm" action="{{ route('reports.report.shipmentByStautasResult') }}" method="GET" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-lg-3">
                                <label for="account_id">الحساب</label>
                                <select class="form-select select2 form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" id="account_id" name="account_id" required="true">
                                    @foreach ($Accounts as $key => $Account)
                                    <option value="{{ $key }}">
                                        {{ $Account }}
                                    </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="col-lg-3">
                                <label for="start_date">التاريخ البداء:</label>
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control flatpickr-disabled-range flatpickr-input" data-column="5" id="start_date" placeholder="StartDate to EndDate" data-column-index="4" name="start_date" />
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <label for="end_date">التاريخ النهاية:</label>
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control flatpickr-disabled-range flatpickr-input" data-column="5" id="end_date" placeholder="StartDate to EndDate" data-column-index="4" name="end_date" />
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <label for="status_id">الحالة</label>
                                <select class="form-select select2 form-control{{ $errors->has('status_id') ? ' is-invalid' : '' }}" id="status_id" name="status_id" required="true">
                                    @foreach ($Statuses as $key => $Status)
                                    <option value="{{ $key }}">
                                        {{ $Status }}
                                    </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('status_id', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-1 col-md-2">
                                <button id="searchBtn" class="btn btn-primary btn-block">بحث</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            
         
           

               
                
        </div>
    </div>
</div>
<!-- row closed -->
</div>


   <!-- Basic table -->
@if (isset($shipments) && count($shipments) > 0)
    <div class="summary-box">
        <h1>تقرير الشحنات</h1>
        <div class="summary" style="border: 1px solid #ccc; border-radius: 10px; padding: 10px; margin-bottom: 20px; display: flex; gap: 10px;">
            <p style="margin: 0 10px;">اسم الحساب: {{ $accountName }}</p>
            <p style="margin: 0 10px;">عدد الشحنات: {{ count($shipments) }}</p>
            <p style="margin: 0 10px;">الحالة: {{ $status }}</p>
            <p style="margin: 0 10px;">تاريخ البداية: {{ $startDate }}</p>
            <p style="margin: 0 10px;">تاريخ النهاية: {{ $endDate }}</p>

            
        </div>
        <button class="no-print" onclick="window.print()">طباعة التقرير</button>

       

    </div>

           <table  id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('shipments.serial_number') }}</th>
                        <th>{{ trans('shipments.account') }}</th>
                        <th>{{ trans('shipments.loading_city_id') }}</th>
                        <th>{{ trans('shipments.unloading_city_id') }}</th>
                        <th>{{ trans('shipments.vehicle_type_id') }}</th>
                        <th>{{ trans('shipments.vehicle_id') }}</th>
                        @if($accountType == 'business_shipper' || $accountType == 'individual_shipper')
                        <th>{{ trans('shipments.price') }}</th>
                        @endif
                        @if($accountType == 'individual_carrier' || $accountType == 'business_carrier')
                        <th>{{ trans('shipments.carrier_price') }}</th>
                        <th>{{ trans('shipments.driver') }}</th>
                        <th>{{ trans('shipments.phone') }}</th>
                        @endif
                        <th>{{ trans('shipments.supervisor_user_id') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipments as $shipment)
                    <tr>
                        <td class="align-middle">{{ $shipment->serial_number }}</td>
                        <td class="align-middle">{{ $shipment->getAccountName($shipment->account_id)->name_arabic }}</td>
                        <td class="align-middle">{{ $shipment->getCityName($shipment->loading_city_id)->name_arabic }}</td>
                        <td class="align-middle">{{ $shipment->getCityName($shipment->unloading_city_id)->name_arabic }}</td>
                        <td class="align-middle">{{ optional($shipment->VehicleType)->name_arabic }}</td>
                        

                        <td class="align-middle">
                            @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->right_letter) }}
                                {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->middle_letter) }}
                                {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->left_letter) }}
                                {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->plate) }}
                            @endif
                        </td>

                        @if($accountType == 'business_shipper' || $accountType == 'individual_shipper')
                            <td class="align-middle">{{ $shipment->price }}</td>
                        @endif
                        @if($accountType == 'individual_carrier' || $accountType == 'business_carrier')
                            <td class="align-middle">{{ $shipment->carrier_price }}</td>
                            <td class="align-middle">
                                    @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                        {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->name_arabic }}
                                    @endif
                            </td>
                            <td class="align-middle">
                                    @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                        {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->phone }}
                                    @endif
                            </td>

                            
                        @endif
                        <td class="align-middle">{{ optional($shipment->User)->name_arabic }}</td>
                        <td class="text-end">
                            <a class="btn btn-primary" data-toggle="collapse" href="#shipmentDetails{{ $shipment->id }}" role="button" aria-expanded="false" aria-controls="shipmentDetails{{ $shipment->id }}">
                                <i data-feather='arrow-down'></i>
                                اضاهات الشحنة
                            </a>
                        </td>
                    </tr>
                    <tr class="collapse" id="shipmentDetails{{ $shipment->id }}">
                        <td colspan="8">
                            <div class="shipment-details">
                            <ul>
                                <li><strong>{{ trans('shipments.user_id') }}:</strong> {{ $shipment->getAccountName($shipment->account_id)->name_arabic }}</li>
                                <li><strong>{{ trans('shipments.loading_city_id') }}:</strong> {{ $shipment->getCityName($shipment->loading_city_id)->name_arabic }}</li>
                                <li><strong>{{ trans('shipments.unloading_city_id') }}:</strong> {{ $shipment->getCityName($shipment->unloading_city_id)->name_arabic }}</li>
                                <li><strong>{{ trans('shipments.vehicle_type_id') }}:</strong> {{ optional($shipment->VehicleType)->name_arabic }}</li>
                                <li><strong>{{ trans('shipments.goods_id') }}:</strong> {{ optional($shipment->Goods)->name_arabic }}</li>
                                <li><strong>{{ trans('shipments.status_id') }}:</strong> {{ optional($shipment->Status)->name_arabic }}</li>
                                <li><strong>{{ trans('shipments.price') }}:</strong> {{ $shipment->price }}</li>
                                <li><strong>{{ trans('shipments.carrier_price') }}:</strong> {{ $shipment->carrier_price }}</li>
                                <li><strong>{{ trans('shipments.supervisor_user_id') }}:</strong> {{ optional($shipment->User)->name }}</li>
                                <li><strong>{{ trans('shipments.carrir') }}:</strong>
                                    @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                        {{ ($shipment->getCarrir($shipment->id)->name_arabic) }}
                                    @endif
                                </li>
                                <li><strong>{{ trans('shipments.vehicle_id') }}:</strong>
                                    @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                        {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->right_letter) }}
                                        {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->middle_letter) }}
                                        {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->left_letter) }}
                                        {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->plate) }}
                                    @endif
                                </li>
                                <li><strong>{{ trans('shipments.driver') }}:</strong>
                                    @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                        {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->name_arabic }}
                                    @endif
                                </li>
                                <li><strong>{{ trans('shipments.phone') }}:</strong>
                                    @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                        {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->phone }}
                                    @endif
                                </li>
                                <li><strong>{{ trans('shipments.identity_number') }}:</strong>
                                    @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                        {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->identity_number }}
                                    @endif
                                </li>
                                <li><strong>{{ trans('shipments.created_at') }}:</strong> {{ $shipment->created_at }}</li>
                                <li><strong>{{ trans('shipments.updated_at') }}:</strong> {{ $shipment->updated_at }}</li>
                            </ul>

                            </div>
                        </td>
                    </tr>
                    @endforeach
    </tbody>
</table>

@endif
   <!-- Basic table -->
                
                
                <!--/ Basic table -->
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('script')



@endsection
