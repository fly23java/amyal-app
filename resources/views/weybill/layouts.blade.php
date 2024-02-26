<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,-scale=1.0">
    <style>
    
    body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    direction: rtl;
    font-size:26px;
    /* background-color: #eee; */
    
}

.container {
    width: 80%;
    margin: auto;
}

h1 {
    
    margin-bottom: 2rem;
}

h2 {
    text-align: center;
    margin-bottom
}
table{
    width: 90%;
    margin:10px auto
}
th{
            background-color: #f2f2f2;
        width: 100%;
        }
    .bg  {
        background-color: black;
        color:#fff;
        border-radius: 11px;
       
    }
    .redios{
        
        border-radius: 11px;
    }

    @media print {
    background: #eee !important; /* <= DISABLES backgrounds */
    }
    </style>
    
   
</head>
<body>
    <div style="width: 97%%;margin: 10px auto;">
    <table>
            <tr >
                <td > @include('weybill.header')</td>
                <td style="font-size:45px;">

                {{ trans('bill.name') }}
                </td>
            
            </tr>

    </table>
        
        <table >
            <tr>
                <td> {{ trans('bill.document_number') }} :</td>
                <td>{{$shipment->serial_number}}</td>
            </tr>
            <tr>
                <td>  {{ trans('bill.receiving_date') }} :</td>
                <td>{{ date('Y-m-d', strtotime($shipment->updated_at)) }}</td>
            </tr>
            <tr>
                
            </tr>
            <tr>
            <th class="bg">
                  {{ trans('bill.company_details') }}
              </th>
            
                <tr>
                    <td> {{ trans('bill.company_name') }} {{ $shipment->getAUserName($shipment->user_id)->name }}</td>
                </tr>
           
            <tr>
                <td colspan="2">
                    
                </td>
            </tr>

            <tr class="bg redios">
               <td >
               {{ trans('bill.loading_city') }}
               
               </td>

               <td>
             {{ trans('bill.unloading_city') }}
             </td>
           </tr>
           <tr>
               <td>{{$shipment->getCityName($shipment->loading_city_id)->name_arabic }}</td> 
               <td>{{  $shipment->getCityName($shipment->unloading_city_id)->name_arabic  }} </td> 
              
           </tr>
            <tr class="bg redios">
                <td>
                <strong>{{ trans('bill.vehicle_details') }}</strong> 
                </td>
                <td>
                </td>
                
            </tr>   
            <tr>
                <td>
                    {{ trans('bill.plate_number') }}    
                </td>
                <td> {{ $vehicle->plate }} {{ $vehicle->right_letter }}  {{$vehicle->middle_letter }} {{ $vehicle->left_letter}}</td>
            </tr>
           
            <tr class="bg redios">
            <td>
                    <strong>{{ trans('bill.driver_details') }}</strong> 
            </td>
            <td>
            </td>
            </tr>
            <tr>
                <td>{{ trans('bill.driver_name') }}</td>
                <td> {{ $vehicle->Driver->name_arabic }}  </td>
            </tr>
            <tr>
                <td>
                    {{ trans('bill.identity_number') }}    
                </td>
                <td>{{ $vehicle->Driver->identity_number }}</td>
            </tr>
            <tr>
                <td>{{ trans('bill.driver_phone') }}  </td>
                <td>{{ $vehicle->Driver->phone }}</td>
            </tr>
        </table>

        <div  style="position: absolute;top:{{ $top}}%;right: {{ $right }}%;">
            @include('weybill.seal')
        </div>
      
    </div>
</body>
</html>