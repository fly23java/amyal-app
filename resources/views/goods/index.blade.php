@extends('layouts.dashbord-layout')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(Session::has('success_message'))
        <script>
            // toastr.success("{{ Session::get('success_message') }}");
            // $(document).ready( function () {
            //     toastr['success']('{{ Session::get('success_message') }}', 'Success!', {
            //         closeButton: true,
            //         tapToDismiss: false,
            //         rtl: isRtl
            //         });
            // });
        </script>
    @endif


    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('main.goods') }}</h4>
            <div>
                <a href="{{ route('goods.goods.create') }}" class="btn btn-secondary" title="{{ trans('goods.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($goodsObjects) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('goods.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('goods.name_arabic') }}</th>
                            <th>{{ trans('goods.name_english') }}</th>
                            <th>{{ trans('goods.price') }}</th>
                            <th>{{ trans('goods.goods_type_id') }}</th>
                            <th>{{ trans('goods.unit_id') }}</th>
                            <th>{{ trans('goods.account_id') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($goodsObjects as $goods)
                        <tr>
                            <td class="align-middle">{{ $goods->name_arabic }}</td>
                            <td class="align-middle">{{ $goods->name_english }}</td>
                            <td class="align-middle">{{ $goods->price }}</td>
                            <td class="align-middle">{{ optional($goods->GoodsType)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($goods->Unit)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($goods->Account)->name_arabic }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('goods.goods.destroy', $goods->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('goods.goods.show', $goods->id ) }}" class="btn btn-info" title="{{ trans('goods.show') }}">
                                            <i data-feather='eye'></i>
                                        </a>
                                        <a href="{{ route('goods.goods.edit', $goods->id ) }}" class="btn btn-primary" title="{{ trans('goods.edit') }}">
                                            <i data-feather='edit'></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('goods.delete') }}" onclick="return confirm(&quot;{{ trans('goods.confirm_delete') }}&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

            
        </div>
        
        @endif
    
    </div>
@endsection