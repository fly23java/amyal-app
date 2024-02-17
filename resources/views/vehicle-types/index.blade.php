@extends('layouts.dashbord-layout')
 
@section('content')
   
<div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ __('vehicle-types.cityControl') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('vehicle-types.Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('vehicle-types.index') }}">{{ __('vehicle-types.cityControl') }}</a>
                                    </li>
                                    
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrumb-right">
                        <div class="dropdown">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
            <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{ __('vehicle-types.cityControl') }}
                                    </h4>
                                    <h4 class="card-title">
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <a  href=" {{ route('vehicle-types.create') }}" class="btn add-new btn-primary mt-50" >
                                            <span>
                                            {{ __('vehicle-types.cityAdd') }}
                                            </span>
                                        </a> 
                                    </div>
                                        
                                    </h4>

                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <p class="card-text"></p>
                                        <div class="table-responsive">
                                            <table  class="table zero-configuration">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('vehicle-types.name_english') }}</th>
                                                        <th>{{ __('vehicle-types.name_arabic') }}</th>
                                                      
                                                        <th>{{ __('vehicle-types.CountryActions') }}</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach( $vehicle_types as $vehicle_type )

                                                    <tr>
                                                        <td>{{ $vehicle_type->name_english }}</td>
                                                        <td>{{ $vehicle_type->name_arabic }}</td>
                                                       
                                                        
                                                        <th width="280px">
                    
                                                                    <form action="{{ route('vehicle-types.destroy',$vehicle_type->id) }}" method="POST">
                                                    
                                                                        <a class="btn" href="{{ route('vehicle-types.edit',$vehicle_type->id) }}">
                                                                            تعديل
                                                                            <span class="action-edit"><i class="feather icon-edit"></i></span>
                                                                        </a>
                                                    
                                                                            @csrf
                                                                            @method('DELETE')
                                                    
                                                                            <button type="submit" class="btn">حذف
                                                                            <span class="action-delete"><i class="feather icon-trash"></i></span>
                                                                            </button>
                                                                    </form>
                                                    
    
    
                                                        </th>
                                                        
                                                       
                                                    </tr>

                                                    @endforeach
                                                    
                                                    
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                        <th>{{ __('vehicle-types.name_english') }}</th>
                                                        <th>{{ __('vehicle-types.name_arabic') }}</th>
                                                       
                                                        <th>{{ __('vehicle-types.CountryActions') }}</th>
                                                        
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

          

            </div>
        </div>
    </div>
    


                
      
@endsection



@section('script')

<script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/tables/table-datatables-basic.js') }}"></script>


<script>
    // var table = new DataTable('');

    $(document).ready( function () {
        $('.zero-configuration').DataTable({
        "drawCallback": function () {
            $('.previous').addClass('btn btn-sm btn-dark');
            $('.paginate_button ').addClass('btn btn-sm btn-primary');

            $('.next').addClass('btn btn-sm btn-dark')
        }
       
     });
    } );

    
</script>
@endsection