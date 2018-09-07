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
        border: 1px solid #ddd;
        font-size:11px;
    }
  </style>
</head>
<body>
  <header align="center"> <h4 align="center" style="color:#FFF;">Prophos</h4>  </header>
   <main>
        <h5>Customer List</h5>
        <table cellspacing="0" width="100%">
            <thead style="background-color:#CCC">
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Postcode</th>
                    <th>Status</th>
                    @foreach($data['ctgs'] as $key=> $rowCtg)
                    <th> {{ $rowCtg->name }}</th>
                    @endforeach
                </tr>  
            </thead>
            <tbody>
               @foreach($data['table'] as $ky => $vl)
               <tr>
                    <td>{{ $vl['id'] }}</td>
                    <td>{{ $vl['first_name'] }}</td>
                    <td>{{$vl['last_name'] }}</td>
                    <td>{{ $vl['email'] }}</td>
                    <td>{{ $vl['city'] }}</td>
                    <td>{{ $vl['zip'] }}</td>
                    <td align="center"> @if($vl['status']>0)
                            <img style="width:8px;" src="uploads/checked.png" alt="">
                        @else
                            <img style="width:8px;" src="uploads/unchecked.png" alt="">
                        @endif</td>
                    @foreach($data['ctgs'] as $key=> $rowCtg)
                    <td align="center"> 
                        @if($vl['ctg'.$key]>0)
                            <img style="width:8px;" src="uploads/greenicon.png" alt="">
                        @else
                            <img style="width:8px;" src="uploads/redicon.png" alt="">
                        @endif
                     </td>
                    @endforeach
                </tr>
               @endforeach
               
            </tbody>            
        </table>
        

  </main>
  <footer>Hello </footer>
</body>
</html>