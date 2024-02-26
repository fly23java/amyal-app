@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'Shipment Delivery Detail' }}</h4>
        <div>
            <form method="POST" action="{!! route('shipment_delivery_details.shipment_delivery_detail.destroy', $shipmentDeliveryDetail->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.edit', $shipmentDeliveryDetail->id ) }}" class="btn btn-primary" title="{{ trans('shipment_delivery_details.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('shipment_delivery_details.delete') }}" onclick="return confirm(&quot;{{ trans('shipment_delivery_details.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.index') }}" class="btn btn-primary" title="{{ trans('shipment_delivery_details.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.create') }}" class="btn btn-secondary" title="{{ trans('shipment_delivery_details.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.shipment_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($shipmentDeliveryDetail->Shipment)->serial_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.vehicle_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($shipmentDeliveryDetail->Vehicle)->owner_name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.loading_time') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->loading_time }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.unloading_time') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->unloading_time }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.arrival_time') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->arrival_time }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.departure_time') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->departure_time }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.delivery_status') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->delivery_status }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.delivery_document') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->delivery_document }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipmentDeliveryDetail->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection