
<div class="mb-3 row">
    <label for="shipment_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.shipment_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('shipment_id') ? ' is-invalid' : '' }}" id="shipment_id" name="shipment_id" required="true">
        	    <option value="" style="display: none;" {{ old('shipment_id', optional($statusChange)->shipment_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('status_changes.shipment_id__placeholder') }}</option>
        	@foreach ($Shipments as $key => $Shipment)
			    <option value="{{ $key }}" {{ old('shipment_id', optional($statusChange)->shipment_id) == $key ? 'selected' : '' }}>
			    	{{ $Shipment }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('shipment_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="status_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.status_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('status_id') ? ' is-invalid' : '' }}" id="status_id" name="status_id" required="true">
        	    <option value="" style="display: none;" {{ old('status_id', optional($statusChange)->status_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('status_changes.status_id__placeholder') }}</option>
        	@foreach ($Statuses as $key => $Status)
			    <option value="{{ $key }}" {{ old('status_id', optional($statusChange)->status_id) == $key ? 'selected' : '' }}>
			    	{{ $Status }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('status_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="user_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.user_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('user_id') ? ' is-invalid' : '' }}" id="user_id" name="user_id" required="true">
        	    <option value="" style="display: none;" {{ old('user_id', optional($statusChange)->user_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('status_changes.user_id__placeholder') }}</option>
        	@foreach ($Users as $key => $User)
			    <option value="{{ $key }}" {{ old('user_id', optional($statusChange)->user_id) == $key ? 'selected' : '' }}>
			    	{{ $User }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

