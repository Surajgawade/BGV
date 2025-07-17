<?php 
if(count($education_lists) >= '1')
{
  if(in_array('eduver', explode(',',$component_check))) 
  {
    if($education_details_component_check == "1")
    { 
  ?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='education_add_frm' data-url="<?=  CLIENT_SITE_URL.'candidate_mail/education_edit/'.$cand_id  ?>" ><i class="fa fa-minus"></i> Update </button>

    <?php
    }
    else 
    {
    ?>

     <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='education_add_frm' data-url="<?= CLIENT_SITE_URL.'candidate_mail/education_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Education</button> 
   <?php 
    }
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='education_add_frm' data-url="<?=  CLIENT_SITE_URL.'candidate_mail/education_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Education</button>
  
<?php
}
?>
<div class="clearfix"></div>
  
<table id="tbl_datatable_education" class="table table-bordered"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
     <thead>
        <tr>
          <th>Sr No</th>
          <th>Comp INT</th>
          <th>Comp Ref No</th>
          <th>School/College</th>
          <th>University</th>
          <th>Qualification</th>
          <th>Month of Passing</th>
          <th>Year of Passing</th>
          <th>Major</th>
          <th>Grade Class Marks</th>
          
        </tr>
  </thead>
    
    <tbody>
    <?php
    if($education_lists == "Not Permission")
    {
        echo "<div id = 'permission_education'></div>";
    } 
    else
    {         
      $counter = 1;

      if(count($education_lists) > 0)
      {  
        foreach($education_lists as $education_list)
        {

          echo  "<tr>";
          echo  "<td>".$counter."</td>";
          echo  "<td>".convert_db_to_display_date($education_list['iniated_date'])."</td>";
          echo  "<td>".$education_list['education_com_ref']."</td>";
          echo  "<td>".$education_list['school_college']." </td>";
          echo  "<td>".$education_list['university_board']." </td>";
          echo  "<td>".$education_list['qualification']." </td>";
          echo  "<td>".$education_list['month_of_passing']." </td>";
          echo  "<td>".$education_list['year_of_passing']." </td>";
          echo  "<td>".$education_list['major']." </td>";
          echo  "<td>".$education_list['grade_class_marks']." </td>";
          echo  "</tr>";
          $counter++;
        }
      }
      else
      {
          echo "<tr><td colspan='9' style='text-align:center;'>No Record Found</td></tr>";
      }
    }
    ?>
  </tbody>
</table>
<div class="education_add_frm"></div>

<script type="text/javascript">
  $(document).ready(function(){

  if ($("#permission_education").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }
 
});
</script>
<script>
$(function ()  {

 var add_otable = $('#tbl_datatable_education').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "scrollX": true,
      "ordering": true,
      "bInfo": false,
      "autoWidth": false,
      "language": {"emptyTable": "No Record Found"},
      "lengthChange": false,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

  add_otable.columns.adjust().draw();

  add_otable.columns().every( function () {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
       if ( that.search() !== this.value ) {
           that.search( this.value ).draw();
       }
    });
  });

  $('#tbl_datatable_education tbody').on( 'dblclick', 'tr', function () {
    var row_data = add_otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'Candidate_mail/education_view_from_cands/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.education_add_frm').html(html);
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
        scrollTop: $(".education_add_frm").offset().top
      }, 2000);
   });
 });

</script>
