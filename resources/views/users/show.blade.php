@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($user->name) ? $user->name : 'User' }}</h4>
        <div>
            <form method="POST" action="{!! route('users.user.destroy', $user->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('users.user.edit', $user->id ) }}" class="btn btn-primary" title="{{ trans('users.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('users.delete') }}" onclick="return confirm(&quot;{{ trans('users.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('users.user.index') }}" class="btn btn-primary" title="{{ trans('users.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('users.user.create') }}" class="btn btn-secondary" title="{{ trans('users.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.name') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $user->name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.email') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $user->email }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.birth_date') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $user->birth_date }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.account_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($user->Account)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.type') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $user->type }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.status') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $user->status }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $user->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('users.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $user->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection