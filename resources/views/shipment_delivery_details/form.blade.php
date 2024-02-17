
<div class="mb-3 row">
    <label for="shipment_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.shipment_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('shipment_id') ? ' is-invalid' : '' }}" id="shipment_id" name="shipment_id" required="true">
        	    <option value="" style="display: none;" {{ old('shipment_id', optional($shipmentDeliveryDetail)->shipment_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('shipment_delivery_details.shipment_id__placeholder') }}</option>
        	@foreach ($Shipments as $key => $Shipment)
			    <option value="{{ $key }}" {{ old('shipment_id', optional($shipmentDeliveryDetail)->shipment_id) == $key ? 'selected' : '' }}>
			    	{{ $Shipment }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('shipment_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="vehicle_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.vehicle_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('vehicle_id') ? ' is-invalid' : '' }}" id="vehicle_id" name="vehicle_id" required="true">
        	    <option value="" style="display: none;" {{ old('vehicle_id', optional($shipmentDeliveryDetail)->vehicle_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('shipment_delivery_details.vehicle_id__placeholder') }}</option>
        	@foreach ($Vehicles as $key => $Vehicle)
			    <option value="{{ $key }}" {{ old('vehicle_id', optional($shipmentDeliveryDetail)->vehicle_id) == $key ? 'selected' : '' }}>
			    	{{ $Vehicle }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('vehicle_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="loading_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.loading_time') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('loading_time') ? ' is-invalid' : '' }}" name="loading_time" type="text" id="loading_time" value="{{ old('loading_time', optional($shipmentDeliveryDetail)->loading_time) }}" placeholder="{{ trans('shipment_delivery_details.loading_time__placeholder') }}">
        {!! $errors->first('loading_time', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="unloading_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.unloading_time') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('unloading_time') ? ' is-invalid' : '' }}" name="unloading_time" type="text" id="unloading_time" value="{{ old('unloading_time', optional($shipmentDeliveryDetail)->unloading_time) }}" placeholder="{{ trans('shipment_delivery_details.unloading_time__placeholder') }}">
        {!! $errors->first('unloading_time', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="arrival_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.arrival_time') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('arrival_time') ? ' is-invalid' : '' }}" name="arrival_time" type="text" id="arrival_time" value="{{ old('arrival_time', optional($shipmentDeliveryDetail)->arrival_time) }}" placeholder="{{ trans('shipment_delivery_details.arrival_time__placeholder') }}">
        {!! $errors->first('arrival_time', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="departure_time" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.departure_time') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('departure_time') ? ' is-invalid' : '' }}" name="departure_time" type="text" id="departure_time" value="{{ old('departure_time', optional($shipmentDeliveryDetail)->departure_time) }}" placeholder="{{ trans('shipment_delivery_details.departure_time__placeholder') }}">
        {!! $errors->first('departure_time', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="delivery_status" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.delivery_status') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('delivery_status') ? ' is-invalid' : '' }}" name="delivery_status" type="text" id="delivery_status" value="{{ old('delivery_status', optional($shipmentDeliveryDetail)->delivery_status) }}" maxlength="255" placeholder="{{ trans('shipment_delivery_details.delivery_status__placeholder') }}">
        {!! $errors->first('delivery_status', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="delivery_document" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipment_delivery_details.delivery_document') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('delivery_document') ? ' is-invalid' : '' }}" name="delivery_document" type="text" id="delivery_document" value="{{ old('delivery_document', optional($shipmentDeliveryDetail)->delivery_document) }}" maxlength="255" placeholder="{{ trans('shipment_delivery_details.delivery_document__placeholder') }}">
        {!! $errors->first('delivery_document', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

