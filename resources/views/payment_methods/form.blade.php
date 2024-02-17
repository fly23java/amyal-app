
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('payment_methods.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($paymentMethod)->name_arabic) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('payment_methods.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('payment_methods.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($paymentMethod)->name_english) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('payment_methods.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

