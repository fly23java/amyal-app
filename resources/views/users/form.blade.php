
<div class="mb-3 row">
    <label for="name" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('users.name') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" type="text" id="name" value="{{ old('name', optional($user)->name) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('users.name__placeholder') }}">
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="email" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('users.email') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" type="text" id="email" value="{{ old('email', optional($user)->email) }}" minlength="1" maxlength="255" required="true" placeholder="{{ trans('users.email__placeholder') }}">
        {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="password" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('users.password') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" type="text" id="password" minlength="1" maxlength="255" required="true" placeholder="{{ trans('users.password__placeholder') }}">
        {!! $errors->first('password', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="birth_date" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('users.birth_date') }}</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('birth_date') ? ' is-invalid' : '' }}" name="birth_date" type="text" id="birth_date" value="{{ old('birth_date', optional($user)->birth_date) }}" placeholder="{{ trans('users.birth_date__placeholder') }}">
        {!! $errors->first('birth_date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="account_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('users.account_id') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('account_id') ? ' is-invalid' : '' }}" id="account_id" name="account_id" required="true">
        	    <option value="" style="display: none;" {{ old('account_id', optional($user)->account_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('users.account_id__placeholder') }}</option>
        	@foreach ($Accounts as $key => $Account)
			    <option value="{{ $key }}" {{ old('account_id', optional($user)->account_id) == $key ? 'selected' : '' }}>
			    	{{ $Account }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="type" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('users.type') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select  form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" id="type" name="type" required="true">
        	    <option  style="display: none;" {{ old('type', optional($user)->type ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('users.type__placeholder') }}</option>
        
			    <option value="admin" {{ old('type', optional($user)->type) == 'admin' ? 'selected' : '' }}>
			    	{{ trans('users.type_admin') }}
			    </option>
			    <option value="staff" {{ old('type', optional($user)->type) == 'staff' ? 'selected' : '' }}>
			    	{{ trans('users.type_staff') }}
			    </option>
			
        </select>
        
        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="status" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('users.status') }}</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select   select2   form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" id="status" name="status" required="true">
        	    <option value="" style="display: none;" {{ old('status', optional($user)->status ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('users.status__placeholder') }}</option>
        
                <option value="active" {{ old('status', optional($user)->status) == 'active' ? 'selected' : '' }}>
			    	{{ trans('users.status_active') }}
			    </option>
                <option value="disabled" {{ old('status', optional($user)->status) == 'disabled' ? 'selected' : '' }}>
			    	{{ trans('users.status_disabled') }}
			    </option>
			
        </select>
        
        {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

