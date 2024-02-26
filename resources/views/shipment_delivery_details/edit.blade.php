@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">
  
         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ !empty($title) ? $title : 'Shipment Delivery Detail' }}</h4>
            <div>
                <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.index') }}" class="btn btn-primary" title="{{ trans('shipment_delivery_details.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('shipment_delivery_details.shipment_delivery_detail.create') }}" class="btn btn-secondary" title="{{ trans('shipment_delivery_details.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" class="needs-validation" novalidate action="{{ route('shipment_delivery_details.shipment_delivery_detail.update', $shipmentDeliveryDetail->id) }}" id="edit_shipment_delivery_detail_form" name="edit_shipment_delivery_detail_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('shipment_delivery_details.form', [
                                        'shipmentDeliveryDetail' => $shipmentDeliveryDetail,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('shipment_delivery_details.update') }}">
                </div>
            </form>

        </div>
    </div>

@endsection