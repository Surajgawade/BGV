<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Welcome extends CI_Controller
{

    public function cv()
    {
        $this->load->view('csv');
    }

    public function country()
    {
        // header("Content-Disposition: attachment; filename=data.csv");
        // header("Pragma: no-cache");
        // header("Expires: 0");
        $handle = fopen(SITE_BASE_PATH . "uploads/country_ips.csv", "r");
        $x = 1;
        $this->load->model('common_model');
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {

            $country_ips = array();
            //$country_ips[] = $x;

            $name = ($data[0] != '') ? $data[0] : '';
            $group = ($data[1] != '') ? $data[1] : '';

            $country_ips[] = ($data[3] != '') ? $data[3] : '';
            $country_ips[] = ($data[4] != '') ? $data[4] : '';
            $country_ips[] = ($data[5] != '') ? $data[5] : '';
            $country_ips[] = ($data[6] != '') ? $data[6] : '';
            $country_ips[] = ($data[7] != '') ? $data[7] : '';
            $country_ips[] = ($data[8] != '') ? $data[8] : '';
            $country_ips[] = ($data[9] != '') ? $data[9] : '';
            $country_ips[] = ($data[10] != '') ? $data[10] : '';
            $country_ips[] = ($data[11] != '') ? $data[11] : '';
            $country_ips[] = ($data[12] != '') ? $data[12] : '';
            $country_ips[] = ($data[13] != '') ? $data[13] : '';
            $country_ips[] = ($data[14] != '') ? $data[14] : '';
            $country_ips[] = ($data[15] != '') ? $data[15] : '';
            $country_ips[] = ($data[16] != '') ? $data[16] : '';
            $country_ips[] = ($data[17] != '') ? $data[17] : '';
            $country_ips[] = ($data[18] != '') ? $data[18] : '';
            $country_ips[] = ($data[19] != '') ? $data[19] : '';
            $country_ips[] = ($data[20] != '') ? $data[20] : '';

            $str = array('id' => $x, 'country_name' => $name, 'country_group' => $group, 'country_ips' => implode(",", array_filter($country_ips)));
           
            $x++;
        }
        //show($str);
        fclose($handle);
        //$this->db->insert_batch('country_ip',$str);
    }

    public function read()
    {
        $path = SITE_URL . CANDIDATES_BULK_FILES . '86365.pdf';

        $fp_pdf = fopen($path, 'rb');

        $img = new imagick(0); // [0] can be used to set page number
        $img->setResolution(300, 300);
        $img->readImageFile($fp_pdf);
        $img->setImageFormat("jpg");
        $img->setImageCompression(imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality(90);

        $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

        $data = $img->getImageBlob();

        $fp_pdf = '';
    }

    public function calla()
    {

        $holiday_date_array = array('2019-03-21', '2019-03-20');

        $date_required = "2019-03-07";

        $as = getWorkingDays($date_required, $holiday_date_array, 11);
        show($as);

    }

    public function dn()
    {

        $pdfAbsolutePath = SITE_BASE_PATH . UPLOAD_FOLDER . CANDIDATES;

        $im = new imagick($pdfAbsolutePath . "86365.pdf");
        $im->setImageFormat('jpg');
        header('Content-Type: image/jpeg');
        echo $im;
        die;
        $im = new Imagick();
        $im->setResolution(300, 300);
        $im->readImage($pdfAbsolutePath . "86365.pdf");

        $im = new imagick($pdfAbsolutePath);

        $noOfPagesInPDF = $im->getNumberImages();

        if ($noOfPagesInPDF) {

            for ($i = 0; $i < $noOfPagesInPDF; $i++) {

                $url = $pdfAbsolutePath . '[' . $i . ']';

                $image = new Imagick($url);

                $image->setImageFormat("jpg");

                $image->writeImage($pdfAbsolutePath . "/" . ($i + 1) . '-' . rand() . '.jpg');
            }
            echo "All pages of PDF is converted to images";

        }
        echo "PDF doesn't have any pages";
    }

    public function dns()
    {
        $link = '';
        $handle = fopen(SITE_BASE_PATH . 'uploads/empverres_files.csv', "r");

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link . $data[1]);
            $fp = fopen(SITE_BASE_PATH . 'e40/' . $data[1], 'w');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        }
    }

    public function mail()
    {
        $period = new DatePeriod(
            new DateTime('2016-1-1'),
            new DateInterval('P1D'),
            new DateTime('2020-12-31')
        );

        foreach ($period as $key => $value) {
            if (date('w', strtotime($value->format('Y-m-d'))) == 6) {
                $satur[] = array('holiday_date' => $value->format('Y-m-d'), 'remark' => 'saturday', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => 1, 'status' => 0);
            }

            if (date('w', strtotime($value->format('Y-m-d'))) == 0) {
                $satur[] = array('holiday_date' => $value->format('Y-m-d'), 'remark' => 'sunday', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => 1, 'status' => 0);
            }

        }
        $this->db->insert_batch('holiday_dates', $satur);
    }

    public function index()
    {
        redirect('admin/login');

    }

    public function im()
    {
        $ai = 1;
        $aiu = 2;
        $handle = fopen(SITE_BASE_PATH . "uploads/hospcash.csv", "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {

            $ciuser[] = array('user_name' => strtolower($data[0]), 'email_id' => '', 'password' => '$2y$12$DNOqky57DKufHiVCrofh8esmEdZBHjpyMoIWbjddp5ROJVERoq6Ue', 'status' => '1', 'role' => 2, 'created_at' => '2019-02-28 15:28:05');
            $insurer = array();

            $insurer[] = ($data[13] != '') ? $data[13] : '';
            $insurer[] = ($data[14] != '') ? $data[14] : '';
            $insurer[] = ($data[15] != '') ? $data[15] : '';
            $insurer[] = ($data[16] != '') ? $data[16] : '';
            $insurer[] = ($data[17] != '') ? $data[17] : '';
            $insurer[] = ($data[18] != '') ? $data[18] : '';
            $insurer[] = ($data[19] != '') ? $data[19] : '';
            $insurer[] = ($data[20] != '') ? $data[20] : '';
            $insurer[] = ($data[21] != '') ? $data[21] : '';
            $insurer[] = ($data[22] != '') ? $data[22] : '';
            $insurer[] = ($data[23] != '') ? $data[23] : '';
            $insurer[] = ($data[24] != '') ? $data[24] : '';
            $insurer[] = ($data[25] != '') ? $data[25] : '';

            $str = implode(',', array_filter($insurer));

            $hospital[] = array('user_id' => $aiu, 'name' => strtolower(htmlentities($data[1])), 'full_address' => strtolower($data[2]), 'area' => strtolower($data[3]), 'building_no' => strtolower($data[4]), 'road_num' => strtolower($data[5]), 'landmark' => strtolower($data[6]), 'pincode' => $data[7], 'city' => strtolower($data[8]), 'state' => strtolower($data[9]), 'reception' => strtolower($data[10]), 'latitude' => $data[11], 'longitude' => $data[12], 'insurers' => $str);
            $ai++;
            $aiu++;
        }
        show($ciuser);
        show($hospital);

        fclose($file);
    }

    public function tes()
    {
        $data['header_title'] = "Candidates List";

        $this->load->view('admin/header', $data);

        $this->load->view('test.php');

        $this->load->view('admin/footer');

    }

    public function save_new_role()
    {
        $frm = $this->input->post();
        show($frm);
    }
    //return $this->db->get_where("permissions", array("id" => $id, "deleted_at" => null))->row(0);

    // public function im()
    // {
    //     ini_set('memory_limit', '-1');

    //     $geojson_data = file_get_contents(SITE_IMAGES_URL.'20.geojson');

    //     $new = explode('coordinates',$geojson_data);

    //     foreach ($new as $key => $value)
    //     {
    //         //$as[] =  $this->db->insert('testing', array('va' => $value));
    //     }
    //     print_r($as);
    // }

    // public function imf()
    // {
    //     ini_set('memory_limit', '-1');

    //     $geojson_data = file_get_contents(SITE_IMAGES_URL.'all_Station.geojson');

    //     $new = explode('OBJECTID',$geojson_data);

    //     $file_upload_path = SITE_BASE_PATH.'files/';

    //     foreach ($new as $key => $value)
    //     {
    //         if($key > 0)
    //         {
    //             $start_pos = strpos($value, 'Route_Name');

    //             $end_pos = strpos($value, 'Remark');

    //             $a = $start_pos+14;
    //             $end = $end_pos-$a;
    //             $end = $end-4;
    //             $filename = substr($value,$a,$end);

    //             $a = $end = 0;
    //             $openfile = $file_upload_path.$filename.".geojson";
    //             $opend = fopen($openfile, "w");
    //             fwrite($opend,$value);

    //             $this->db->insert('filename', array('name' => $filename));
    //         }
    //     }
    //     //print_r($as);
    // }

    // public function get_string_between($string, $start, $end)
    // {
    //     $string = ' ' . $string;
    //     $ini = strpos($string, $start);
    //     if ($ini == 0) return '';
    //     $ini += strlen($start);
    //     $len = strpos($string, $end, $ini) - $ini;
    //     return substr($string, $ini, $len);
    // }

    // public function get()
    // {
    //     $file_upload_path = SITE_BASE_PATH.'files/';
    //     $this->db->select('name');

    //     $this->db->from('filename');

    //     $result  = $this->db->get();

    //     $result_array = $result->result_array();

    //     foreach ($result_array as $key => $value)
    //     {
    //         $filenamed  = $file_upload_path.$value['name'].'.geojson';

    //         $fullstring = file_get_contents($filenamed);

    //         $parsed = $this->get_string_between($fullstring, ' [ [ ', ' ] ] }');

    //         $replacwe = str_replace("[ ","(",$parsed);
    //         $replacwe = str_replace(" ]",")",$replacwe);

    //         file_put_contents($filenamed,$replacwe);

    //     }
    // }

    // public function read()
    // {
    //     $dir = SITE_BASE_PATH.'files/';

    //     // Open a directory, and read its contents
    //     if (is_dir($dir)){
    //       if ($dh = opendir($dir)){
    //           $cunter  = 103;
    //           $geojson_data = '';
    //         while (($file = readdir($dh)) !== false){
    //           if(file_exists($dir.$file) && strlen($file) > 4 )
    //           {
    //               $boundary = file_get_contents($dir.$file);
    //               if (strpos($boundary, 'Route_Name') !== false) {

    //                   echo $sql = "insert into train_route_boundaries values(".$cunter.",'".$file."',".$boundary.",'".$geojson_data."')";
    //               }
    //               die;
    //             //$res = pg_query($con,$sql);
    //             $cunter++;
    //           }
    //         }
    //         closedir($dh);
    //       }
    //     }
    // }

    public function body()
    {
        $this->load->view('admin/login');
    }

    public function test()
    {
        $this->load->view('admin/test');
    }

    public function data()
    {
        show($this->input->post());
    }
}
