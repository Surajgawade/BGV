<div id="showvendorModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Vendor Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div id="append_vendor_model"></div>

       <div class="tab-pane" id="tab_vendor_log1">
              <table id="tbl_vendor_log1" class="table table-bordered datatable_logs"></table>
            </div>
      <div class="modal-footer">
        <button type="button" id="vendor_details_back" name="vendor_details_back" class="btn btn-default btn-sm pull-left">Back</button>
     <!--   <button type="submit" id="vendor_result_submit" name="vendor_result_submit" class="btn btn-success btn-sm">Save</button>-->
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
         

             <div class="row">
              <div class="col-12">
               <div class="card m-b-20">
                 <div class="card-body">
                
                    <table id="example1" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                        <tr class="filters">
                          <th><input name="select_all" value="1" id="example1-select-all" class="example1-select-all" type="checkbox" /></th>

                          <th>Sr No</th>
                          <th>Date</th>
                          <th>By</th>       
                          <th>Comp Ref No</th>
                          <th>Vendor Name</th>
                          <th>City</th>
                         
                          <th>State</th>
                          <th>Addtn Cost</th>
                          <th>Total Cost</th>
                        
                          <th >Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                 
                          $counter = 1;

                          foreach($detail as $list)
                          {  
                             if(($list['accept_reject_cost'] == "0" || $list['accept_reject_cost'] == NULL))
                             {
 
                            echo  "<tr><td><input type='checkbox' name='cases_id[]' id='cases_id' value='".$list['vendor_master_log_id']."'> </td>";
                            echo  "<td>".$counter."</td>";
                            echo  "<td>".convert_db_to_display_date($list['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                            echo  "<td>".$list['created_by']." </td>";
                            echo  "<td>".$list['identity_com_ref']."</td>";
                            echo  "<td>".$list['vendor_name']."</td>";
                            echo  "<td>".$list['city']."</td>";
                           // echo  "<td>".$list['pincode']."</td>";
                            echo  "<td>".$list['state']."</td>";
                            echo  "<td>".$list['additional_cost']."</td>";
                            echo  "<td>".$list['cost']."</td>";
                           
                            echo  "<td style='white-space: nowrap;'>";
                              echo '<button data-id='.$list['vendor_master_log_id'].' data-url ="'.ADMIN_SITE_URL.'identity/View_vendor_log/'.$list['vendor_master_log_id'].'" data-toggle="modal" data-target="#showvendorModel"  class="btn btn-sm btn-info  showvendorModel"> View </button>';
                            ?>
                            

                            <?php if(($list['accept_reject_cost'] == "2" || $list['accept_reject_cost'] == "0" || $list['accept_reject_cost'] == NULL)  && (!empty($list['vendor_cost_details_id'])))
                            { ?>
                            <button class="btn btn-sm btn-info approve"  data-url="<?= ADMIN_SITE_URL.'identity/apperovecost/' ?>" data-id="<?php  echo $list['vendor_cost_details_id']  ?>" data-vendor_master_id="<?php  echo $list['vendor_master_log_id']  ?>" data-amount="<?php  echo $list['cost'] ?>"  data-add_amount="<?php  echo $list['additional_cost'] ?>">Approve</button>
                          <?php } ?>
                           
                               <?php if(($list['accept_reject_cost'] == "1" || $list['accept_reject_cost'] == "0" || $list['accept_reject_cost'] == NULL) && (!empty($list['vendor_cost_details_id'])))
                               { ?>
                              <button class="btn btn-sm btn-info reject"    data-url="<?= ADMIN_SITE_URL.'identity/rejectcost/' ?>" data-id="<?php  echo $list['vendor_cost_details_id']  ?>" data-vendor_master_id="<?php  echo $list['vendor_master_log_id']  ?>" data-amount="<?php  echo $list['cost'] ?>"  data-add_amount="<?php  echo $list['additional_cost'] ?>">Reject</button>
                            <?php } ?>


                     <?php   echo"</td>";
                            echo "</tr>";
                          }
                            $counter++;
                        }
                       

                        ?>
                      </tbody>
                    </table>
                   
                  </div>
                </div>
                </div>
              </div>
            </div>   



<script type="text/javascript">



  $(".approve").click(function(e) {

     e.preventDefault();


   var id   = $(this).attr("data-id");
   var cost   = $(this).attr("data-amount");
   var add_cost   = $(this).attr("data-add_amount");
   var vendor_master_log_id   = $(this).attr("data-vendor_master_id");

   
    $.ajax({
          type: "POST",
          url: '<?php echo ADMIN_SITE_URL.'identity/apperovecost' ?>',
          data:'id='+id+'&cost='+cost+"&add_cost="+add_cost+"&vendor_master_log_id="+vendor_master_log_id,
        
          dataType:'json',
          beforeSend:function(){
            $('.approve').attr('disabled','disabled');
          },
          complete:function(){
          //  $('.approve').removeAttr('disabled',false);
          },
          success: function(jdata){
            //$("vendor_cost_approve").load($(this).data("url"));
         
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              setTimeout(function() 
              {
                  window.location;  //Refresh page
              }, 2000);
                
            }else {
              show_alert(message,'error'); 

               setTimeout(function() 
              {
               location.reload();  //Refresh page
              }, 2000);
            }
          }
    });
});

  $(".reject").click(function(e) {

     e.preventDefault();
     var id   = $(this).attr("data-id");
    var vendor_master_log_id   = $(this).attr("data-vendor_master_id");
    $.ajax({
          type: "POST",
          url: '<?php echo ADMIN_SITE_URL.'identity/rejectcost' ?>',
          data:'id='+id+"&vendor_master_log_id="+vendor_master_log_id,
        
          dataType:'json',
          beforeSend:function(){
            $('.reject').attr('disabled','disabled');
          },
          complete:function(){
           // $('.reject').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
               setTimeout(function() 
              {
               location.reload();  //Refresh page
              }, 2000);
            }else {
              show_alert(message,'error'); 

               setTimeout(function() 
              {
               location.reload();  //Refresh page
              }, 2000);
            }
          }
    });
});
</script>

<script>
var select_one = [];
$(function ()  {
  var table = $('#example1').DataTable({
     scrollX:true,
      "iDisplayLength": 25,
      "lengthMenu": [[20, 40, 100, -1], [20, 40, 100, "All"]],
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
      }],
      'order': [1, 'asc']
  });

  $('.example1-select-all').on('click', function(){
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

 
});

$(document).on('click','.showvendorModel',function() {

    var url = $(this).data('url');
    var id = $(this).data('id');



    $('#append_vendor_model').load(url,function(){
      $('#showvendorModel').modal('show');
     
      $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'identity/vendor_logs_cost/' ?>',
      data: 'id='+id,
      beforeSend :function(){
        jQuery('#tbl_vendor_log1').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {

            $('#tbl_vendor_log1').html(message);

        }
        else
        {
          $('#tbl_vendor_log1').html(message);
        }

          
          
        }
    }); 

    });
   
});


</script>
