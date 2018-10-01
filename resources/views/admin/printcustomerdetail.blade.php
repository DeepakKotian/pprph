<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    body{font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;}
    header { position: fixed; top: -50px;  background-color: #3c8dbc; height: 50px; }
    footer { position: fixed; bottom: -50px; background-color: #3c8dbc; height: 50px; }
    /* p { page-break-after: always; }
    p:last-child { page-break-after: never; } */
    table, th, td {
        /* border: 1px solid #ddd; */
        font-size:11px;
    }
    table.fmlyTable, .fmlyTable th, .fmlyTable td{
        border: 1px solid #ddd;
    }
  </style>
</head>
<body>
  <header align="center"> <h4 align="center" style="color:#FFF;">Prophos - Customer Detail</h4>  </header>
   <main>
        <h3> Customer Id :  {{ $data['id'] }} </h3>
        <table cellspacing="0" width="100%" border="0">
            <tbody>
                <tr>
                    <th>Name</th>
                    <td>:</td> <td> {{ $data['first_name'] }} {{ $data['last_name'] }}</td>
                </tr>
                <tr> <th> Birth Details </th>
                <td>:</td> <td> <b> DOB: </b> {{ date('d-m-Y',strtotime($data['dob'])) }}  <b> Sex </b> : @if($data['gender']=='M') Male @else 'Female' @endif  </td></tr>
                <tr>
                    <th>Company</th>
                    <td>:</td> <td> {{ $data['company'] }}</td>
                </tr>  
                <tr>
                    <th>Address</th>
                    <td>:</td> <td> {{ $data['address'] }} {{ $data['city'] }} {{ $data['nationality'] }}, {{ $data['zip'] }}</td>
                </tr> 
                <tr>
                    <th>Contact Details</th>
                    <td>:</td> <td> Phone - {{ $data['telephone'] }} {{ $data['mobile'] }} , Email - {{ $data['email'] }} {{ $data['email_office'] }}</td>
                </tr> 
            </tbody>            
        </table>

        <h4>Family Details</h4>
        <table width="50%" cellspacing="0" class="fmlyTable">
            <tr>
                <th>Name</th>
                <th>DOB</th>
                <th>Mobile</th>
            </tr>
            @foreach($data->family as $key=>$val)
                <tr>
                    <td>{{ $val['first_name'] }} {{ $val['last_name'] }}</td>
                    <td>{{ $val['dob'] }}</td>
                    <td>{{ $val['mobile'] }}</td>
                </tr>
            @endforeach
        </table>

        <h4>Policy Details</h4>
        <table width="50%" cellspacing="0" class="fmlyTable">
            <tr>
                <th>Policy </th>
                <th>Provider</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            @foreach($data->policy as $ky=>$vl)
                <tr>
                    <td>{{ $vl->insuranceName }}</td>
                    <td>{{ $vl->providerName }}</td>
                    <td>{{ date('d-m-Y',strtotime($vl->start_date)) }}</td>
                    <td>{{ date('d-m-Y',strtotime($vl->end_date)) }}</td>
                </tr>
            @endforeach
        </table>
  </main>
  <footer style="color:#FFF;"> <a align="center"> &copy; Prophos </a>  </footer>
</body>
</html>