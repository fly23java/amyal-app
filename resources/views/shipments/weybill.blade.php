@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('shipments.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('shipments.shipment.destroy', $shipment->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('shipments.shipment.edit', $shipment->id ) }}" class="btn btn-primary" title="{{ trans('shipments.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('shipments.delete') }}" onclick="return confirm(&quot;{{ trans('shipments.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('shipments.shipment.index') }}" class="btn btn-primary" title="{{ trans('shipments.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('shipments.shipment.create') }}" class="btn btn-secondary" title="{{ trans('shipments.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
                <a  class="btn btn-secondary" id="get_pdf">
                    <span class="fa-solid fa-print" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>
 <div class="card-body">
    <div id="print" style="font-family: 'Noto Naskh Arabic', sans-serif;">   
        <img style="position: absolute;right: 30%;top: 50%;" src="{{ asset('seal/1.png') }}" />
        <table>
           <tr>
               <th>
                  <img style="width:150px" src="">
               </th>
               <th>
                  <h1>
                  {{ trans('bill.name') }}
                  </h1>
               </th>
           </tr>
         
       </table style="">
        <div class="clearfix" style=" overflow: auto;">
        <table style="float: right; width: 45%;height: 108px;">
            <tr>
                <th>{{ trans('bill.shipment_detDails') }}</th>
                <th></th>
               
            </tr>
            <tr>
                <td>{{ trans('bill.document_number') }}</td>
                <td>{{$shipment->serial_number}}</td>
            </tr>
            <tr>
                <td>{{ trans('bill.receiving_date') }}</td>
                <td>{{ date('Y-m-d', strtotime($shipment->updated_at)) }}</td>
            </tr>
        </table>

        <table style="float: left; width: 45%;height: 108px;">
            <tr>
                <th >

                    {{ trans('bill.additional_details') }}
                </th>
                <th >
                </th>
            
            </tr>
            <tr>
                <td>{{ trans('bill.status') }}</td>
                <td>{{ $shipment->Status->name_arabic }}</td>
            </tr>
            <tr>
                <td>{{ trans('bill.expected_delivery_date') }}</td>
                <td> </td>
            </tr>
                
        </table>
        </div>
        <div style="content:''; clear: both;display: table;"></div>
       <table style="width:100%;height: 108px;">
          <tr>
            <th>
            {{ trans('bill.company_details') }}
            </th>
         </tr>
         <tr>
            <td> {{ trans('bill.company_name') }} {{ $shipment->getAUserName($shipment->user_id)->name }}</td>
         </tr>
         
      </table>

  
     <div class="clearfix" style=" overflow: auto;"> 
       <table style="float: right; width: 45%;height: 108px;">
           <tr>
               <th>
                   
                   {{ trans('bill.loading_city') }}
               </th>
               <th>
               </th>
           </tr>
           <tr>
               <td>{{ trans('bill.city') }} </td>
               <td>{{$shipment->getCityName($shipment->loading_city_id)->name_arabic }}</td> 
           </tr>
          
       </table>

       <table style="float: left; width: 50%;height: 108px;">
         <tr>
             <th>
                 {{ trans('bill.unloading_city') }}

             </th>
             <th>
             </th>
           </tr>
           <tr>
               <td>{{ trans('bill.city') }} </td>
               <td>{{  $shipment->getCityName($shipment->unloading_city_id)->name_arabic  }} </td> 
           </tr>
            
      </table>
    </div>
    <div style="content:''; clear: both;display: table;"></div>

   
    <div class="clearfix" style=" overflow: auto;">
        <table style="float: right; width: 45%;height: 108px;">
            <tr>
                <th>
                    
                    {{ trans('bill.vehicle_details') }}
                </th>
                <th>
                </th>
            </tr>
            <tr>
                <td>{{ trans('bill.plate_number') }} </td>
                <td> {{ $vehicle->plate }} {{ $vehicle->right_letter }}  {{$vehicle->middle_letter }} {{ $vehicle->left_letter}}</td>
            </tr>
            <tr>
                <td></td>
                <td>
                
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </table>

        <table style="float: left; width: 50%;height: 108px;">
            <tr>
                <th>
                    {{ trans('bill.driver_details') }}

                </th>
                <th>
                </th>
            </tr>
            <tr>
                <td>{{ trans('bill.driver_name') }}</td>
                <td> {{ $vehicle->Driver->name_arabic }}  </td>
            </tr>
            <tr>
                <td>{{ trans('bill.identity_number') }} </td>
                <td>{{ $vehicle->Driver->identity_number }}</td>
            </tr>
            <tr>
                <td>{{ trans('bill.driver_phone') }}  </td>
                <td>{{ $vehicle->Driver->phone }}</td>
            </tr>
                
        </table>
    </div>
    <div style="content:''; clear: both;display: table;"></div>

  
    </div>
</div>
  
<div>

@endsection