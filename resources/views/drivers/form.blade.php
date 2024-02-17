
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($driver)->name_arabic) }}" maxlength="255" placeholder="{{ trans('drivers.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($driver)->name_english) }}" maxlength="255" placeholder="{{ trans('drivers.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="email" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.email') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" type="text" id="email" value="{{ old('email', optional($driver)->email) }}" maxlength="255" placeholder="{{ trans('drivers.email__placeholder') }}">
        {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="password" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.password') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" type="text" id="password" value="{{ old('password', optional($driver)->password) }}" maxlength="255" placeholder="{{ trans('drivers.password__placeholder') }}">
        {!! $errors->first('password', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="phone" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.phone') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" type="text" id="phone" value="{{ old('phone', optional($driver)->phone) }}" maxlength="255" placeholder="{{ trans('drivers.phone__placeholder') }}">
        {!! $errors->first('phone', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="identity_number" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.identity_number') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('identity_number') ? ' is-invalid' : '' }}" name="identity_number" type="text" id="identity_number" value="{{ old('identity_number', optional($driver)->identity_number) }}" min="0" max="255" placeholder="{{ trans('drivers.identity_number__placeholder') }}">
        {!! $errors->first('identity_number', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>



<div class="mb-3 row">
    <label for="date_of_birth_gregorian" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.date_of_birth_gregorian') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('date_of_birth_gregorian') ? ' is-invalid' : '' }} flatpickr-disabled-range flatpickr-input" name="date_of_birth_gregorian" type="text" id="date_of_birth_gregorian" value="{{ old('date_of_birth_gregorian', optional($driver)->date_of_birth_gregorian) }}" placeholder="{{ trans('drivers.date_of_birth_gregorian__placeholder') }}">
        {!! $errors->first('date_of_birth_gregorian', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="account_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.account_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" id="account_id" name="account_id" required="true">
        	    <option value="" style="display: none;" {{ old('account_id', optional($driver)->account_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('drivers.account_id__placeholder') }}</option>
        	@foreach ($Accounts as $key => $Account)
			    <option value="{{ $key }}" {{ old('account_id', optional($driver)->account_id) == $key ? 'selected' : '' }}>
			    	{{ $Account }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="vehicle_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.vehicle_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('vehicle_id') ? ' is-invalid' : '' }}" id="vehicle_id" name="vehicle_id" required="true">
        	    <option value="" style="display: none;" {{ old('vehicle_id', optional($driver)->vehicle_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('drivers.vehicle_id__placeholder') }}</option>
        	@foreach ($Vehicles as $key => $Vehicle)
			    <option value="{{ $key }}" {{ old('vehicle_id', optional($driver)->vehicle_id) == $key ? 'selected' : '' }}>
			    	{{ $Vehicle }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('vehicle_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

