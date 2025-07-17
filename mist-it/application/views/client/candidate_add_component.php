<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"> 
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, 
     user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="icon" href="<?php echo SITE_IMAGES_URL; ?>apple-touch-icon.png" type="image/ico" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php echo isset($header_title) ? ucwords(strtolower($header_title)) : CRMNAME ?></title>
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>dataTables.checkboxes.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>metismenu.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>icons.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>style.css">
   <link rel="icon" href="<?php echo SITE_IMAGES_URL;?>favicon.ico" type="image/ico" />

    <link href="<?php echo SITE_CSS_URL; ?>daterangepicker.css" rel="stylesheet">
    <link href="<?php echo SITE_CSS_URL; ?>datepicker3.css" rel="stylesheet">
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />


<style type="text/css">
  
 @media screen and (max-width : 760px){
 .dropdown-content li{
        display: inline-block;
        font-size: 1em;
        margin:  0;
        width: 100%;
    }
    .dropdown-content{
        display: block;
        margin: 0;
        text-align: right;
    }   
}

#pending_submit_check {
 /* font-family: Arial, Helvetica, sans-serif;*/
  border-collapse: collapse;
  width: 100%;
}

#pending_submit_check td, #pending_submit_check th {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

</style>
</head>
<body> 

  
    <div class="content-page">
      <div class="content">
        <div class="container-fluid">
       <?php  if($company_logo != ''){ ?>
        <div style="display: flex;">
      
        <div style="margin-left: 25px">
            <span>
                 <img src="<?php echo COMPANYLOGO ?>" alt="" height="50" style="background: white;">
            </span>
              
        </div>
        <div style="margin-left: 500px"> 
              <span>
                    <img src="<?php echo SITE_URL . CLIENT_LOGO .'/'.$company_logo ?>" alt="" height="70">
              </span>
        </div>
     </div>
      <?php }else{  ?>

        <div style="text-align: center;">
            <a href="<?php echo COMPANYLOGO ?>" class="logo">
                <span>
                    <img src="<?php echo COMPANYLOGO ?>" alt="" height="50" style="background: white;">
                </span>
            </a>
        </div>

      <?php } ?>

        <br>      
          <div class="row">
            <div class="col-12">
              <div class="card m-b-20">
                <div class="card-body">
                  <div class="text-white div-header">
                    Candidate Details  
                  </div>
                   <br>
                   <div style="float: right;">
                       <button class="btn btn-secondary waves-effect  edit_btn_click" data-frm_name='update_candidates' data-editUrl="<?= $candidate_details['cands_info_id'] ?>"><i class="fa fa-edit"></i> Edit</button>
                  </div>
                <?php echo form_open('#', array('name'=>'update_candidates','id'=>'update_candidates')); ?> 
                 
                <div class="clearfix"></div>
                  <input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['cands_info_id']); ?>" class="form-control">

                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label>Candidate Name <span class="error"> *</span></label>
                      <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$candidate_details['CandidateName']); ?>" class="form-control" readonly>
                      <?php echo form_error('CandidateName'); ?>
                    </div>
                
                    <div class="col-sm-4 form-group">
                      <label>Date of Birth <span class="error"> *</span></label>
                      <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details['DateofBirth'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
                      <?php echo form_error('DateofBirth'); ?>
                    </div>

                    <div class="col-sm-4 form-group">
                      <label>Father's Name <span class="error"> *</span></label>
                      <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$candidate_details['NameofCandidateFather']); ?>" class="form-control cls_disabled">
                      <?php echo form_error('NameofCandidateFather'); ?>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-4 form-group">
                        <label>Gender<span class="error"> *</span></label>
                       <?php
                          echo form_dropdown('gender', GENDER, set_value('gender',$candidate_details['gender']), 'class="custom-select cls_disabled" id="gender"');
                          echo form_error('gender');
                        ?>
                    </div>

                    <div class="col-sm-4 form-group">
                      <label>Primary Contact<span class="error"> *</span></label>
                      <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$candidate_details['CandidatesContactNumber']); ?>" class="form-control" readonly>
                      <?php echo form_error('CandidatesContactNumber'); ?>
                    </div>

                    <div class="col-sm-4 form-group">
                      <label>Alternative Contact No</label>
                      <input type="text" name="ContactNo1" id="ContactNo1" maxlength="12" value="<?php echo set_value('ContactNo1',$candidate_details['ContactNo1']); ?>" class="form-control cls_disabled">
                      <?php echo form_error('ContactNo1'); ?>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
                    </div>
                  </div>
                 <?php echo form_close(); ?>

                </div>
              </div>
            </div>
          </div>

          <?php
                        $components = json_decode($client_components['component_name'],true);
                     
                        $selected_component = explode(',', $client_components['component_id']);
                       
                        $i = 0; 
                        foreach ($components as $key => $component) 
                        {
                         
                          ?>
                            <div class="row">
                              <div class="col-12">
                                <div class="card m-b-20">
                                  <div class="card-body">
                                    <div class="text-white div-header">
                                        <?php  echo  $component ?> Case Details  <button class="btn btn-sm btn-warning" type="button" id="<?php echo $key; ?>" style="float: right;" >Load  <?php  echo  $component ?></button>  
                                    </div> 
                                    <br>
                                    <div id = '<?php echo $key; ?>_1'></div>
                                  </div> 
                                </div>  
                              </div> 
                            </div>   

                        <?php 
                        }
                        ?>

                        

       <!-- <div class="row">
            <div class="col-12">
              <div class="card m-b-20">
                <div class="card-body">
                  <div class="text-white div-header">
                      Address Case Details     
                  </div>
                  <div class="clearfix"></div>
                  <br>

                  <div id="block_container">   
                  <table class="table-content"  border="1">
                    <thead>
                      <td style="border-spacing: 10;padding : 10px; text-align: center;" colspan="2">Current Address</td>
                     
                    </thead>
                    <thead>
                      <td style="border-spacing: 10;padding : 10px;">Fields</td>
                      <td style="border-spacing: 10;padding : 10px;">Input</td>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;"> Address type </td>
                        <td style="border-spacing: 10;padding : 10px;">
                          <?php
                            echo form_dropdown('current_address_type', ADDRESS_TYPE, set_value('current_address_type'), 'class="custom-select" id="current_address_type"');
                            echo form_error('current_address_type'); 
                          ?>    
                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Stay From (MM/YYYY)</td>
                        <td style="border-spacing: 10;padding : 10px;">

                          <input type="text" name="current_stay_from" id="current_stay_from" value="<?php echo set_value('current_stay_from'); ?>" class="form-control"> 

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Stay To (MM/YYYY)</td>
                        <td style="border-spacing: 10;padding : 10px;">

                          <input type="text" name="current_stay_to" id="current_stay_to" value="<?php echo set_value('current_stay_to'); ?>" class="form-control"> 

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Street Address</td>
                        <td style="border-spacing: 10;padding : 10px;">

                         <textarea name="current_address" rows="1" id="current_address" class="form-control"><?php echo set_value('current_address'); ?></textarea>

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">City</td>
                        <td style="border-spacing: 10;padding : 10px;">

                        <input type="text" name="current_city" id="current_city" value="<?php echo set_value('current_city'); ?>" class="form-control">

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Pincode</td>
                        <td style="border-spacing: 10;padding : 10px;">

                        <input type="text" name="current_pincode" maxlength="6" id="current_pincode" value="<?php echo set_value('current_pincode'); ?>" class="form-control">

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">State</td>
                        <td style="border-spacing: 10;padding : 10px;">

                        <?php
                          echo form_dropdown('current_state', $states, set_value('current_state'), 'class="custom-select" id="current_state"');
                          echo form_error('state');
                        ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Attachment</td>
                        <td style="border-spacing: 10;padding : 10px;">

                          <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;" colspan="2">

                          <input type="submit" name="btn_address" id='btn_address' value="Submit" class="btn btn-success">
                          <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                      <input type="checkbox" name="TT_sticky_header" id="TT_sticky_header_function" value="{TT_sticky_header}" onchange="stickyheaddsadaer(this)"/>
                      <em> Check this box if current address and permanent address same</em>
                  </div> 
                  
                  <table class="table-content"  border="1">
                    <thead>
                      <td style="border-spacing: 10;padding : 10px; text-align: center;" colspan="2">Permanant Address</td>
                     
                    </thead>
                    <thead>
                      <td style="border-spacing: 10;padding : 10px;">Fields</td>
                      <td style="border-spacing: 10;padding : 10px;">Input</td>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;"> Address type </td>
                        <td style="border-spacing: 10;padding : 10px;">
                          <?php
                            echo form_dropdown('permanent_address_type', ADDRESS_TYPE, set_value('permanent_address_type'), 'class="custom-select" id="permanent_address_type"');
                            echo form_error('permanent_address_type'); 
                          ?>    
                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Stay From (MM/YYYY)</td>
                        <td style="border-spacing: 10;padding : 10px;">

                          <input type="text" name="permanent_stay_from" id="permanent_stay_from" value="<?php echo set_value('permanent_stay_from'); ?>" class="form-control"> 

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Stay To (MM/YYYY)</td>
                        <td style="border-spacing: 10;padding : 10px;">

                          <input type="text" name="permanent_stay_to" id="permanent_stay_to" value="<?php echo set_value('permanent_stay_to'); ?>" class="form-control"> 

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Street Address</td>
                        <td style="border-spacing: 10;padding : 10px;">

                         <textarea name="permanent_address" rows="1" id="permanent_address" class="form-control"><?php echo set_value('permanent_address'); ?></textarea>

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">City</td>
                        <td style="border-spacing: 10;padding : 10px;">

                        <input type="text" name="permanent_city" id="permanent_city" value="<?php echo set_value('permanent_city'); ?>" class="form-control">

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Pincode</td>
                        <td style="border-spacing: 10;padding : 10px;">

                        <input type="text" name="permanent_pincode" maxlength="6" id="permanent_pincode" value="<?php echo set_value('permanent_pincode'); ?>" class="form-control">

                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">State</td>
                        <td style="border-spacing: 10;padding : 10px;">

                        <?php
                          echo form_dropdown('permanent_state', $states, set_value('permanent_state'), 'class="custom-select" id="permanent_state"');
                          echo form_error('permanent_state');
                        ?>
                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;">Attachment</td>
                        <td style="border-spacing: 10;padding : 10px;">

                          <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                        </td>
                      </tr>
                      <tr>
                        <td style="border-spacing: 10;padding : 10px;" colspan="2">

                          <input type="submit" name="btn_address" id='btn_address' value="Submit" class="btn btn-success">
                          <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                 
                   
                    
                
                

                </div>
              </div>
            </div>
          </div>-->
 
        </div>
      </div>
    </div>
  
   <div id= "addrver"> </div>

  

<script src="<?php echo SITE_JS_URL;?>jquery.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>metisMenu.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>jquery.slimscroll.js"></script>
<script src="<?php echo SITE_JS_URL;?>waves.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>notify.js"></script>
<script src="<?php echo SITE_JS_URL;?>jquery.validate.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>demo.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap-multiselect.js"></script>
<script src="<?php echo SITE_JS_URL; ?>daterangepicker.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap-datepicker.js"></script>

<!-- Required datatable js -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>dataTables.checkboxes.min.js"></script>
<!-- Buttons examples -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/jszip.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/pdfmake.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/vfs_fonts.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.html5.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.print.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/responsive.bootstrap4.min.js"></script>

<!-- Datatable init js -->
<script src="<?php echo  SITE_URL; ?>assets/pages/datatables.init.js"></script>
<script src="<?php echo  SITE_URL; ?>assets/plugins/select2/js/select2.min.js"></script>
    
<script src="<?php echo  SITE_JS_URL; ?>app.js"></script>

<script type="text/javascript">
$('.cls_disabled').prop('disabled', true);
$(".content-page").css({"margin-left":"0px"});
$('.addrver').click(function(){

}).trigger('click');



$('#update_candidates').validate({ 
    rules: {
     candidate_id : {
        required : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      NameofCandidateFather : {
        required : true,
        lettersonly : true
      },
      DateofBirth : {
        required : true
      },
      CandidatesContactNumber : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      gender : {
        required : true,
        greaterThan: 0
      },
      ContactNo1 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      }
     
    },
    messages: {
        candidate_id : {
          required : "Enter Candidate ID"
        },
        CandidateName : {
          required : "Enter Candidate Name"
        },
        NameofCandidateFather : {
          required : "Enter Name of Father",
          lettersonly : "Allowed Only letter"
        },
        DateofBirth : {
            required : "Enter Date of Birth"
        },
        gender : {
           required : "Select Gender"
        },
        CandidatesContactNumber : {
          required : "Please Enter Primary Contact"
        }
      },
      submitHandler: function(form) 
      { 
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'Candidate_mail/candidates_update'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_update').attr('disabled','disabled');
            
            },
            complete:function(){
            
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                location.reload();
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });    
      }
  });

</script>



<script type="text/javascript">
  

    $('#addrver').click(function(){
    $.ajax({
      type : 'POST',
      url : '<?php echo CLIENT_SITE_URL.'candidate_mail/ajax_tab_data/'.$candidate_details['cands_info_id'].'/'.'addrver'; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#addrver_1').html("Loading..");
      },
      success:function(message)
      {
   
         $('#addrver_1').html(message);
      }
    }); 
   
  });
  
  
</script>
</body>
</html>