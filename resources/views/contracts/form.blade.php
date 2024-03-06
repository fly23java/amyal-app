
<div class="mb-3 row">
    <label for="sender_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contracts.sender_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('sender_id') ? ' is-invalid' : '' }}" id="sender_id" name="sender_id" required="true">
        	    <option value="" style="display: none;" {{ old('sender_id', optional($contract)->sender_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('contracts.sender_id__placeholder') }}</option>
        	@foreach ($Users as $key => $User)
			    <option value="{{ $key }}" {{ old('sender_id', optional($contract)->sender_id) == $key ? 'selected' : '' }}>
			    	{{ $User }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('sender_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="receiver_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contracts.receiver_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('receiver_id') ? ' is-invalid' : '' }}" id="receiver_id" name="receiver_id" required="required">
        	    <option value="" style="display: none;" {{ old('receiver_id', optional($contract)->receiver_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('contracts.receiver_id__placeholder') }}</option>
        	@foreach ($Users as $key => $User)
			    <option value="{{ $key }}" {{ old('receiver_id', optional($contract)->receiver_id) == $key ? 'selected' : '' }}>
			    	{{ $User }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('receiver_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>


<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contracts.contract_title') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input  class="form-control{{ $errors->has('contract_title') ? ' is-invalid' : '' }}" name="contract_title" type="text" id="contract_title" value="{{ old('contract_title', optional($contract)->contract_title) }}" minlength="1" maxlength="255" required="required" placeholder="{{ trans('contracts.contract_title__placeholder') }}">
        {!! $errors->first('contract_title', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="mb-3 row">
    <label for="description" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('contracts.description') }}</label>
    <div class="col-lg-10 col-xl-9">
        <textarea required="required" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="description" required="true">{{ old('description', optional($contract)->description) }}</textarea>
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

