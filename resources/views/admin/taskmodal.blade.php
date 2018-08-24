<div class="modal fade" id="addTask" tabindex="-1" role="dialog" aria-labelledby="addTaskLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title"  id="exampleModalLabel"> <span style="text-transform:capitalize"><% modalAction %></span> Task</h4>
           
         </div>
         <div class="modal-body">
         <div class="box-body">
            <div class="form-group" :class="{ 'has-error': $v.tasks.task_name.$error }">
              <label for="exampleInputEmail1">Task Name *</label>
              <input type="text" class="form-control" v-model="$v.tasks.task_name.$model" id="exampleInputEmail1" placeholder="Enter Task Name">
            </div>
            <div class="form-group" >
            
              <label for="exampleInputEmail1">Task Detail </label>
             <textarea name="" id="" v-model="tasks.task_detail" class="form-control" placeholder="Enter Task detail" cols="30" rows="3"></textarea>
            </div>
            <div class="form-group" :class="{ 'has-error': $v.tasks.priority.$error }">
              <label for="exampleInputEmail1">Priority *</label>
              <select class="form-control"  name="" v-model="$v.tasks.priority.$model" id="">
                <option value="">------</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
      
            </select>
            </div>
            
            <div class="form-group" :class="{ 'has-error': $v.tasks.assigned_id.$error }">
             
              <label for="exampleInputEmail1">Assign to </label>
            
            <select class="form-control"  name="" v-model="$v.tasks.assigned_id.$model" id="">
            <option value="">-------</option>
            <option v-for="taskuser in taskUsers" :value="taskuser.id"> <% taskuser.first_name%> <% taskuser.last_name%></option>
            </select>
            </div>

            <div class="form-group" :class="{ 'has-error': $v.tasks.due_date.$error }">
              <label for="exampleInputEmail1">Due Date </label>
              <div class="input-group">
              <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  
               <input readonly type="text" v-model="$v.tasks.due_date.$model" class="form-control"  name="" id="due_date">
               </div>
            </div>
         </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-primary" v-if="modalAction=='edit'" v-on:click="updatetasks()" type="button">Save</a>
            <button class="btn btn-primary" v-if="modalAction=='add'" v-on:click="addTask()" type="button">Save</a>
         </div>
      </div>
   </div>
</div>