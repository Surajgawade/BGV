<div class="content-page">
  <div class="content">
    <div class="container-fluid">

    
    <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Candidate</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>candidates/add_candiddate_view">Candidate</a></li>
                  <li class="breadcrumb-item active">Candidate Edit</li>
                  <li class="breadcrumb-item active"><?php echo $candidate_details['CandidateName']; ?></li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                   <li><button class="btn btn-secondary waves-effect btn_clicked btn_clicked" data-accessUrl="<?= CLIENT_SITE_URL?>candidates/add_candiddate_view"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>
   
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            <div class="nav-tabs-custom">
      
             <ul class="nav nav-pills nav-justified" role="tablist">
                <?php
                $components = json_decode($client_components['component_name'],true);
             
                $selected_component = explode(',', $client_components['component_id']);
               
                $active_tab = '';

                echo "<li class='nav-item waves-effect waves-light'><a class = 'nav-link active' href='#candidate_tab' data-toggle='tab' role='tab'>Candidate</a></li>";

                foreach ($components as $key => $component) 
                {

                    if(in_array($key, $selected_component))
                    {

                      $tabs_panels[] = $key;
                      $component  =   explode(' ',trim($component));
                      $component  =   explode('/',trim($component[0]));
                      echo "<li role='presentation' data-url='".CLIENT_SITE_URL."candidates/ajax_tab_data/' data-can_id=".$candidate_details['cands_info_id']." data-tab_name=".$key." class='view_component_tab nav-item waves-effect waves-light'><a class='nav-link' href='#".$key."' aria-controls='home' role='tab' data-toggle='tab'>  ".$component[0]."</a></li>";
                    }
                }
                
                ?>
              </ul>
              <div class="tab-content">
               <!--<div  class="container" style="width: 100%; border-style: ridge;">-->
                <?php
                  echo "<div class='active tab-pane' id='candidate_tab'>";
                  $this->load->view('client/candidates_edit_view');

                  ?>
    
               <?php  echo "</div><br>";
                  foreach ($tabs_panels as $key => $tabs_panel) 
                  { 
                      echo "<div id='".$tabs_panel."' class='tab-pane fade in'>";

                      echo "</div>";
                  }
                ?> 
              </div>
            </div>

           </div>
         </div>
       </div>
     </div>
    
    </div>
  </div>
</div>

