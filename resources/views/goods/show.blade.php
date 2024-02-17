@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('goods.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('goods.goods.destroy', $goods->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('goods.goods.edit', $goods->id ) }}" class="btn btn-primary" title="{{ trans('goods.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('goods.delete') }}" onclick="return confirm(&quot;{{ trans('goods.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('goods.goods.index') }}" class="btn btn-primary" title="{{ trans('goods.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('goods.goods.create') }}" class="btn btn-secondary" title="{{ trans('goods.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goods->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goods->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.price') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goods->price }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.photo') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ asset('storage/' . $goods->photo) }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.goods_type_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($goods->GoodsType)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.unit_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($goods->Unit)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.account_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($goods->Account)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goods->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('goods.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $goods->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection