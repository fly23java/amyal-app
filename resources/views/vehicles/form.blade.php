
<div class="mb-3 row">
    <label for="owner_name" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.owner_name') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('owner_name') ? ' is-invalid' : '' }}" name="owner_name" type="text" id="owner_name" value="{{ old('owner_name', optional($vehicle)->owner_name) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('vehicles.owner_name__placeholder') }}">
        {!! $errors->first('owner_name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="sequence_number" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.sequence_number') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('sequence_number') ? ' is-invalid' : '' }}" name="sequence_number" type="number" id="sequence_number" value="{{ old('sequence_number', optional($vehicle)->sequence_number) }}" required="true" placeholder="{{ trans('vehicles.sequence_number__placeholder') }}">
        {!! $errors->first('sequence_number', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="plate" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.plate') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('plate') ? ' is-invalid' : '' }}" name="plate" type="text" id="plate" value="{{ old('plate', optional($vehicle)->plate) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('vehicles.plate__placeholder') }}">
        {!! $errors->first('plate', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="right_letter" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.right_letter') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('right_letter') ? ' is-invalid' : '' }}" name="right_letter" type="text" id="right_letter" value="{{ old('right_letter', optional($vehicle)->right_letter) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('vehicles.right_letter__placeholder') }}">
        {!! $errors->first('right_letter', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="middle_letter" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.middle_letter') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('middle_letter') ? ' is-invalid' : '' }}" name="middle_letter" type="text" id="middle_letter" value="{{ old('middle_letter', optional($vehicle)->middle_letter) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('vehicles.middle_letter__placeholder') }}">
        {!! $errors->first('middle_letter', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="left_letter" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.left_letter') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('left_letter') ? ' is-invalid' : '' }}" name="left_letter" type="text" id="left_letter" value="{{ old('left_letter', optional($vehicle)->left_letter) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('vehicles.left_letter__placeholder') }}">
        {!! $errors->first('left_letter', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row" style="display:none">
    <label for="plate_type" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.plate_type') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('plate_type') ? ' is-invalid' : '' }}" name="plate_type" type="number" id="plate_type" value="2" min="-2147483648" max="2147483647" required="true" placeholder="{{ trans('vehicles.plate_type__placeholder') }}">
        {!! $errors->first('plate_type', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="vehicle_type_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.vehicle_type_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('vehicle_type_id') ? ' is-invalid' : '' }}" id="vehicle_type_id" name="vehicle_type_id" required="true">
        	    <option value="" style="display: none;" {{ old('vehicle_type_id', optional($vehicle)->vehicle_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('vehicles.vehicle_type_id__placeholder') }}</option>
        	@foreach ($VehicleTypes as $key => $VehicleType)
			    <option value="{{ $key }}" {{ old('vehicle_type_id', optional($vehicle)->vehicle_type_id) == $key ? 'selected' : '' }}>
			    	{{ $VehicleType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('vehicle_type_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="account_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.account_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" id="account_id" name="account_id" required="true">
        	    <option value="" style="display: none;" {{ old('account_id', optional($vehicle)->account_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('vehicles.account_id__placeholder') }}</option>
        	@foreach ($Accounts as $key => $Account)
			    <option value="{{ $key }}" {{ old('account_id', optional($vehicle)->account_id) == $key ? 'selected' : '' }}>
			    	{{ $Account }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

