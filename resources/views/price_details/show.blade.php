@extends('layouts.app')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'Price Detail' }}</h4>
        <div>
            <form method="POST" action="{!! route('price_details.price_detail.destroy', $priceDetail->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('price_details.price_detail.edit', $priceDetail->id ) }}" class="btn btn-primary" title="{{ trans('price_details.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('price_details.delete') }}" onclick="return confirm(&quot;{{ trans('price_details.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('price_details.price_detail.index') }}" class="btn btn-primary" title="{{ trans('price_details.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('price_details.price_detail.create') }}" class="btn btn-secondary" title="{{ trans('price_details.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.price_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($priceDetail->Price)->price_title }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.vehicle_type_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($priceDetail->VehicleType)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.goods_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($priceDetail->Good)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.loading_city_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($priceDetail->City)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.dispersal_city_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($priceDetail->City)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.price') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $priceDetail->price }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.accepted_user_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($priceDetail->User)->name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $priceDetail->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('price_details.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $priceDetail->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection