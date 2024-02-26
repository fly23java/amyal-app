<div class="modal modal-slide-in new-user-modal fade" id="models-shipment-delivery">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0"  method="POST" action="{{ route('shipments.shipment.getAddVehcileToShipment') }}" id="create_shipment_details_form">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                             {{ trans('shipments.edit') }}
                                        </h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                    <input  name="shipment_delivery_detail_id" type="hidden" id="shipment_delivery_detail_id" >
                                    <input  name="supervisor_user_id" type="hidden"  value=" {{ Auth::user()->id }} " >
                                    <input  name="shipment_id" type="hidden" id="shipment_id" >
                                    {{ csrf_field() }}

                                    <div class="mb-3 row">
        <label for="loading_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.loading_time') }}</label>
        <div class="col-lg-10 col-xl-9">
            <input class="form-control{{ $errors->has('loading_time') ? ' is-invalid' : '' }}" name="loading_time" type="text" id="loading_time" value="{{ old('loading_time', optional($shipment->shipmentDeliveryDetail)->loading_time) }}" placeholder="{{ trans('shipment_delivery_details.loading_time__placeholder') }}">
            {!! $errors->first('loading_time', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>

    <div class="mb-3 row">
        <label for="unloading_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.unloading_time') }}</label>
        <div class="col-lg-10 col-xl-9">
            <input class="form-control{{ $errors->has('unloading_time') ? ' is-invalid' : '' }}" name="unloading_time" type="text" id="unloading_time" value="{{ old('unloading_time', optional($shipment->shipmentDeliveryDetail)->unloading_time) }}" placeholder="{{ trans('shipment_delivery_details.unloading_time__placeholder') }}">
           
        </div>
    </div>

    <div class="mb-3 row">
        <label for="arrival_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.arrival_time') }}</label>
        <div class="col-lg-10 col-xl-9">
            <input class="form-control{{ $errors->has('arrival_time') ? ' is-invalid' : '' }}" name="arrival_time" type="text" id="arrival_time" value="{{ old('arrival_time', optional($shipment->shipmentDeliveryDetail)->arrival_time) }}" placeholder="{{ trans('shipment_delivery_details.arrival_time__placeholder') }}">
           
        </div>
    </div>

    <div class="mb-3 row">
        <label for="departure_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.departure_time') }}</label>
        <div class="col-lg-10 col-xl-9">
            <input class="form-control{{ $errors->has('departure_time') ? ' is-invalid' : '' }}" name="departure_time" type="text" id="departure_time" value="{{ old('departure_time', optional($shipment->shipmentDeliveryDetail)->departure_time) }}" placeholder="{{ trans('shipment_delivery_details.departure_time__placeholder') }}">
           
        </div>
    </div>

                                   
                                    <div class="modal-body flex-grow-1" >
                                        <button type="submit" class="btn btn-primary mr-1 data-submit">{{ trans('shipments.update') }}</button>
                                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('main.reset') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>