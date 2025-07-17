<?php 
if(count($employment_lists) >= '1')
{
  if(in_array('empver', explode(',',$component_check))) 
  { 
    if($employment_details_component_check == "1")
    {
    ?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='emp_add_frm' data-url="<?=  CLIENT_SITE_URL.'candidate_mail/employment_edit/'.$cand_id  ?>" ><i class="fa fa-minus"></i> Update </button>

    <?php
    }
    else 
    {
    ?> 
     <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='emp_add_frm' data-url="<?= CLIENT_SITE_URL.'candidate_mail/employment_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Employment</button>
    <?php
    }
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='emp_add_frm' data-url="<?=  CLIENT_SITE_URL.'candidate_mail/employment_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Employment</button>
  
<?php
}
?>

<div class="clearfix"></div>

<table id="tbl_datatable_emp" class="table table-bordered"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
   
   <thead>
        <tr>
          <th>Sr No</th>
          <th>Comp INT</th>
          <th>Comp Ref No</th>
          <th>Company Name</th>
          <th>Employee Code</th>
          <th>Employment Type</th>
          <th>Employment From</th>
          <th>Employment To</th>
          <th>Designation</th>
        </tr>
  </thead>

    <tbody>
           <?php
        if($employment_lists == "Not Permission")
        {
            echo "<div id = 'permission_employment'></div>";
        }
        else
        {  
            $counter = 1;
            
            if(count($employment_lists) > 0)
            {  

              foreach ($employment_lists as $employment_list)
              {
                echo  "<tr>";
                echo  "<td>".$counter."</td>";
                echo  "<td>".convert_db_to_display_date($employment_list['iniated_date'])."</td>";
                echo  "<td>".$employment_list['emp_com_ref']."</td>";
                echo  "<td>".$employment_list['coname']." </td>";
                echo  "<td>".$employment_list['empid']." </td>";
                echo  "<td>".$employment_list['employment_type']." </td>";
                echo  "<td>".$employment_list['empfrom']." </td>";
                echo  "<td>".$employment_list['empto']." </td>";
                echo  "<td>".$employment_list['designation']." </td>";
                echo  "</tr>";
                $counter++;
              }
            }
            else
            {
               echo "<tr><td colspan='8' style='text-align:center;'>No Record Found</td></tr>";
            }
        }
      ?>
    </tbody>
</table>
<div class="emp_add_frm"></div>
<script type="text/javascript">
  $(document).ready(function(){

  if ($("#permission_employment").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   } 
  
});
</script>
<script>
$(function ()  {


  var otable = $('#tbl_datatable_emp').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "scrollX": true,
      "autoWidth": false,
      "ordering": true,
      "bInfo": false,
      "language": {"emptyTable": "No Record Found"},
      "lengthChange": false,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

  otable.columns.adjust().draw();

  otable.columns().every( function () {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
       if ( that.search() !== this.value ) {
           that.search( this.value ).draw();
       }
    });
  });

  $('#tbl_datatable_emp tbody').on( 'dblclick', 'tr', function () {
    var row_data = otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'Candidate_mail/employment_view_from_cands/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.emp_add_frm').html(html);
            }
        });
    }
  });

});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $(".append_candidate_tab").click(function() {
      $('html, body').animate({
        scrollTop: $(".emp_add_frm").offset().top
      }, 2000);
   });
 });

</script>