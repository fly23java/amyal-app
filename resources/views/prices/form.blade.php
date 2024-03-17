
<div class="mb-3 row">
    <label for="receiver_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('prices.receiver_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select select2 form-control{{ $errors->has('receiver_id') ? ' is-invalid' : '' }}" id="receiver_id" name="receiver_id" required="true">
        	    <option value="" style="display: none;" {{ old('receiver_id', optional($price)->receiver_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('prices.receiver_id__placeholder') }}</option>
        	@foreach ($Accounts as $key => $Account)
			    <option value="{{ $key }}" {{ old('receiver_id', optional($price)->receiver_id) == $key ? 'selected' : '' }}>
			    	{{ $Account }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('receiver_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{  trans('prices.price_title') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('price_title') ? ' is-invalid' : '' }}" name="price_title" type="text" id="price_title" value="{{old('price_title', optional($price)->price_title) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('prices.price_title__placeholder') }}">
        {!! $errors->first('price_title', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="mb-3 row">
    <label for="description" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('prices.description') }}</label>
    <div class="col-lg-10 col-xl-9">
        <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="description" required="true">{{ old('description', optional($price)->description) }}</textarea>
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

