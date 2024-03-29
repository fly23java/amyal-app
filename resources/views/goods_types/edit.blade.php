@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">
  
         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('goods_types.edit') }}</h4>
            <div>
                <a href="{{ route('goods_types.goods_type.index') }}" class="btn btn-primary" title="{{ trans('goods_types.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('goods_types.goods_type.create') }}" class="btn btn-secondary" title="{{ trans('goods_types.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" class="needs-validation" novalidate action="{{ route('goods_types.goods_type.update', $goodsType->id) }}" id="edit_goods_type_form" name="edit_goods_type_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('goods_types.form', [
                                        'goodsType' => $goodsType,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('goods_types.update') }}">
                </div>
            </form>

        </div>
    </div>

@endsection