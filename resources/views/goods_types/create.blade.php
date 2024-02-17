@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">

         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('goods_types.create') }}</h4>
            <div>
                <a href="{{ route('goods_types.goods_type.index') }}" class="btn btn-primary" title="{{ trans('goods_types.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
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

            <form method="POST" class="needs-validation" novalidate action="{{ route('goods_types.goods_type.store') }}" accept-charset="UTF-8" id="create_goods_type_form" name="create_goods_type_form" >
            {{ csrf_field() }}
            @include ('goods_types.form', [
                                        'goodsType' => null,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('goods_types.add') }}">
                </div>

            </form>

        </div>
    </div>

@endsection


