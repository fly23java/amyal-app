
<div class="mb-3 row">
    <label for="name_arabic" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicle_types.name_arabic') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_arabic') ? ' is-invalid' : '' }}" name="name_arabic" type="text" id="name_arabic" value="{{ old('name_arabic', optional($vehicleType)->name_arabic) }}" minlength="1" placeholder="{{ trans('vehicle_types.name_arabic__placeholder') }}">
        {!! $errors->first('name_arabic', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="name_english" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('vehicle_types.name_english') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name_english') ? ' is-invalid' : '' }}" name="name_english" type="text" id="name_english" value="{{ old('name_english', optional($vehicleType)->name_english) }}" minlength="1" placeholder="{{ trans('vehicle_types.name_english__placeholder') }}">
        {!! $errors->first('name_english', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>



<div class="mb-3 row">
    <label for="unloading_city_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('goods.goods_type_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('unloading_city_id') ? ' is-invalid' : '' }}" name="goods_types[]" id="goods_types" multiple required="true">
        	   
            @foreach($goodsTypes as $goodsType)
                    <option value="{{ $goodsType->id }}" {{ (isset($vehicleType) && $vehicleType->goodsTypes->contains($goodsType->id)) ? 'selected' : '' }}>
                        {{ $goodsType->name_arabic }}
                    </option>
            @endforeach
        </select>
        
      
    </div>
</div>

