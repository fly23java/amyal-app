
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($account)->name_arabic) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('accounts.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($account)->name_english) }}" maxlength="255" placeholder="{{ trans('accounts.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="cr_number" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.cr_number') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('cr_number') ? ' is-invalid' : '' }}" name="cr_number" type="text" id="cr_number" value="{{ old('cr_number', optional($account)->cr_number) }}" min="0" max="255" placeholder="{{ trans('accounts.cr_number__placeholder') }}">
        {!! $errors->first('cr_number', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="bank" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.bank') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('bank') ? ' is-invalid' : '' }}" name="bank" type="text" id="bank" value="{{ old('bank', optional($account)->bank) }}" maxlength="255" placeholder="{{ trans('accounts.bank__placeholder') }}">
        {!! $errors->first('bank', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="iban" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.iban') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('iban') ? ' is-invalid' : '' }}" name="iban" type="text" id="iban" value="{{ old('iban', optional($account)->iban) }}" maxlength="255" placeholder="{{ trans('accounts.iban__placeholder') }}">
        {!! $errors->first('iban', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="account_number" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.account_number') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number" type="text" id="account_number" value="{{ old('account_number', optional($account)->account_number) }}" min="0" max="255" placeholder="{{ trans('accounts.account_number__placeholder') }}">
        {!! $errors->first('account_number', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="tax_number" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.tax_number') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('tax_number') ? ' is-invalid' : '' }}" name="tax_number" type="text" id="tax_number" value="{{ old('tax_number', optional($account)->tax_number) }}" min="0" max="255" placeholder="{{ trans('accounts.tax_number__placeholder') }}">
        {!! $errors->first('tax_number', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="tax_value" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.tax_value') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('tax_value') ? ' is-invalid' : '' }}" name="tax_value" type="text" id="tax_value" value="{{ old('tax_value', optional($account)->tax_value) }}" maxlength="255" placeholder="{{ trans('accounts.tax_value__placeholder') }}">
        {!! $errors->first('tax_value', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="type" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.type') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select  form-control {{ $errors->has('type') ? ' is-invalid' : '' }}" id="type" name="type" required="true">
        	    <option value="" style="display: none;" {{ old('type', optional($account)->type ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('accounts.type__placeholder') }}</option>
        	   
			    <option value="admin" {{ old('type', optional($account)->type) == 'admin' ? 'selected' : '' }}>
			    	{{ trans('accounts.type_admin') }}
			    </option>
			    <option value="individual_shipper" {{ old('type', optional($account)->type) == 'individual_shipper' ? 'selected' : '' }}>
			    	{{ trans('accounts.type_individual_shipper') }}
			    </option>
			    <option value="individual_carrier" {{ old('type', optional($account)->type) == 'individual_carrier' ? 'selected' : '' }}>
			    	{{ trans('accounts.type_individual_carrier') }}
			    </option>
			    <option value="business_shipper" {{ old('type', optional($account)->type) == 'business_shipper' ? 'selected' : '' }}>
			    	{{ trans('accounts.type_business_shipper') }}
			    </option>
			    <option value="business_carrier" {{ old('type', optional($account)->type) == 'business_carrier' ? 'selected' : '' }}>
			    	{{ trans('accounts.type_business_carrier') }}
			    </option>
			
        </select>
        
        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

