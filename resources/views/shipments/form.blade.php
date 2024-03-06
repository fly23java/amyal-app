
<div class="mb-3 row">
    <label for="account_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.user_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" id="account_id" name="account_id" required="true">
        	    <option value="" style="display: none;" {{ old('user_id', optional($shipment)->user_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('shipments.user_id__placeholder') }}</option>
        	@foreach ($Accounts as $key => $Account)
			    <option value="{{ $key }}" {{ old('account_id', optional($shipment)->account_id) == $key ? 'selected' : '' }}>
			    	{{ $Account }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>


<div class="mb-3 row">
    <label for="loading_city_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.loading_city_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('loading_city_id') ? ' is-invalid' : '' }}" id="loading_city_id" name="loading_city_id" required="true">
        	    <option value="" style="display: none;" {{ old('loading_city_id', optional($shipment)->loading_city_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('shipments.loading_city_id__placeholder') }}</option>
        	@foreach ($Cities as $key => $City)
			    <option value="{{ $key }}" {{ old('loading_city_id', optional($shipment)->loading_city_id) == $key ? 'selected' : '' }}>
			    	{{ $City }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('loading_city_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="unloading_city_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.unloading_city_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('unloading_city_id') ? ' is-invalid' : '' }}" id="unloading_city_id" name="unloading_city_id" required="true">
        	    <option value="" style="display: none;" {{ old('unloading_city_id', optional($shipment)->unloading_city_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('shipments.unloading_city_id__placeholder') }}</option>
        	@foreach ($Cities as $key => $City)
			    <option value="{{ $key }}" {{ old('unloading_city_id', optional($shipment)->unloading_city_id) == $key ? 'selected' : '' }}>
			    	{{ $City }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('unloading_city_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="vehicle_type_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.vehicle_type_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('vehicle_type_id') ? ' is-invalid' : '' }}" id="vehicle_type_id" name="vehicle_type_id" required="true">
        	    <option value="" style="display: none;" {{ old('vehicle_type_id', optional($shipment)->vehicle_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('shipments.vehicle_type_id__placeholder') }}</option>
        	@foreach ($VehicleTypes as $key => $VehicleType)
			    <option value="{{ $key }}" {{ old('vehicle_type_id', optional($shipment)->vehicle_type_id) == $key ? 'selected' : '' }}>
			    	{{ $VehicleType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('vehicle_type_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="goods_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.goods_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select  form-control{{ $errors->has('goods_id') ? ' is-invalid' : '' }}" id="goods_id" name="goods_id" required="true">
        	    <option value="" style="display: none;" {{ old('goods_id', optional($shipment)->goods_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('shipments.goods_id__placeholder') }}</option>
        	
        </select>
        <div class="invalid-feedback goods_error">Please select your country</div>
        
        {!! $errors->first('goods_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="price" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.price') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" type="number" id="price" value="{{ old('price', optional($shipment)->price) }}" min="-999999" max="999999" required="true" placeholder="{{ trans('shipments.price__placeholder') }}" step="any">
        {!! $errors->first('price', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

