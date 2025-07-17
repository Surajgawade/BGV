<div class="content-page">
    <div class="content">
     <div class="container-fluid">
    
     <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Task Details</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>client_new_cases">Task Manager</a></li>
                  <li class="breadcrumb-item active">Task Manager Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                   <li><button class="btn btn-secondary waves-effect btn_clicked btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>client_new_cases"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>

    <div class="row">
      <div class="col-12">
      <div class="page-title-box">
        <div class="text-white div-header">
        Case Details
        </div>
        <br>
          <div style="float: right;">
            <ol class="breadcrumb">
            <li><button class="btn btn-secondary waves-effect edit_btn_click" data-frm_name='save_edit' data-editUrl='<?= ($this->permission['access_task_list_edit']) ? encrypt($details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button></li>
            </ol>
          </div>
      </div>
    </div>
  </div>
        
          <div class="box-body">
            <?php echo form_open_multipart('#', array('name'=>'save_edit','id'=>'save_edit')); ?>
            
            <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$details['id']) ?>">
            <br>
            <div class="row">
              <div class="col-sm-4">
                <label>Client<span class="error"> *</span></label>
                <?php
                  echo form_dropdown('client_id', $clients, set_value('client_id',$details['client_id']), 'class="select2" id="client_id"');
                  echo form_error('client_id');
                ?>
              </div>
              
              <div class="col-sm-4">
                <label>Total Cases<span class="error"> *</span></label>
                <input type="number" name="total_cases" id="total_cases" value="<?php echo set_value('total_cases',$details['total_cases']) ?>" class="form-control">
                <?php echo form_error('total_cases'); ?>
              </div>
              <div class="col-sm-4">
                <label>Task Type<span class="error"> *</span></label>
                <?php
                  echo form_dropdown('type', array('' => 'Select','new case' => 'New Case','insuff cleared' => 'Insuff Cleared','closures' => '
                    Closures'), set_value('type',$details['type']), 'class="select2" id="type"');
                  echo form_error('type');
                ?>
              </div>   
            </div>
            <div class="row">
                           
              <div class="col-sm-4">
                <label>Remarks<span class="error"> *</span></label>
                <textarea class="form-control" name="remarks" id="remarks" rows="1" maxlength="250"><?php echo set_value('remarks',$details['remarks']); ?></textarea>
                <?php echo form_error('remarks'); ?>
              </div> 
              <div class="col-sm-4">
                <label>Case Status <span class="error"> *</span></label>
                <?php

                  echo form_dropdown('case_status', array('' => 'Select Status','wip' => 'WIP','hold' => 'Hold','closed' => 'Closed'), set_value('case_status',$details['status']), 'class="form-control" id="case_status"');
                  echo form_error('case_status');
                ?>
              </div>

              <div class="col-sm-4">
                <label>Task Assign</label>
                <?php
                  unset($assign_ids[0]);   
                  $task_person_id = explode(",",$details['task_person_id']);
                  echo form_multiselect('assign_id[]', $assign_ids, set_value('assign_id',$task_person_id), 'class="select2" id="assign_id"');
                  echo form_error('assign_id');
                ?>
              </div>
               
              
            </div>
            <div class = "row">
            <div class="col-sm-4">
                <label>Status <span class="error"> *</span></label>
                <?php

                  $task_completes_id = explode(",",$details['task_completed_id']); 
                if($details['status'] != "hold")
                { 
                  if(in_array($this->user_info['id'],$task_completes_id))
                  {
                  
                    $person_status = "closed";
                  }
                  else{
                   
                    $person_status = "wip";
                 
                  }
                }
                else{
                    $person_status = "hold";
                }   
                 
                  $per_status = array('' => 'Select Status','wip' => 'WIP','hold' => 'Hold','closed' => 'Closed');
                  echo form_dropdown('status',$per_status , set_value('status',$person_status), 'class="form-control" id="status"');
                  echo form_error('status');
                ?>
              </div>
              
              <?php   
                  if(!empty($pre_post_details[0]['component_ref_no']))
                  {
                    $component_ref_no = $pre_post_details[0]['component_ref_no'];
                  }
                  else{
                    $component_ref_no = "";
                  }

                  $task_completes_id = explode(",",$details['task_completed_id']); 
                
                  if(in_array($this->user_info['id'],$task_completes_id))
                  {
                   
                    if(empty($pre_post_details[0]['id']))
                    { 
                      $css_style = "style = 'display: none;'";
                    }
                    else
                    { 
                      $css_style = "style = 'display: black;'";
                    }
                   
                  }
                  else{
                   
                    $css_style = "style = 'display: none;'";
                 
                  }
               ?>  
                
              <div class="col-sm-4" id= "comp_re_no" <?php echo $css_style;  ?>>
                <label>Mist ID</label>
                <input type="text" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$component_ref_no); ?>" class="form-control">
                  <?php echo form_error('component_ref_no'); ?> 
                  <div id = "candidate_ref_no"></div>
              </div>
              
            </div>
             
            <div class="row">
              <div class="col-sm-4">
                <label>Attachment</label>
                <input type="file" name="ekm_file[]" accept=".eml,.msg" id="ekm_file" value="" class="form-control" multiple="multiple" >
                <?php echo form_error('ekm_file'); ?>
              </div>
              
           <!--   <div class="col-sm-4">
                <label>Task Complete</label>
                <?php
                  unset($assign_ids[0]); 
                  $task_completed_id = explode(",",$details['task_completed_id']);   
                  echo form_multiselect('complete_id[]', $assign_ids, set_value('complete_id',$task_completed_id), 'class="select2" id="complete_id"');
                  echo form_error('complete_id');
                ?>
              </div>-->
            </div> 
            <br>
            <div class="row">
            <div class="col-sm-12 form-group">
                <ol>
                <?php 
                foreach ($attachments as $key => $value) {
                    $url  =  SITE_URL. "uploads/task_file/";
                  
                    ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
                    return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
                  
                }
                ?>
                </ol>
                </div> 
              </div>
            
         
             <div class="clearfix"></div>        
              <div class="box-body">
                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_update" name="btn_update" class="btn btn-primary">Update</button>
                </div>
              </div>
            <?php echo form_close(); ?>
         </div>
        </div>
        <br>
        
        <?php 
    
        if(empty($pre_post_details[0]['id']))
        {
        }
        else
        {
        ?>
        <table style="border-collapse: collapse;width: 100%;">
          <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Client Name</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['client_name'] ?></td>
          </tr> 
          <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Entity</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['entity_name'] ?></td>
          </tr> 
          <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Package</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['package_name'] ?></td>
          </tr>
          <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Component Ref No</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['component_ref_no'] ?></td>
          </tr>
           
          <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Candidate Name</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['candidate_name'] ?></td>
          </tr>
          <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Client / Employment ID</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['client_ref_no'] ?></td>
          </tr>  
          <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Initiation Date</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo convert_db_to_display_date($pre_post_details[0]['initiation_date']) ?></td>
          </tr> 
          <tr> 
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Primary Contact</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['primary_contact'] ?></td>
          </tr>
          <tr>  
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Contact No 2 </td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['contact_two'] ?></td>
          </tr>
          <tr>  
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Contact No 3 </td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><?php echo $pre_post_details[0]['contact_three'] ?></td>
          </tr>
        </table>
      <?php } ?>
</div>
</div>
</div>
</div> 
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script>
$('#case_status').attr("style", "pointer-events: none;");

function myOpenWindow(winURL, winName, winFeatures, winObj)
{

 
  var theWin;

  if (winObj != null)
  {
    if (!winObj.closed) {
      winObj.focus();
      return winObj;
    } 
  }
  theWin = window.open(winURL, winName, "width=900,height=650"); 
  return theWin;
}

$("#save_edit :input").prop("disabled", true);

$(document).on('change', '#status', function(){
var status =  $(this).val();
if(status == "closed")
{ 
    <?php 
    if(empty($pre_post_details[0]['id']))
    { 
      ?>
       $("#comp_re_no").css('display', 'none');
   <?php }
    else
    {?> 
      $("#comp_re_no").css('display', 'block');
  <?php  } ?>
  
}
else{
  $("#comp_re_no").css('display', 'none');
}
});

$("#component_ref_no").keyup(function(){
    
    var ref_no = $(this).val();
    
    $.ajax({
      type:'POST',
      url:'<?php echo ADMIN_SITE_URL.'client_new_cases/check_ref_no_exists'; ?>',
      data:'ref_no='+ref_no,
      success:function(jdata) {
        if(jdata.ref_no == 'na')
        {
            $('#candidate_ref_no').html('');
            $('#btn_update').attr("disabled", true);
        }
        else
        {
           $('#candidate_ref_no').html('Existing');
           $('#btn_update').removeAttr("disabled");
                
        }
      }
    });  
  });

$(document).ready(function(){

  $('#save_edit').validate({
    rules : {
      update_id : {
        required : true,
        greaterThan : 0
      },
       remarks : {
        required : true
      },
       client_id : {
        required : true,
        greaterThan : 0
      },
      
       total_cases : {
        required : true
      },
      type : {
        required : true
      },
      status : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Select Client"
      },
      remarks : {
        required : "Enter Remarks"
      },
      status : {
        required : "Select Status"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'client_new_cases/frm_update'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_update').attr('disabled',true);
        },
        complete:function(){
          $('#btn_update').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            window.location = jdata.redirect;
          } else  {
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

  var emp_log_tbl =  $('#tbl_datatable').DataTable( { "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 5,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });
  
});


$('#add_verificarion_result').validate({
      rules: {
        tbl_addrver : {
          required : true
        },
        addrverres_id : {
          required : true
        },
        mode_of_verification : {
          required : true
        },     
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        },
         resident_status : {
          required : true
        },
         verified_by : {
          required : true
        },
        addr_proof_collected : {
          required : true
        },
        res_address : {
          required : true
        },
        res_city : {
          required : true
        },
         res_pincode : {
          required : true
        },
         res_state : {
          required : true
        },
         res_stay_from : {
          required : true
        },
         res_stay_to : {
          required : true
        },
        address_action : {
          required : true
        },
          city_action : {
          required : true
        },
        pincode_action : {
          required : true
        },
       state_action : {
          required : true
        },
         stay_from_action : {
          required : true
        },
         stay_to_action : {
          required : true
        }

      },
      messages: {
        tbl_addrver : {
          required : "ID"
        },
        addrverres_id : {
          required : "ID"
        },
        mode_of_verification : {
          required : "Select Mode Of Verification"
        },
        closuredate : {
          required : "Select Closure Date"
        },
        remarks : {
          required : "Enter Remarks"
        }, 
         resident_status : {
          required : "Select Resident Status"
        },
         verified_by : {
           required : "Enter Verified By"
        },
        res_address : {
           required : "Enter Street Address"
        },
        res_city : {
           required : "Enter City"
        },
         res_pincode : {
           required : "Enter Pincode"
        },
         res_state : {
           required : "Select State"
        },
         res_stay_from : {
           required : "Enter Stay Form"
        },
         res_stay_to : {
           required : "Enter Stay To"
        },
         address_action : {
           required : "Please Selected address Action"
        },
         city_action : {
           required : "Please Selected city Action"
        },
          pincode_action : {
           required : "Please Selected pincode Action"
        },
        
        state_action : {
           required : "Please Selected state Action"
        },
        stay_from_action : {
           required : "Please Selected stay from  Action"
        },
        stay_to_action : {
           required : "Please Selected stay to  Action"
        }
      },
      submitHandler: function(form) 
      { 
        var activityData = $('#add_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=1'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'activity_log/save_activity'; ?>",
          data : activityData,
          type: 'post',
          async:false,
          cache: false,
          processData:false,
          dataType:'json',
          success: function(jdata){
            $('#activityModel').modal('hide');
         
        var activityData = new FormData(form);
        activityData.append('action_val',$("#action option:selected").text());
        activityData.append('component_type',$("#action option:selected").text());
        activityData.append('activity_last_id',jdata.last_id);
        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_result'; ?>',
          data:  activityData,
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbresult').attr('disabled','disabled');
          },
          complete:function(){
           // $('#sbresult').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.redirect){
              //show_alert(message,'success');
              //location.reload();
              //window.location = jdata.redirect;


       var selectedData = new Array();
           var selectedData = new Array();
            $('.row_position>tr').each(function() {
              $(this).removeClass('ui-sortable-handle');
                selectedData.push($(this).attr("class"));
            });

  
 //var currentRow = $(".order:checked").closest("tr");
  
   
   // var col = currentRow.find("td:eq(1)").text(); 
  
    //console.log(selectedData);
  //  console.log(col);



 var values = new Array();
       $.each($("input[name='order[]']:checked"), function() {
           var data = $(this).parents('tr:eq(0)');
            values.push( data.find("td:eq(1)").text());             
       });

    

        // alert(selectedData) ;
         updateOrder(values);
    

       function updateOrder(data) {
        $.ajax({
            url:"<?php echo ADMIN_SITE_URL.'client_new_cases/rearragged_file_name/'.$details['id'].'';?>",
            type:'post',
            data:{position:data},
            success:function(jdata){
               var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {

               // $('#add_verificarion_result').submit();
                show_alert(message,'success');
                location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
        })
    }
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
         }
        });  
      }
  });

</script>

<script type="text/javascript">
  $(document).on('click', '.delete', function(){  
           var task_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'client_new_cases/delete/';?>",  
                     method:"POST",  
                     data:{task_id:task_id},  
                     success: function(jdata){
                    var message =  jdata.message || '';
                    (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
                    if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                      show_alert(message,'success');

                     setTimeout(function(){
                             window.location = "<?php echo ADMIN_SITE_URL.'client_new_cases/';?>";
                       },1000);
                    }
                    if(jdata.status == <?php echo ERROR_CODE; ?>){
                      show_alert(message,'error'); 
                    }
                  }
                });  
           }  
           else  
           {  
                return false;       
           }  
      }); 


   $('#activityModelClk').click(function(){
    var url = $(this).data("url");
  
    $('.append-activity_view').load(url,function(){});
    $('#activityModel').modal('show');
  });


  $(document).on('click','#clkAddResultModel',function() {
  if($("#cases_activity").valid())
  {
    $('#activityModel').modal('hide');
    var url = $(this).data('url');

    $('#append_result_model').load(url,function(){
      $('#addAddResultModel').modal('show');
    });
  }

  $(document).on('click',".rto_clicked",function() {

    var txt_val = $(this).data('val');
    var nameArr =  txt_val.substring(txt_val.indexOf('_')+1)  
    var actual_value=document.getElementById(nameArr).value;

    var rtb_val = $(this).val();

    if(rtb_val == 'no'){
      $("#"+txt_val).val("");
    }
    else if(rtb_val == 'yes'){
      $("#"+txt_val).val(actual_value);
    } else if(rtb_val == 'not-obtained') {
    
      $("#"+txt_val).val("Not Obtained");
    }
    else if(rtb_val == 'not-verified') {
    
      $("#"+txt_val).val("Not Verified");
    }
});
});      
</script>
<!--<script type="text/javascript">
  $('#sbresult').on('click', function(e){

      e.preventDefault();

       var selectedData = new Array();
           var selectedData = new Array();
            $('.row_position>tr').each(function() {
              $(this).removeClass('ui-sortable-handle');
                selectedData.push($(this).attr("class"));
            });

  
 //var currentRow = $(".order:checked").closest("tr");
  
   
   // var col = currentRow.find("td:eq(1)").text(); 
  
    //console.log(selectedData);
  //  console.log(col);



 var values = new Array();
       $.each($("input[name='order[]']:checked"), function() {
           var data = $(this).parents('tr:eq(0)');
            values.push( data.find("td:eq(1)").text());             
       });

    

        // alert(selectedData) ;
         updateOrder(values);
    

       function updateOrder(data) {
        $.ajax({
            url:"<?php echo ADMIN_SITE_URL.'client_new_cases/rearragged_file_name/'.$details['id'].'';?>",
            type:'post',
            data:{position:data},
            success:function(jdata){
               var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {

               // $('#add_verificarion_result').submit();
                show_alert(message,'success');
                location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
        })
    }

      //alert(sortable_data);
            
  })
</script>-->