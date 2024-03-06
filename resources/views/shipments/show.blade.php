@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('shipments.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('shipments.shipment.destroy', $shipment->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('shipments.shipment.edit', $shipment->id ) }}" class="btn btn-primary" title="{{ trans('shipments.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('shipments.delete') }}" onclick="return confirm(&quot;{{ trans('shipments.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('shipments.shipment.index') }}" class="btn btn-primary" title="{{ trans('shipments.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('shipments.shipment.create') }}" class="btn btn-secondary" title="{{ trans('shipments.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.user_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipment->getAUserName($shipment->user_id)->name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.loading_city_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipment->getCityName($shipment->loading_city_id)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.unloading_city_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{  $shipment->getCityName($shipment->unloading_city_id)->name_arabic}}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.vehicle_type_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($shipment->VehicleType)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.goods_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($shipment->Goods)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.status_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($shipment->Status)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.price') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipment->price }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.carrier_price') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipment->carrier_price }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.supervisor_user_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($shipment->User)->name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.carrir') }}</dt>
            <dd class="col-lg-10 col-xl-9">
                @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                    {{ ($shipment->getCarrir($shipment->id)->name_arabic)  }}
                @endif
               

            </dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipment->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $shipment->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection