<div class="row" style="margin-left: 10px; margin-right: 10px;">  
<div class="col-sm-3 form-group">
	<label>Action<span class="error"> *</span></label>
    <input type="hidden" name="auto_tag" id="auto_tag" value="">
    <?php
    if($this->permission['access_candidates_list_special_acitivity_status'] == 0) {
        $activity_mode=array_flip($activity_mode);
        unset($activity_mode['Change Of Address']);
        unset($activity_mode['NA']);
        unset($activity_mode['Stop Check']);
        unset($activity_mode['Not Applicable']);
        $activity_mode=array_flip($activity_mode);
    }
      
    echo form_dropdown('action', $activity_mode, set_value('action'), 'class="select2 activity_filter" data-next="activity_mode" id="action"');
    echo form_error('action'); 
  ?>
    <div id ="web_status_check" class="error" style="color:red;"></div>
</div>

<div class="col-sm-3 form-group">
	<label>Activity Mode <span class="error"> *</span></label>
    <?php
    $activity_type = array(0 =>'Select Activity');
    echo form_dropdown('activity_mode', $activity_type, set_value('activity_mode'), 'class="select2 activity_filter" data-next="activity_type" id="activity_mode"');
    echo form_error('activity_mode'); 
    ?>
</div>
<div class="col-sm-3 form-group">
	<label>Activity Type<span class="error"> *</span></label>
    <?php
    echo form_dropdown('activity_type', $activity_type, set_value('activity_type'), 'class="select2 activity_filter" data-next="activity_status" id="activity_type"');
    echo form_error('activity_type'); 
    ?>
</div>

<div class="col-sm-3 form-group">
	<label>Activity Status<span class="error"> *</span></label>
    <?php
    echo form_dropdown('activity_status', $activity_type, set_value('activity_status'), 'class="select2"  id="activity_status"');
    echo form_error('activity_status'); 
    ?>
</div>
</div>
<div id="hide_two_col">
<div class="row" style="margin-left: 10px; margin-right: 10px;">  

  <div class="col-sm-4 form-group">
  	<label> Next Follow Up Date <span class="error"> *</span></label>
  	<input type="text" name="next_follow_up_date" id="next_follow_up_date" value="<?php echo set_value('next_follow_up_date',date('Y-m-d', strtotime(date('Y-m-d').' +1 day')));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
  </div>
  <div class="col-sm-8 form-group">
  	<label>Remarks <span class="error"> *</span></label>
  	<input type="text" name="remarks" id="remarks" value="<?php echo set_value('remarks');?>" class="form-control">
  	<?php echo form_error('remarks'); ?>
  </div>
</div>
</div>
<script>
$(document).ready(function(){  
  
	$('.myDatepicker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
		todayHighlight: true,
		startDate: new Date()
  });

  $('#cases_activity').validate({
    rules : {
      activity_status : {
        required : true,
        greaterThan : 0
      },
      activity_mode : {
        required : true,
        greaterThan : 0
      },
      activity_type : {
        required : true,
        greaterThan : 0
      },
      action : {
        required : true,
        greaterThan : 0
      },
      next_follow_up_date : {
        required : true,
        greaterThan : 0
      },
      remarks : {
        required : true
      }
    },
    messages : {
      activity_status : {
        required : "Select Activity Status ",
        greaterThan : "Select Activity Status "
      },
      activity_mode : {
        required : "Select Activity Mode"
      },
      activity_type : {
        required : "Select Activity Type"
      },
      action : {
        required : "Select Action"
      },
      next_follow_up_date : {
        required : "Select Date"
      },
      remarks : {
        required : "Enter Remarks"
      }
    }, 
    submitHandler: function(){
      var data = $('#cases_activity').serialize()+ "&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text();
      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'activity_log/save_activity'; ?>",
        data : data,
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#activity_btn').val('saving...');
          $('#activity_btn').attr('disabled','disabled');
        },
        complete:function(){
          $('#activity_btn').removeAttr('disabled');
          $('#activity_btn').val('Save');                
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
            $('#activityModel').modal('hide');
            $('#cases_activity')[0].reset();
            show_alert(message,'success');
            location.reload();
          } else {
            show_alert(message,'error'); 
          }
        }
      });
    }
  });

  $('.activity_filter').on('change',function(){
    var activity_status = $(this).val();
    var next = $(this).data('next');
    var confinue = '';
    var selected = "<?php echo set_value('activity_status'); ?>";
    if(activity_status != 0 && next != '')
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'activity_log/activity_dropdown_mode'; ?>',
            data:'activity_status='+activity_status+'&selected='+selected+'&name='+next,
            dataType:'json',
            beforeSend :function(){
              jQuery('#'+next).find("option:eq(0)").html("Please wait..");
            }, success:function(jdata) {

              $('#'+next).empty();
              confinue = jdata.continue_button.add_result;
            
              $.each(jdata.list, function(key, value) {
                $('#'+next).append($("<option></option>").attr("value",key).text(value));
              });
              
              $('#auto_tag').val(confinue);
       
              if(next == 'activity_mode'  || next == 'activity_type')
              {
                $("#clkAddResultModel").hide();
                $("#activity_btn").hide();
              }
              if(next == 'activity_status')
              {
                 $("#clkAddResultModel").show();
                 $("#activity_btn").show();
              }
              if(next == 'activity_mode') {
                if(confinue != 1) {
                  $('#hide_two_col').show();
                  $('#btn_action').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button><button type="submit" id="activity_btn" name="activity_btn" class="btn btn-success btn-sm"> Save</button>');
                  $("#activity_btn").hide();

                } else {
                  

                  $('#hide_two_col').hide();
                  $('#btn_action').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button><button type="button" id="clkAddResultModel" data-url="<?php echo $add_result_url;?>" name="" class="btn btn-success btn-sm"> Continue</button>');

                   $("#clkAddResultModel").hide();
                }
              }
            }
        });
    }
	});

  $('#activity_status').on('change',function(){
    var activity_status = $(this).val();
    var selected = "<?php echo set_value('activity_mode'); ?>";
    if(activity_status != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'activity_log/activity_dropdown_mode'; ?>',
            data:'activity_status='+activity_status+'&selected='+selected+'&name=remarks',
            success:function(html)
            {
              jQuery('#remarks').val(html);
            }
        });
    }
	});



  $('#action').on('change',function(){
    
    var action = $("#action option:selected").text();
    if(action == 'YTR')
    {
      $('#next_follow_up_date').on('change',function(){
                
          var next_follow_up_date  =   $('#next_follow_up_date').val();

          $('#remarks').val('Candidate to be relieved on '+next_follow_up_date);
        
      });
    }  
  });
  

});
</script>
<script type="text/javascript">
    $(".select2").select2();
</script>