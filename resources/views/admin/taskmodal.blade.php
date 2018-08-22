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
            <div class="form-group" :class="">
              <label for="exampleInputEmail1">Task Name *</label>
              <input type="text" class="form-control" v-model="tasks.task_name" id="exampleInputEmail1" placeholder="Enter Insurance Name">
            </div>
            <div class="form-group" :class="">
            
              <label for="exampleInputEmail1">Task Detail </label>
             <textarea name="" id="" v-model="tasks.task_detail" class="form-control" cols="30" rows="3"></textarea>
            </div>
            <div class="form-group" :class="">
             
              <label for="exampleInputEmail1">Task Users </label>
            
            <select class="form-control"  name="" v-model="tasks.assigned_id" id="">
            <option value="">Please Selct</option>
            <option v-for="taskuser in taskUsers" :value="taskuser.id"> <% taskuser.first_name%> <% taskuser.last_name%></option>
            </select>
            </div>
            <div class="form-group" :class="">
              <label for="exampleInputEmail1">Due Date </label>
               <input readonly type="text" v-model="tasks.due_date" class="form-control"  name="" id="due_date">
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