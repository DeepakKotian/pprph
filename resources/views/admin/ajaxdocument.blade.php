<h4>Document : <a target="_blank" href="../uploads/vertrag/{{ $documents->docName }}"> {{ $documents->docName }} </a></h4>
<h4>Document Submitted For:</h4>
<div class="table table-responsive">
    <table class="table table-bordered">  
        <thead> 
            <tr>
            <th> Policy Name</th>
            <th> Provider Name </th>
            </tr> 
        </thead>    
        <tbody>
        @foreach($documents as $key=>$val)   
            <tr>
                <td>{{ $val->insurance_name }} </td>   
                <td>{{ $val->provider_name }} </td>   
            </tr>
         @endforeach
        </tbody>
    </table>
</div>      
         