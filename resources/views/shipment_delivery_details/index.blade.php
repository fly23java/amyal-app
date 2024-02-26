@extends('layouts.dashbord-layout')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('main.shipment_delivery_details') }}</h4>
            <div>
                <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.create') }}" class="btn btn-secondary" title="{{ trans('shipment_delivery_details.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($shipmentDeliveryDetails) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('shipment_delivery_details.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ trans('shipment_delivery_details.shipment_id') }}</th>
                            <th>{{ trans('shipment_delivery_details.vehicle_id') }}</th>
                            <th>{{ trans('shipment_delivery_details.loading_time') }}</th>
                            <th>{{ trans('shipment_delivery_details.unloading_time') }}</th>
                            <th>{{ trans('shipment_delivery_details.arrival_time') }}</th>
                            <th>{{ trans('shipment_delivery_details.departure_time') }}</th>
                            <th>{{ trans('shipment_delivery_details.delivery_status') }}</th>
                            <th>{{ trans('shipment_delivery_details.delivery_document') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($shipmentDeliveryDetails as $shipmentDeliveryDetail)
                        <tr>
                            <td class="align-middle">{{ optional($shipmentDeliveryDetail->Shipment)->serial_number }}</td>
                            <td class="align-middle">{{ optional($shipmentDeliveryDetail->Vehicle)->owner_name }}</td>
                            <td class="align-middle">{{ $shipmentDeliveryDetail->loading_time }}</td>
                            <td class="align-middle">{{ $shipmentDeliveryDetail->unloading_time }}</td>
                            <td class="align-middle">{{ $shipmentDeliveryDetail->arrival_time }}</td>
                            <td class="align-middle">{{ $shipmentDeliveryDetail->departure_time }}</td>
                            <td class="align-middle">{{ $shipmentDeliveryDetail->delivery_status }}</td>
                            <td class="align-middle">{{ $shipmentDeliveryDetail->delivery_document }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('shipment_delivery_details.shipment_delivery_detail.destroy', $shipmentDeliveryDetail->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.show', $shipmentDeliveryDetail->id ) }}" class="btn btn-info" title="{{ trans('shipment_delivery_details.show') }}">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.edit', $shipmentDeliveryDetail->id ) }}" class="btn btn-primary" title="{{ trans('shipment_delivery_details.edit') }}">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('shipment_delivery_details.delete') }}" onclick="return confirm(&quot;{{ trans('shipment_delivery_details.confirm_delete') }}&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

         
        </div>
        
        @endif
    
    </div>
@endsection