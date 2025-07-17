<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Map_creation extends CI_Controller {
	
	function __construct() {
        parent::__construct();

        $this->load->model('addressver_model');
    }

    public function index($address_id = false)
    {

        if($address_id != "")
        {

            $file_upload_path = SITE_BASE_PATH . ADDRESS .'address_verification/capture_'.$address_id.'_map.png';
            $map_url = SITE_URL.'home/capture/'.$address_id;

            $this->ScreenShotUrl($map_url,$file_upload_path);
    
        }
       
    }

    public function captures($cap_id) {
        if($cap_id != "") {

            //$cap_id = decrypt($cap_id);
            $this->load->model('addressver_model');
            
            $details = $this->addressver_model->get_report_details(array('addrver.id' =>$cap_id,'address_details.status' =>1));

            if(!empty($details))
            {   
                $details = $details[0];
               
                $data['header_title'] = $details['CandidateName'].' Profile';
                $data['details'] = $details;

               $this->load->view('capture',$data);
            }
        }
    }
    
    public function capture($cap_id) {
        if($cap_id != "") {
            
            $details = $this->addressver_model->get_report_details(array('addrver.id' =>$cap_id,'address_details.status' =>1));

            if(!empty($details))
            {   
                $details = $details[0];
               
                $data['header_title'] = $details['CandidateName'].' Profile';
                $data['details'] = $details;
               
                $this->load->view('capture',$data);
            }
        }
    }

    public function capture_screenshot($map_url,$file_upload_path)
    {

        $url = 'http://PhantomJScloud.com/api/browser/v2/a-demo-key-with-low-quota-per-ip-address/';
        $payload = file_get_contents ( SITE_BASE_PATH.'request.json' );
        $url_update = json_decode($payload,true);
       
        $url_update['pages'][0]['url'] = $map_url;
        $url_update = json_encode($url_update);
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => $url_update
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context); 
        if ($result === FALSE) { /* Handle error */ }
        $obj = json_decode( $result );
        $base64Enc = $obj->{"content"}->{"data"};
        $this->Phan_base64_to_jpeg( $base64Enc, $file_upload_path);
    }

    protected function Phan_base64_to_jpeg( $base64_string, $output_file ) {
        $ifp = fopen( $output_file, "wb" ); 
        fwrite( $ifp, base64_decode( $base64_string) ); 
        fclose( $ifp ); 
        return( $output_file ); 
    }

    protected function ScreenShotUrl($url,$file_upload_path){
    $link = $url ;
    

    // Google API key 
    $googleApiKey = 'AIzaSyA6Z3FJPkKdFJYSMsfT7OGuwHIn_BylwLw'; 

    $api ="YOUR_API";
    $site =$_GET['site'];
    $adress="https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$link&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$googleApiKey";
    $curl_init = curl_init($adress);
    curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);
    
    $response = curl_exec($curl_init);
    curl_close($curl_init);
    
    $googledata = json_decode($response,true);
   
    $screenshot_data = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"]["screenshot"]['data'];
    //$snap =$snapdata["screenshot"]['data'];
   
    list($type, $screenshot_data) = explode(';', $screenshot_data); 
    list(, $screenshot_data)      = explode(',', $screenshot_data);
   

    //echo '<img src="'.$snap.'" />'; exit;
    $this->Phan_base64_to_jpeg($screenshot_data,$file_upload_path);
   // $snap =$snapdata["screenshot"];
     /* echo '<img src="'.$snap.'" />'; exit;

    $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$link&screenshot=true&key=$googleApiKey");

    $googlePagespeedData = json_decode($googlePagespeedData, true);

     $screenshot = $googlePagespeedData['lighthouseResult']['audits']['final-screenshot']['details']['data']; 
    
     echo '<img src="'.$screenshot.'" width="800" />'; exit;
           
          
             
            // Save screenshot as an image 
            $screenshot_data = $screenshot; 
            list($type, $screenshot_data) = explode(';', $screenshot_data); 
            list(, $screenshot_data)      = explode(',', $screenshot_data); */
            //print_r($screenshot_data);
           // $screenshot_data = base64_decode($screenshot_data); 


    /*$screenshot = base64_decode($googlePagespeedData['screenshot']['data']);

    $data = str_replace('_','/',$googlePagespeedData['screenshot']['data']);
    $data = str_replace('-','+',$data);
    $decoded = base64_decode($data);*/
    //file_put_contents($file_upload_path,$screenshot_data);
    #$this->Phan_base64_to_jpeg($screenshot_data,$file_upload_path);
    /* $curl_init = curl_init($googlePagespeedData);
    curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);
    
    $response = curl_exec($curl_init);
    curl_close($curl_init);
    
    $googledata = json_decode($response,true);
    print_r($googledata );exit();
    $snapdata = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"];
    $snap =$snapdata["screenshot"];
file_put_contents($file_upload_path,$snap);*/
    }

}
?>