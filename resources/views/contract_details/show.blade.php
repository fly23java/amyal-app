@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('contract_details.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('contract_details.contract_detail.destroy', $contractDetail->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('contract_details.contract_detail.edit', $contractDetail->id ) }}" class="btn btn-primary" title="{{ trans('contract_details.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('contract_details.delete') }}" onclick="return confirm(&quot;{{ trans('contract_details.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('contract_details.contract_detail.index') }}" class="btn btn-primary" title="{{ trans('contract_details.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('contract_details.contract_detail.create') }}" class="btn btn-secondary" title="{{ trans('contract_details.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.contract_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($contractDetail->Contract)->description }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.vehicle_type_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($contractDetail->VehicleType)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.goods_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($contractDetail->Good)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.loading_city_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($contractDetail->City)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.dispersal_city_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($contractDetail->City)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.price') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $contractDetail->price }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $contractDetail->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contract_details.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $contractDetail->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection