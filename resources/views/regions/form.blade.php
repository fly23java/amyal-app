
<div class="mb-3 row">
    <label for="country_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('regions.country_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}" id="country_id" name="country_id" required="true">
        	    <option value="" style="display: none;" {{ old('country_id', optional($region)->country_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('regions.country_id__placeholder') }}</option>
        	@foreach ($Countries as $key => $Country)
			    <option value="{{ $key }}" {{ old('country_id', optional($region)->country_id) == $key ? 'selected' : '' }}>
			    	{{ $Country }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('country_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('regions.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($region)->name_arabic) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('regions.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('regions.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($region)->name_english) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('regions.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

