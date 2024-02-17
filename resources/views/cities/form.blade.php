
<div class="mb-3 row">
    <label for="region_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('cities.region_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('region_id') ? ' is-invalid' : '' }}" id="region_id" name="region_id" required="true">
        	    <option value="" style="display: none;" {{ old('region_id', optional($city)->region_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('cities.region_id__placeholder') }}</option>
        	@foreach ($Regions as $key => $Region)
			    <option value="{{ $key }}" {{ old('region_id', optional($city)->region_id) == $key ? 'selected' : '' }}>
			    	{{ $Region }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('region_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('cities.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($city)->name_arabic) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('cities.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('cities.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($city)->name_english) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('cities.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

