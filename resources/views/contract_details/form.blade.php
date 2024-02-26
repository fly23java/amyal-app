
<div class="mb-3 row">
    <label for="contract_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.contract_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('contract_id') ? ' is-invalid' : '' }}" id="contract_id" name="contract_id" required="true">
        	    <option value="" style="display: none;" {{ old('contract_id', optional($contractDetail)->contract_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('contract_details.contract_id__placeholder') }}</option>
        	@foreach ($Contracts as $key => $Contract)
			    <option value="{{ $key }}" {{ old('contract_id', optional($contractDetail)->contract_id) == $key ? 'selected' : '' }}>
			    	{{ $Contract }}
			    </option>
			@endforeach
        </select>
       
        {!! $errors->first('contract_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="vehicle_type_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.vehicle_type_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('vehicle_type_id') ? ' is-invalid' : '' }}" id="vehicle_type_id" name="vehicle_type_id">
        	    <option value="" style="display: none;" {{ old('vehicle_type_id', optional($contractDetail)->vehicle_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('contract_details.vehicle_type_id__placeholder') }}</option>
        	@foreach ($VehicleTypes as $key => $VehicleType)
			    <option value="{{ $key }}" {{ old('vehicle_type_id', optional($contractDetail)->vehicle_type_id) == $key ? 'selected' : '' }}>
			    	{{ $VehicleType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('vehicle_type_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="goods_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.goods_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('goods_id') ? ' is-invalid' : '' }}" id="goods_id" name="goods_id">
        	    <option value="" style="display: none;" {{ old('goods_id', optional($contractDetail)->goods_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('contract_details.goods_id__placeholder') }}</option>
        	@foreach ($Goods as $key => $Good)
			    <option value="{{ $key }}" {{ old('goods_id', optional($contractDetail)->goods_id) == $key ? 'selected' : '' }}>
			    	{{ $Good }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('goods_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="loading_city_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.loading_city_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('loading_city_id') ? ' is-invalid' : '' }}" id="loading_city_id" name="loading_city_id" required="true">
        	    <option value="" style="display: none;" {{ old('loading_city_id', optional($contractDetail)->loading_city_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('contract_details.loading_city_id__placeholder') }}</option>
        	@foreach ($Cities as $key => $City)
			    <option value="{{ $key }}" {{ old('loading_city_id', optional($contractDetail)->loading_city_id) == $key ? 'selected' : '' }}>
			    	{{ $City }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('loading_city_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="dispersal_city_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.dispersal_city_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('dispersal_city_id') ? ' is-invalid' : '' }}" id="dispersal_city_id" name="dispersal_city_id" required="true">
        	    <option value="" style="display: none;" {{ old('dispersal_city_id', optional($contractDetail)->dispersal_city_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('contract_details.dispersal_city_id__placeholder') }}</option>
        	@foreach ($Cities as $key => $City)
			    <option value="{{ $key }}" {{ old('dispersal_city_id', optional($contractDetail)->dispersal_city_id) == $key ? 'selected' : '' }}>
			    	{{ $City }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('dispersal_city_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="price" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.price') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" type="number" id="price" value="{{ old('price', optional($contractDetail)->price) }}" min="-9999999" max="9999999" required="true" placeholder="{{ trans('contract_details.price__placeholder') }}" step="any">
        {!! $errors->first('price', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

