
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($goods)->name_arabic) }}" maxlength="255" placeholder="{{ trans('goods.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($goods)->name_english) }}" maxlength="255" placeholder="{{ trans('goods.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="price" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.price') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" type="number" id="price" value="{{ old('price', optional($goods)->price) }}" min="-9999999" max="9999999" required="true" placeholder="{{ trans('goods.price__placeholder') }}" step="any">
        {!! $errors->first('price', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="photo" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.photo') }}</label>
    <div class="col-lg-10 col-xl-9">
        <div class="mb-3">
            <input class="form-control{{ $errors->has('photo') ? ' is-invalid' : '' }}" type="file" name="photo" id="photo" class="">
        </div>

        @if (isset($goods->photo) && !empty($goods->photo))

        <div class="input-group mb-3">
          <div class="form-check">
            <input type="checkbox" name="custom_delete_photo" id="custom_delete_photo" class="form-check-input custom-delete-file" value="1" {{ old('custom_delete_photo', '0') == '1' ? 'checked' : '' }}> 
          </div>
          <label class="form-check-label" for="custom_delete_photo"> Delete {{ $goods->photo }}</label>
        </div>

        @endif

        {!! $errors->first('photo', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row ">
    <label for="goods_type_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.goods_type_id') }}</label>
    <div class="col-lg-10 col-xl-9 ">
        <select class="form-select   select2   form-control  {{ $errors->has('goods_type_id') ? ' is-invalid' : '' }}" id="goods_type_id" name="goods_type_id" required="true">
        	    <option value="" style="display: none;" {{ old('goods_type_id', optional($goods)->goods_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('goods.goods_type_id__placeholder') }}</option>
        	@foreach ($GoodsTypes as $key => $GoodsType)
			    <option value="{{ $key }}" {{ old('goods_type_id', optional($goods)->goods_type_id) == $key ? 'selected' : '' }}>
			    	{{ $GoodsType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('goods_type_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="unit_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.unit_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('unit_id') ? ' is-invalid' : '' }}" id="unit_id" name="unit_id" required="true">
        	    <option value="" style="display: none;" {{ old('unit_id', optional($goods)->unit_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('goods.unit_id__placeholder') }}</option>
        	@foreach ($Units as $key => $Unit)
			    <option value="{{ $key }}" {{ old('unit_id', optional($goods)->unit_id) == $key ? 'selected' : '' }}>
			    	{{ $Unit }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('unit_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="account_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.account_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" id="account_id" name="account_id" required="true">
        	    <option value="" style="display: none;" {{ old('account_id', optional($goods)->account_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('goods.account_id__placeholder') }}</option>
        	@foreach ($Accounts as $key => $Account)
			    <option value="{{ $key }}" {{ old('account_id', optional($goods)->account_id) == $key ? 'selected' : '' }}>
			    	{{ $Account }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

