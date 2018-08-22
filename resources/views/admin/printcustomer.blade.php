<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    /* p { page-break-after: always; }
    p:last-child { page-break-after: never; } */
  </style>
</head>
<body>
  <header>Hello {{ $data->first_name }}</header>
  <footer>Hello {{ $data->last_name }}</footer>
  <main>
        <table>
            <thead>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>  
            </thead>
            <tbody>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
                <tr>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->first_name}}</td>
                </tr>
            </tbody>            
        </table>
        

  </main>
</body>
</html>