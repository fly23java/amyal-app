@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('goods_types.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('goods_types.goods_type.destroy', $goodsType->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('goods_types.goods_type.edit', $goodsType->id ) }}" class="btn btn-primary" title="{{ trans('goods_types.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('goods_types.delete') }}" onclick="return confirm(&quot;{{ trans('goods_types.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('goods_types.goods_type.index') }}" class="btn btn-primary" title="{{ trans('goods_types.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('goods_types.goods_type.create') }}" class="btn btn-secondary" title="{{ trans('goods_types.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goodsType->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goodsType->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.parent_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($goodsType->ParentGoodsType)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goodsType->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods_types.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goodsType->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection