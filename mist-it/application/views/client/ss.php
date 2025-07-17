<?php 
if(count($address_lists) >= '1')
{
  if(in_array('addrver', explode(',',$component_check))) 
  { 
    if($address_details_component_check == "1") 
    {
    
  ?>
    
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='add_add_frm_current' data-url="<?= CLIENT_SITE_URL.'candidate_mail/address_edit/'.$cand_id.'/current'  ?>" ><i class="fa fa-minus"></i> Add Current Address </button>
  <?php
    }
    else 
    {

    ?>
     <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='add_add_frm_current' data-url="<?= CLIENT_SITE_URL.'candidate_mail/address_add/'.$cand_id.'/current'  ?>" ><i class="fa fa-plus"></i> Add Current Address </button>
    <?php
    }
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='add_add_frm_current' data-url="<?= CLIENT_SITE_URL.'candidate_mail/address_add/'.$cand_id.'/current'  ?>" ><i class="fa fa-plus"></i> Add Current Address </button>
<?php
}
?>


<div class="clearfix"></div>

<br>

  <div class="add_add_frm_current"></div>
  <br>
  <button class="btn btn-sm btn-info append_candidate_tab permanent_address" style="float: left;"  data-tab_name='add_add_frm_permanent' data-url="<?= CLIENT_SITE_URL.'candidate_mail/address_add/'.$cand_id.'/permanent'  ?>" ><i class="fa fa-plus"></i> Add Permanent Address </button>
  <br>
  <div class="add_add_frm_permanent"></div>


<script>
$(function ()  {
 
 var add_otable = $('#tbl_datatable_add').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "scrollX": true,
      "ordering": true,
      "autoWidth": false,
      "bInfo": false,
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

  $('#tbl_datatable_add tbody').on( 'dblclick', 'tr', function () {

    var row_data = add_otable.row( this ).data();

    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'Candidate_mail/address_view_form_inside/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.add_add_frm').html(html);
            }
        });
    }
  });

});
</script>

<!--<script type="text/javascript">
$(document).ready(function() {
    $(".append_candidate_tab").click(function() {
      $('html, body').animate({
        scrollTop: $(".add_add_frm").offset().top
      }, 2000);
   });
 });

</script>-->