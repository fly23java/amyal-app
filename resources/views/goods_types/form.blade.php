
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($goodsType)->name_arabic) }}" maxlength="255" placeholder="{{ trans('goods_types.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($goodsType)->name_english) }}" maxlength="255" placeholder="{{ trans('goods_types.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="parent_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.parent_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('parent_id') ? ' is-invalid' : '' }}" id="parent_id" name="parent_id">
        	    <option value="" style="display: none;" {{ old('parent_id', optional($goodsType)->parent_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('goods_types.parent_id__placeholder') }}</option>
        	@foreach ($ParentGoodsTypes as $key => $ParentGoodsType)
			    <option value="{{ $key }}" {{ old('parent_id', optional($goodsType)->parent_id) == $key ? 'selected' : '' }}>
			    	{{ $ParentGoodsType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('parent_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

