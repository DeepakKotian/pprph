<h4>Document : <a target="_blank" href="../uploads/vertrag/{{ $documents->docName }}"> {{ $documents->docName }} </a></h4>
<h4>Document Submitted For:</h4>
<div class="table table-responsive">
    <table class="table table-bordered">  
        <thead> 
            <tr>
            <!-- <th> Policy Number </th> -->
            <th> Policy Name</th>
            <!-- <th> Provider Name </th> -->
           
            </tr> 
        </thead>    
        <tbody>
        @foreach($documents as $key=>$val)   
            @if($val->policy_number!='')     
            <tr>
                <!-- <td>{{ $val->policy_number }} </td>    -->
                <td>{{ $val->insurance_name }} </td>   
                <!-- <td>{{ $val->provider_name }} </td>    -->
            </tr>
            @else
            <tr>
                <td colspan="3">No documents added</td>
            </tr>
            @endif
         @endforeach
        </tbody>
    </table>
</div>   
