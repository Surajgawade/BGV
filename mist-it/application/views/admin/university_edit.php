<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">University Details</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>universities">University</a></li>
                  <li class="breadcrumb-item active">University Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                  <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>universities"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>


     <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <div style="float: right;">
                <button type="button" class="btn btn-secondary waves-effect btn-sm edit_btn_click" data-frm_name='frm_update_univer' data-editUrl='<?= ($this->permission['access_education_universities_edit']) ? encrypt($univers_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button>
                <button type="button" class="btn btn-secondary waves-effect btn-sm deleteURL" data-accessUrl="<?= ($this->permission['access_education_universities_delete']) ? ADMIN_SITE_URL.'universities/delete/'.encrypt($univers_details['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button>
              </div>
            </div>
            <?php echo form_open_multipart('#', array('name'=>'frm_update_univer','id'=>'frm_update_univer')); ?>

              <div class="col-md-8 col-sm-12 col-xs-8 form-group">
                <label >University Name <span class="error"> *</span></label>
                <input type="hidden" name="id" value="<?php echo set_value('id',$univers_details['id']); ?>">
                <input type="text" name="universityname" id="universityname" value="<?php echo set_value('universityname',$univers_details['universityname']);?>" class="form-control ">
                <?php echo form_error('universityname'); ?>
              </div>
           
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label> Vendor Name </label>
                <?php
                 echo form_dropdown('vendor_name', $vendor_list, set_value('vendor_name',$univers_details['vendor_id']),'class="select2" id="vendor_name"');
                 ?>
                <?php echo form_error('vendor_name'); ?>
              </div>
          
               <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label> Year of Passing </label>
                <input type="text" name="yop" id="yop" value="<?php echo set_value('yop',$univers_details['year_of_passing']);?>" class="form-control ">
                <?php echo form_error('year_of_passing'); ?>
              </div>

              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label> URL Link</label>
                <input type="text" name="url_link" id="url_link" value="<?php echo set_value('url_link',$univers_details['url_link']);?>" class="form-control ">
                <?php echo form_error('url_link'); ?>
              </div>

               <div class="col-sm-4 form-group">
                <label>Status </label>
                <?php
                  echo form_dropdown("status", array('1' => 'Active','2' => 'Inactive'), set_value('status',$univers_details['status']), 'class="custom-select" id="status"');
                  echo form_error('status');
                ?>
              </div>
              <div class="col-sm-4 form-group">
                <label>University Image</label>
                <input type="file" name="university_image[]" id="university_image" multiple="multiple" class="form-control" accept=".png, .jpg, .jpeg">
              </div>

              <div class="row">
                <div class="col-sm-6 form-group">
                  <ol>
                  <?php 
                  foreach ($university_attachments as $key => $value) {
                    //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
                       $url  = SITE_URL.UNIVERSITY_PIC;
                   
                      ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  </li> <?php
                    
                  }
                  ?>
                  </ol>
                </div>
              </div>
                   
              <div class="clearfix"></div>
              <div class="box-body">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>  
<script>

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
$(document).ready(function(){
  
  $('input,textarea,select').prop('disabled', true);

  $('#frm_update_univer').validate({
    rules : { 
      universityname : {
        required : true
      },
      id : {
        required : true
      }
    },
    messages: {
      universityname : {
        required : "Enter University"
      },
      id : {
        required :"Id required"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'universities/update_university'; ?>',
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
          //$('#btn_update').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
          }else {
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

});
</script>