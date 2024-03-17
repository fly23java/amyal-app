@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'Price' }}</h4>
        <div>
            <form method="POST" action="{!! route('prices.price.destroy', $price->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('prices.price.edit', $price->id ) }}" class="btn btn-primary" title="{{ trans('prices.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('prices.delete') }}" onclick="return confirm(&quot;{{ trans('prices.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('prices.price.index') }}" class="btn btn-primary" title="{{ trans('prices.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('prices.price.create') }}" class="btn btn-secondary" title="{{ trans('prices.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('prices.receiver_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($price->Account)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('prices.price_title') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $price->price_title }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('prices.description') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $price->description }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('prices.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $price->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('prices.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $price->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection