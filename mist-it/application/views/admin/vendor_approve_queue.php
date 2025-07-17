<div class="content-wrapper">
    <section class="content-header">
      <h1>Case Assign to Vendor</h1>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
              <?php echo form_dropdown('cases_assgin', array('0' => 'Select','1' => 'Assign','2' => 'Reject'), set_value('cases_assgin'), 'class="form-control" id="cases_assgin"');?>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-2 form-group reject_reason" style="display: none;">
              <input type="text" name="reject_reason" maxlength="200" id="reject_reason" placeholder="Reason" class="form-control">
              </div>
              <input type="button" name="btn_assign" id="btn_assign" class="btn btn-info btn-md" value="Assign">
              <div class="clearfix"></div>
              <table id="example" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th><input name="select_all" value="1" id="example-select-all" class="example-select-all" type="checkbox" /></th>
                    <th>Sr No</th>
                    <th>Comp Ref No</th>
                    <th>Vendor Name</th>
                    <th>City</th>
                    <th>Pincode</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                    foreach($lists as $list)
                    {
                      echo  "<tr><td><input type='checkbox' name='cases_id[]' id='cases_id' value='".$list['id']."'> </td>";
                      echo  "<td>".$counter."</td>";
                      echo  "<td>".$list['add_com_ref']."</td>";
                      echo  "<td>".$list['vendor_name']."</td>";
                      echo  "<td>".$list['city']."</td>";
                      echo  "<td>".$list['pincode']."</td>";
                      echo  "<td>".$list['created_by']." </td>";
                      echo  "<td>".convert_db_to_display_date($list['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "</tr>";
                      $counter++;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>

<script>
var select_one = [];
$(function ()  {
  var table = $('#example').DataTable({
      "iDisplayLength": 25,
      "lengthMenu": [[20, 40, 100, -1], [20, 40, 100, "All"]],
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
      }],
      'order': [1, 'asc']
  });

  $('.example-select-all').on('click', function(){
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

  $('#cases_assgin').on('change', function(){
    var cases_assgin_action = $('#cases_assgin').val();
    (cases_assgin_action == 1) ? $('.reject_reason').hide() : $('.reject_reason').show();
  });
});

$(document).on('click', '#btn_assign', function(){
    var cases_assgin_action = $('#cases_assgin').val();
    var reject_reason = $('#reject_reason').val();
    $.each($("input[name='cases_id[]']:checked"), function(){            
        select_one.push($(this).val());
    });
    if(cases_assgin_action != 0 && select_one != "")
    { 
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'approval_queue/final_assigning/' ?>',
          data : 'action='+cases_assgin_action+'&cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              lovation.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });      
    } else {
      $("#cases_assgin option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
});
</script>