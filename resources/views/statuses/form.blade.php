
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($status)->name_arabic) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('statuses.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($status)->name_english) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('statuses.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="message_text_in_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.message_text_in_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('message_text_in_arabic') ? ' is-invalid' : '' }}" name="message_text_in_arabic" type="text" id="message_text_in_arabic" value="{{ old('message_text_in_arabic', optional($status)->message_text_in_arabic) }}" min="1" max="255" required="true" placeholder="{{ trans('statuses.message_text_in_arabic__placeholder') }}">
        {!! $errors->first('message_text_in_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="message_text_in_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.message_text_in_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('message_text_in_english') ? ' is-invalid' : '' }}" name="message_text_in_english" type="text" id="message_text_in_english" value="{{ old('message_text_in_english', optional($status)->message_text_in_english) }}" min="1" max="255" required="true" placeholder="{{ trans('statuses.message_text_in_english__placeholder') }}">
        {!! $errors->first('message_text_in_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="confirm_sending_the_message" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.confirm_sending_the_message') }}</label>
    <div class="col-lg-10 col-xl-9">
        <div class="form-check checkbox">
            <input id="confirm_sending_the_message_1" class="form-check-input" name="confirm_sending_the_message" type="checkbox" value="1" {{ old('confirm_sending_the_message', optional($status)->confirm_sending_the_message) == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="confirm_sending_the_message_1">
                Yes
            </label>
        </div>


        {!! $errors->first('confirm_sending_the_message', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="parent_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.parent_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('parent_id') ? ' is-invalid' : '' }}" id="parent_id" name="parent_id">
        	    <option value="" style="display: none;" {{ old('parent_id', optional($status)->parent_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('statuses.parent_id__placeholder') }}</option>
        	@foreach ($ParentStatuses as $key => $ParentStatus)
			    <option value="{{ $key }}" {{ old('parent_id', optional($status)->parent_id) == $key ? 'selected' : '' }}>
			    	{{ $ParentStatus }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('parent_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

