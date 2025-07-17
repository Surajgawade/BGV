            <div class="row">
              <div class="col-12">
                <div class="card m-b-20">
                    <div class="card-body">
                    <?php echo form_open('#', array('name'=>'export_to_excel_digital_entries','id'=>'export_to_excel_digital_entries')); ?>

                        <button class="btn btn-secondary waves-effect" type="submit"  id="export" style="float:right;"><i class="fa fa-download"></i> Export</button> 
                        
                    <?php echo form_close(); ?>
                    <br>
                     <br>
                    <table id="tbl_digital_vendor_log" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                        <tr >
                          <th>SrId</th>
                          <th>Rec Dt</th>
                          <th>Candidate Name</th>       
                          <th>State</th>
                          <th>Copy Link</th>
                          <th>Mail</th>
                          <th>Date</th>
                          <th>SMS</th>
                          <th>Date</th>
                          <th>Client</th>
                          <th>Sub Client</th>
                          <th>Component ID</th>
                          <th>Client ID</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                         $i = 1;
                          $html_view = '';
                        foreach ($vendor_executive_list as $key => $value) {

                          
                            $login_url = SITE_URL.'av/'.base64_encode($value['address_id']);

                                
                            $html_view .= "<tr id = ".$value['address_id']." url = ".ADMIN_SITE_URL."address/View_vendor_log_digital_entries_view/".$value['address_id']." >";
                            $html_view .= "<td>".$i."</td>";
                            $html_view .= "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                            $html_view .= "<td>".$value['CandidateName']."</td>";
                            $html_view .= "<td>".$value['state']."</td>";
                            $html_view .= "<td><button class='btn btn-info btn-sm copyLink' data-link='".$login_url ."' id=".$i.">Copy Link</button></td>";
                            $html_view .= "<td>";
                            if($value['cands_email_id'] != ""  || $value['cands_email_id'] != NULL)
                            {
                            $html_view .= "<button class='btn btn-info btn-sm trigger_email_again' id='".$value['address_id']."'>Send ( ".$value['is_mail_sent']." )</button>";
                            }
                            $html_view .= "</td>";
                            $html_view .=  "<td>".convert_db_to_display_date($value['last_email_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)."</td>";
                            $html_view .="<td>";
                            if($value['CandidatesContactNumber'] != ""  || $value['CandidatesContactNumber'] != NULL)
                            {
                            $html_view .= "<button class='btn btn-info btn-sm trigger_sms_again' id='".$value['address_id']."'>Send ( ".$value['is_sms_sent']." )</button>";
                            }
                            $html_view .= "</td>";
                            
                            $html_view .=  "<td>".convert_db_to_display_date($value['last_sms_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)."</td>";
          
                            $html_view .= "<td>".$value['clientname']."</td>";
                            $html_view .= "<td>".$value['entity_name']."</td>";
                          
                            $html_view .= "<td>".$value['add_com_ref']."</td>";
                            $html_view .= "<td>".$value['ClientRefNumber']."</td>";
                            $html_view .= '</tr>';  

                            $i++;  
                        }
                        echo  $html_view;
                        ?>

                      </tbody>
                    </table>
                   
                  </div>
                </div>
                </div>
              </div>
            </div> 

<script type="text/javascript">
$(function ()  {
  var table = $('#tbl_digital_vendor_log').DataTable({

        bSortable: true,
        bRetrieve: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
      "iDisplayLength": 20,
      "lengthMenu": [[20, 40, 100, -1], [20, 40, 100, "All"]],
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
      }],
      'order': [1, 'asc']
  });

  $('.tbl_digital_vendor_log-select-all').on('click', function(){
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

});


$('#tbl_digital_vendor_log').on('dblclick', 'tr', function () { 
 
    var id = $(this).attr('id');
    $('.tbl_addrver').val(id);
    var url = $(this).attr('url');
    $('#append_vendor_model_digital_entries_view').load(url,function(){
      $('#showvendorModelDigitalView').modal('show');
      $('#showvendorModelDigitalView').addClass("show");
      $('#showvendorModelDigitalView').css({background: "#0000004d"});
      
    });
  });
 

 $(document).on('click', '.copyLink', function() {
    let urlcopy = $(this).data('link');

    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(urlcopy).select();
    document.execCommand("copy");
    $temp.remove();

    $(this).text('link copied').removeClass('btn-info').addClass('btn-primary');
    setTimeout(function(){$('.copyLink').text('Copy Link').removeClass('btn-primary').addClass('btn-info');}, 2000);
   });


   $('#tbl_digital_vendor_log tbody').on('click', '.trigger_email_again', function (){
        if(confirm('You need send message email again') === true) {
            
            var id = $(this).attr('id'); 
            if(id != "") {
                $.ajax({
                    url: "<?php echo ADMIN_SITE_URL.'address/trigger_email_again'; ?>",
                    type: 'post',
                    data: {send_id:id},
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).text('sending...').attr('disabled','disabled');
                    },
                    complete: function() {
                        $(this).text('Send').removeAttr('disabled');
                    },
                    success: function(jdata) {
                        var message =  jdata.message || '';
                        if(jdata.status == 200) {
                            show_alert(message,'success',true);
                            location.reload();
                            return;
                        } else {
                           show_alert(message,'error'); 
                        }
                    },
                    error: function (jqXHR, exception) {
                        show_alert(jqXHR, 'danger');
                    }
                });
            }
        }
        else {
            show_alert('Cancelled ','info');
        }       
    });


     $('#tbl_digital_vendor_log tbody').on('click', '.trigger_sms_again', function (){
        if(confirm('You need send message sms again') === true) {
            
            var id = $(this).attr('id'); 

            if(id != "") {
                $.ajax({
                    url: "<?php echo ADMIN_SITE_URL.'address/trigger_sms_again'; ?>",
                    type: 'post',
                    data: {send_id:id},
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).text('sending...').attr('disabled','disabled');
                    },
                    complete: function() {
                        $(this).text('Send').removeAttr('disabled');
                    },
                    success: function(jdata) {
                        var message =  jdata.message || '';
                        if(jdata.status == 200) {
                          show_alert(message,'success',true);
                          location.reload();
                          return;
                        } else {
                           show_alert(message,'error'); 
                        }
                    },
                    error: function (jqXHR, exception) {
                        show_alert(jqXHR, 'danger');
                    }
                });
            }
        }
        else {
            show_alert('Cancelled ','info');
        }       
    });

$('#export_to_excel_digital_entries').validate({ 
        rules: {
       
        },
        messages: {
        
        },
        submitHandler: function(form) 
        {      
        
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'digital_closure/export_to_excel'; ?>',
            data: '',
            type: 'POST',
            beforeSend:function(){
              $('#export').text('exporting..');
              $('#export').attr('disabled','disabled');
            },
            complete:function(){
              $('#export').text('Export');
              $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xls");
            $a[0].click();
            $a.remove();      
            location.reload();
        });     
        }
  });

</script>
