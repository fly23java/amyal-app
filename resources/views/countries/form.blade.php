
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('countries.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($country)->name_arabic) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('countries.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('countries.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($country)->name_english) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('countries.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="alpha2_code" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('countries.alpha2_code') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('alpha2_code') ? ' is-invalid' : '' }}" name="alpha2_code" type="text" id="alpha2_code" value="{{ old('alpha2_code', optional($country)->alpha2_code) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('countries.alpha2_code__placeholder') }}">
        {!! $errors->first('alpha2_code', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="alpha3_code" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('countries.alpha3_code') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('alpha3_code') ? ' is-invalid' : '' }}" name="alpha3_code" type="text" id="alpha3_code" value="{{ old('alpha3_code', optional($country)->alpha3_code) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('countries.alpha3_code__placeholder') }}">
        {!! $errors->first('alpha3_code', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="phone_code" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('countries.phone_code') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('phone_code') ? ' is-invalid' : '' }}" name="phone_code" type="number" id="phone_code" value="{{ old('phone_code', optional($country)->phone_code) }}" min="-2147483648" max="2147483647" required="true" placeholder="{{ trans('countries.phone_code__placeholder') }}">
        {!! $errors->first('phone_code', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

