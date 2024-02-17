@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('payment_methods.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('payment_methods.payment_method.destroy', $paymentMethod->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('payment_methods.payment_method.edit', $paymentMethod->id ) }}" class="btn btn-primary" title="{{ trans('payment_methods.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('payment_methods.delete') }}" onclick="return confirm(&quot;{{ trans('payment_methods.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('payment_methods.payment_method.index') }}" class="btn btn-primary" title="{{ trans('payment_methods.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('payment_methods.payment_method.create') }}" class="btn btn-secondary" title="{{ trans('payment_methods.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('payment_methods.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $paymentMethod->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('payment_methods.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $paymentMethod->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('payment_methods.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $paymentMethod->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('payment_methods.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $paymentMethod->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection