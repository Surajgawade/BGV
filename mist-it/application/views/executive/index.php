<body>
    <div class="wrapper-page">
        <div class="card">

            <div class="card-body">
                 <a  href="<?=VENDOR_EXECUTIVE_SITE_URL?>logout?id=<?=$this->vendor_executive_info['id']?>"  style="float: right;" ><i class='fas fa-power-off fa-fw' style='font-size:36px;color:red'></i><br>Logout<a>
                <h3 class="text-center m-0">
                    
                    <a href="#" class="logo logo-admin">
                        
                      <img src="<?php echo SITE_IMAGES_URL; ?>logo.png" height="60" alt="logo">
                    </a>
                    
                </h3>

    <div class="p-5">

       <div class="row">
          <div class="col-xl-12 col-md-12">
            <a href="<?=VENDOR_EXECUTIVE_SITE_URL?>addrver_exe/addrver_exe_current">
                <div class="card mini-stat bg-primary" style="height:100px;">
                  <div class="card-body mini-stat-img">
                    <div class="mini-stat-icon">
                      <i class="mdi mdi-cube-outline float-right"></i>
                    </div>
                    <div class="text-white">
                      <h6 class="text-uppercase mb-3">New Cases</h6>
                      <h4 class="mb-4"><div class="numbers" id="current">0</div></h4>
                    </div>
                  </div>
                </div>
            </a>
          </div>
        </div>
                   
        <div class="row">
          <div class="col-xl-12 col-md-12">
            <a href="<?=VENDOR_EXECUTIVE_SITE_URL?>addrver_exe/addrver_exe_wip">
                <div class="card mini-stat bg-primary" style="height:100px;">
                  <div class="card-body mini-stat-img">
                    <div class="mini-stat-icon">
                      <i class="mdi mdi-cube-outline float-right"></i>
                    </div>
                    <div class="text-white">
                      <h6 class="text-uppercase mb-3">WIP</h6>
                      <h4 class="mb-4"><div class="numbers" id="wip">0</div></h4>
                    </div>
                  </div>
                </div>
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-12 col-md-12">
            <a href="<?=VENDOR_EXECUTIVE_SITE_URL?>addrver_exe/addrver_insufficiency">
                <div class="card mini-stat bg-primary" style="height:100px;">
                  <div class="card-body mini-stat-img">
                    <div class="mini-stat-icon">
                      <i class="mdi mdi-cube-outline float-right"></i>
                    </div>
                    <div class="text-white">
                      <h6 class="text-uppercase mb-3">Insufficiency</h6>
                      <h4 class="mb-4"><div class="numbers" id="insufficiency">0</div></h4>
                    </div>
                  </div>
                </div>
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-12 col-md-12">
            <a href="<?=VENDOR_EXECUTIVE_SITE_URL?>addrver_exe/addrver_closed">
                <div class="card mini-stat bg-primary"  style="height:100px;">
                  <div class="card-body mini-stat-img">
                    <div class="mini-stat-icon">
                      <i class="mdi mdi-cube-outline float-right"></i>
                    </div>
                    <div class="text-white">
                      <h6 class="text-uppercase mb-3">Closed</h6>
                      <h4 class="mb-4"><div class="numbers" id="closed">0</div></h4>
                    </div>
                  </div>
                </div>
            </a>
          </div>
        </div>
    </div>
  </div>
   </div>

    </div>

</body>


<script>
$(document).ready(function(){


  $.ajax({
    type:'POST',
    url: '<?php echo VENDOR_EXECUTIVE_SITE_URL."addrver_exe/status_count_executive"; ?>',
    data : '',      
    dataType: 'json',
    beforeSend :function(){
    $('#wip,#insufficiency,#closed,#current').html("0");
    },
    complete:function(){
    },
    success:function(responce)
    {

      if(responce.status == <?php echo SUCCESS_CODE; ?>)
      {   
          var type = responce.message;
          
          if(typeof(type) != "undefined")
          { 
              $("#current").html(type.address_current);
              $("#wip").html(type.address_wip);
              $("#insufficiency").html(type.address_insufficiency);
              $("#closed").html(type.address_closed);

          }
          return true;
      }
      else
      {
        $('#current,#wip,#insufficiency,#closed').html("0");
        return false;
      }
    }
  });


});


</script>