@extends('layouts.dashbord-layout')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('main.shipments') }}</h4>
            <div>
                <a href="{{ route('shipments.shipment.create') }}" class="btn btn-secondary" title="{{ trans('shipments.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($shipments) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('shipments.none_available') }}</h4>

            
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

            
                        <!-- Basic Tabs starts -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                
                                <div class="card-body">
                                    <ul class="nav nav-tabs" role="tablist">

                                    <li class="nav-item">
                                        
                                            <a class="nav-link active tab-shipment " id="home-tab{{ $Status->id }}" data-id="{{ $Status->id }}" data-toggle="tab" href="#home{{ $Status->id }}" aria-controls="home{{ $Status->id }}" role="tab" aria-selected="true">
                                            <span class="badge badge-light-success badge-pill ml-auto mr-1">{{ $Status->shipment_count }}</span>
                                                {{ $Status->name_arabic }} 
                                            </a>
                                        </li>
                                    @foreach ( $Status->childStatus  as   $stats )
                                        

                                        <li class="nav-item">
                                        
                                            <a class="nav-link tab-shipment " id="home-tab{{ $stats->id }}" data-id="{{ $stats->id }}" data-toggle="tab" href="#home{{ $stats->id }}" aria-controls="home{{ $stats->id }}" role="tab" aria-selected="true">
                                                    <span class="badge badge-light-success badge-pill ml-auto mr-1">
                                                        @foreach ( $Statuses  as   $stats1 )
                                                            @if ($stats->id == $stats1->id)
                                                                {{ $stats1->shipment_count }}
                                                            @endif
                                                        @endforeach
                                                    </span>
                                            {{ $stats->name_arabic }} 
                                            
                                            </a>
                                        </li>
                                     @endforeach
                                        
                                   
                                      
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane  active" id="home{{ $Status->id }}" aria-labelledby="home-tab{{ $Status->id }}" role="tabpanel">
                                               
                                        </div>
                                        @foreach ( $Status->childStatus as   $stats )
                                        <div class="tab-pane" id="home{{ $stats->id }}" aria-labelledby="home-tab{{ $stats->id }}" role="tabpanel">
                                                
                                        </div>
                                        @endforeach
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Basic Tabs ends -->

                  
                    
                
                            

             

            </div>

        
        </div>
        
        @endif

            <!-- modals shipment edit -->
            @include('shipments.modals-shipment-edit')          
        
        
            @include('shipments.modals-statuses-update')          
           
               






                        
    
    </div>



@endsection


@section('script')  
<script>
    $(document).ready(function () {
        // Handle tab click event
        activeTabs();

        function activeTabs() {
          var id =1;  
        var data={
            'id': 1
        };
            $.ajax({
                type: "GET",
                url: config.routes.retrunShipmentInTabsByStatus,
                data : data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    $('#home'+id).html("");
                    var table = $('.zero-configuration2').DataTable();
                    table.destroy();
                    // Now you can safely reinitialize the DataTable
                  

                        $('#home'+id).append(response.data);
                       
                        $('.zero-configuration2').DataTable({
                            "drawCallback": function () {
                                $('.previous').addClass('btn btn-sm btn-dark');
                                $('.paginate_button ').addClass('btn btn-sm btn-primary');
                    
                                $('.next').addClass('btn btn-sm btn-dark');
                                $('div.dataTables_filter input').addClass('form-control');
                                $('.dataTables_length').addClass('d-none');
                    
                    
                                 
                            } , order: [[3, 'desc']]  });
                }
            });
         }
    });
</script>

@endsection