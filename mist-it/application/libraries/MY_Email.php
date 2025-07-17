<?php defined('BASEPATH') or exit('No direct script access allowed.');

class MY_Email extends CI_Email
{

    protected $CI;

    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->CI = &get_instance();
        $this->SMS_key = '373552756472617472616e733535381594897349';

    }

    public function admin_send_employment_mail($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data['detail_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'employment_template', $view_data, true);

        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to($email_tmpl_data['to_emails']);

        $this->CI->email->bcc(array(FROMEMAIL));

        $subject = 'Employment Verification of ' . htmlentities($email_tmpl_data['detail_info']['CandidateName']) . '-' . htmlentities($email_tmpl_data['detail_info']['cmp_ref_no']) . ' ';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if ($email_tmpl_data['detail_info']['file_names'] != '') {
            for ($i = 0; $i < count($files); $i++) {
                $this->CI->email->attach($path . $files[$i]);
            }
        }
        return $this->CI->email->send();
    }

    public function admin_send_mail_task($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data['detail_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'employment_template', $view_data, true);

        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to($email_tmpl_data['to_emails']);

        $this->CI->email->bcc(array(FROMEMAIL));

        $subject = 'Employment Verification of ' . htmlentities($email_tmpl_data['detail_info']['CandidateName']) . '-' . htmlentities($email_tmpl_data['detail_info']['cmp_ref_no']) . ' ';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if ($email_tmpl_data['detail_info']['file_names'] != '') {
            for ($i = 0; $i < count($files); $i++) {
                $this->CI->email->attach($path . $files[$i]);
            }
        }
        return $this->CI->email->send();
    }

    public function admin_send_employment_mail1($email_tmpl_data)
    {
        set_time_limit(0);

        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];
        $view_data['client_disclosure'] = $email_tmpl_data['client_disclosure'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'employment_template', $view_data, true);

        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);
        $bcc_emails = explode(",", $email_tmpl_data['bcc_emails']);
        $cc_bcc_emails = array_merge($cc_emails, $bcc_emails);
        $cc_bcc_emails = array_unique($cc_bcc_emails);

        $this->CI->email->cc(implode(', ', $cc_bcc_emails));

        //$this->CI->email->bcc(array(FROMEMAIL));

        $subject = 'Employment verification of ' . htmlentities(ucwords($email_tmpl_data['detail_info']['CandidateName'])) . ' - (' . htmlentities(strtoupper($email_tmpl_data['detail_info']['emp_com_ref'])) . ')';

        //$this->CI->email->subject(mb_encode_mimeheader($subject,"UTF-8"));

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = SITE_BASE_PATH . EMPLOYMENT . $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);
        
        if (isset($email_tmpl_data['attchment'])) {
            $attachment_array = $email_tmpl_data['attchment'];

        } else {
            $attachment_array = array();
        }

        if ($email_tmpl_data['detail_info']['file_names'] != '') {

            if(!empty($attachment_array))
            {
                for ($i = 0; $i < count($attachment_array); $i++) {

                    if (array_search($attachment_array[$i], $files));
                    {
                        $this->CI->email->attach($path . $attachment_array[$i]);
                    }
                }
            }

        }

        return $this->CI->email->send();
    }

    public function admin_send_reference_initial_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'reference_initial_template', $view_data, true);

        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);
        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);
        $bcc_emails = explode(",", $email_tmpl_data['bcc_emails']);
        $cc_bcc_emails = array_merge($cc_emails, $bcc_emails);

        $this->CI->email->cc(implode(', ', $cc_bcc_emails));

        $subject = 'Reference verification of ' . htmlentities($email_tmpl_data['detail_info']['CandidateName']) . '-' . htmlentities($email_tmpl_data['detail_info']['cmp_ref_no']) . ' ';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = SITE_BASE_PATH . REFERENCES . $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if (isset($email_tmpl_data['attchment'])) {
            $attachment_array = $email_tmpl_data['attchment'];

        } else {
            $attachment_array = array();
        }

        if ($email_tmpl_data['detail_info']['file_names'] != '') {

            if(!empty($attachment_array))
            {

                for ($i = 0; $i < count($attachment_array); $i++) {

                    if (array_search($attachment_array[$i], $files));
                    {

                        $this->CI->email->attach($path . $attachment_array[$i]);
                    }
                }

           }

        }

        return $this->CI->email->send();
    }

    public function admin_send_education_initial_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'education_intitial_mail_template', $view_data, true);

        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);
        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);
        $bcc_emails = explode(",", $email_tmpl_data['bcc_emails']);
        $cc_bcc_emails = array_merge($cc_emails, $bcc_emails);

        $this->CI->email->cc(implode(', ', $cc_bcc_emails));

        $subject = 'Academic verification of ' . htmlentities($email_tmpl_data['detail_info']['CandidateName']) . '-' . htmlentities($email_tmpl_data['detail_info']['cmp_ref_no']) . ' ';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

       $path = SITE_BASE_PATH . EDUCATION . $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if (isset($email_tmpl_data['attchment'])) {
            $attachment_array = $email_tmpl_data['attchment'];

        } else {
            $attachment_array = array();
        }

        if ($email_tmpl_data['detail_info']['file_names'] != '') {

            if(!empty($attachment_array))
            {

                for ($i = 0; $i < count($attachment_array); $i++) {

                    if (array_search($attachment_array[$i], $files));
                    {

                        $this->CI->email->attach($path . $attachment_array[$i]);
                    }
                }
            }

        }

        return $this->CI->email->send();
    }

    public function admin_forgot_password($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'admin_forgot_password', $view_data, true);

        //$this->CI->email->from(FROMEMAIL);
        $email_tmpl_data['subject'] = "Forgot Password";

        $subject = 'Forgot Password';

        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to($email_tmpl_data['email']);

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $this->CI->email->send();

        //$this->email->print_debugger(array('headers');
    }

    public function vendor_forgot_password($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'vendor_forgot_password', $view_data, true);

        //$this->CI->email->from(FROMEMAIL);
        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to($email_tmpl_data['email']);

        $this->CI->email->subject($email_tmpl_data['subject']);

        $this->CI->email->message($message);

        $this->CI->email->send();

    }

    public function client_forgot_password($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'client_forgot_password', $view_data, true);

        //$this->CI->email->from(FROMEMAIL);
        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to($email_tmpl_data['email']);

        $this->CI->email->subject($email_tmpl_data['subject']);

        $this->CI->email->message($message);

        $this->CI->email->send();

    }

    public function admin_send_employment_generci_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'generic_mail_template', $view_data, true);


        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);
        $bcc_emails = explode(",", $email_tmpl_data['bcc_emails']);
        $cc_bcc_emails = array_merge($cc_emails, $bcc_emails);

        $this->CI->email->cc(implode(', ', $cc_bcc_emails));

        $subject = 'Employment verification of ' . htmlentities(ucwords($email_tmpl_data['detail_info']['CandidateName'])) . ' - (' . htmlentities(strtoupper($email_tmpl_data['detail_info']['emp_com_ref'])) . ')';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        /*$path = SITE_BASE_PATH . EMPLOYMENT . $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if (isset($email_tmpl_data['attchment'])) {
            $attachment_array = $email_tmpl_data['attchment'];

        } else {
            $attachment_array = array();
        }

        if ($email_tmpl_data['detail_info']['file_names'] != '') {
            
			for ($i = 0; $i < count($attachment_array); $i++) {

                if (array_search($attachment_array[$i], $files)); {
                    $this->CI->email->attach($path . $attachment_array[$i]);
                }
            }

        }*/
        return $this->CI->email->send();
    }

    public function admin_send_employment_summary_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];
        $view_data['client_disclosure_summary'] =  $email_tmpl_data['client_disclosure_summary']; 

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'summary_mail_template', $view_data, true);

        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);
        $bcc_emails = explode(",", $email_tmpl_data['bcc_emails']);
        $cc_bcc_emails = array_merge($cc_emails, $bcc_emails);

        $this->CI->email->cc(implode(', ', $cc_bcc_emails));

        $subject = 'Employment verification summary of ' . htmlentities(ucwords($email_tmpl_data['detail_info']['CandidateName'])) . ' - (' . htmlentities(strtoupper($email_tmpl_data['detail_info']['emp_com_ref'])) . ')';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

       /* $path = SITE_BASE_PATH . EMPLOYMENT . $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if (isset($email_tmpl_data['attchment'])) {
            $attachment_array = $email_tmpl_data['attchment'];

        } else {
            $attachment_array = array();
        }

        if ($email_tmpl_data['detail_info']['file_names'] != '') {
            

            for ($i = 0; $i < count($attachment_array); $i++) {

                if (array_search($attachment_array[$i], $files));
                {
                    $this->CI->email->attach($path . $attachment_array[$i]);
                }
            }

        }*/

        return $this->CI->email->send();
    }

    public function admin_send_reference_summary_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'summary_mail_ref_template', $view_data, true);

        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);
        $bcc_emails = explode(",", $email_tmpl_data['bcc_emails']);
        $cc_bcc_emails = array_merge($cc_emails, $bcc_emails);

        $this->CI->email->cc(implode(', ', $cc_bcc_emails));

        $subject = 'Reference verification summary of ' . htmlentities($email_tmpl_data['detail_info']['CandidateName']) . '-' . htmlentities($email_tmpl_data['detail_info']['cmp_ref_no']) . ' ';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = SITE_BASE_PATH . REFERENCES . $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if (isset($email_tmpl_data['attchment'])) {
            $attachment_array = $email_tmpl_data['attchment'];

        } else {
            $attachment_array = array();
        }

        if ($email_tmpl_data['detail_info']['file_names'] != '') {
            /*for($i=0; $i< count($files); $i++)
            {
            $this->CI->email->attach($path.$files[$i]);
            }*/

            if(!empty($attachment_array))
            {

                for ($i = 0; $i < count($attachment_array); $i++) {

                    if (array_search($attachment_array[$i], $files));
                    {
                        $this->CI->email->attach($path . $attachment_array[$i]);
                    }
                }
            }

        }

        return $this->CI->email->send();
    }

    public function admin_send_reference_mail($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data['detail_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'reference_template', $view_data, true);

        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to($email_tmpl_data['to_emails']);

        $this->CI->email->bcc(array(FROMEMAIL, FROMEMAIL));

        $this->CI->email->subject('Reference verification of' . $email_tmpl_data['detail_info']['CandidateName']);

        $this->CI->email->message($message);

        return $this->CI->email->send();
    }

    public function insuff_raised_mail($details)
    {
        $view_data['email_info'] = $details;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'insuff_raised_template', $view_data, true);

        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to(FROMEMAIL);

        $this->CI->email->subject('Insuff Raise (Ref No ' . $details['ClientRefNumber'] . ')');

        $this->CI->email->message($message);

        $this->CI->email->send();
    }

    public function discrepancy_raised_mail($details)
    {
        $view_data['email_info'] = $details;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'discrepancy_raised_template', $view_data, true);

        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to(FROMEMAIL);

        $this->CI->email->subject('Discrepancy Raise (Ref No ' . $details['ClientRefNumber'] . ')');

        $this->CI->email->message($message);

        $this->CI->email->send();
    }

    public function add_new_task($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'add_task', $view_data, true);

        $this->CI->email->from($email_tmpl_data['email']);

        $this->CI->email->to($email_tmpl_data['email']);

        $this->CI->email->subject($email_tmpl_data['subject']);

        $this->CI->email->message($message);

        return $this->CI->email->send();

    }

    public function candidate_report_mail_send($email_tmpl_data)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];
        $view_data['component_details'] = $email_tmpl_data['component_details'];
      

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'candidates_report', $view_data, true);
  
        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);
       
        $this->CI->email->cc(implode(', ', $cc_emails));

  
        $subject = $email_tmpl_data['subject'];
       

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = SITE_BASE_PATH.CANDIDATES;

        
        if(isset($email_tmpl_data['attachments']))
        {
            if(!empty($email_tmpl_data['attachments']))
            {
               
               $attachment_array = $email_tmpl_data['attachments'];
            }
            else
            {
                
               $attachment_array = ''; 
            }

        }
        else
        {
            
            $attachment_array = '';
        }

  
        if(!empty($attachment_array));
        {
           
         $this->CI->email->attach($path.$attachment_array);

        }
       
        return $this->CI->email->send();

    }

    public function vendor_case_send_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $to_emails = $email_tmpl_data['to_emails'];

        $cc_emails = $email_tmpl_data['user_email_id'];
        $bcc_emails = $email_tmpl_data['reporting_email_id'];
        $message = $email_tmpl_data['message'];
        $subject = $email_tmpl_data['subject'];

    //$message .= $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME.'emailer_signature', $view_data, TRUE);

        $this->CI->email->to($to_emails);

        $this->CI->email->cc($cc_emails);

        $this->CI->email->bcc($bcc_emails);

        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

        $this->CI->email->attach($path . "/" . $email_tmpl_data['attchment']);

        return $this->CI->email->send();
    }

    public function client_send_mail($email_tmpl_data)
    {
        try {

            $view_data['email_info'] = $email_tmpl_data;
    
            $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'client_send_mail_to_candidate', $view_data, true);
            
            $this->CI->email->from($email_tmpl_data['from_email']);

            $this->CI->email->to($email_tmpl_data['to_emails']);

            $subject = ucwords($email_tmpl_data['cands_name']) . ' your background verification has been initiated for ' . ucwords($email_tmpl_data['clientname']) . ' ';

            $this->CI->email->cc($email_tmpl_data['cc_emails']);

            $this->CI->email->subject($subject);

            $this->CI->email->message($message);
           
            if($this->CI->email->send())
            {

                $this->CI->candidates_model->save(array('is_mail_sent' => STATUS_ACTIVE,'last_email_on' => date(DB_DATE_FORMAT),'cron_status' => STATUS_ACTIVE),array('id'=>$email_tmpl_data['candidate_id']));
                 
                $this->CI->candidates_model->save_task_manager('mail_sms_details',array('type' => 1,'candidate_id'=>$email_tmpl_data['cands_info_id'],'mail_sms_send' => date(DB_DATE_FORMAT)));
          
            }

            if(!empty($email_tmpl_data['CandidatesContactNumber']))
            {

                $sms_content = "Greetings from ".SMS_NAME.". We have partnered with your current employer ".ucwords($email_tmpl_data['clientname'])." to conduct your background verification. Request you to visit ".$email_tmpl_data['url']." and update the details at the earliest.";

                $result_json = $this->send_sms($email_tmpl_data['CandidatesContactNumber'],$sms_content);
             
                $result  = json_decode($result_json);

                if($result->status == "success")
                {  

                    $this->CI->candidates_model->save(array('is_sms_sent' => STATUS_ACTIVE,'last_sms_on' => date(DB_DATE_FORMAT),'cron_status' => STATUS_ACTIVE),array('id'=>$email_tmpl_data['candidate_id']));
                   
                    $this->CI->candidates_model->save_task_manager('mail_sms_details',array('type' => 2,'candidate_id'=>$email_tmpl_data['cands_info_id'],'mail_sms_send' => date(DB_DATE_FORMAT)));
                }
            }
         } catch (Exception $e) {
            log_message('error', 'MY_Email:: client_send_mail');
            log_message('error', $e->getMessage());
        }
    
    }

    public function client_send_mail_trigger($email_tmpl_data)
    {
        try {
         
            $view_data['email_info'] = $email_tmpl_data;
    
            $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'client_send_mail_to_candidate_trigger', $view_data, true);
      
            $this->CI->email->from($email_tmpl_data['from_email']);

            $this->CI->email->to($email_tmpl_data['to_emails']);

            $subject = 'Reminder Hi '. ucwords($email_tmpl_data['cands_name']) . ' - details for your background verification is still pending for ' . ucwords($email_tmpl_data['clientname']) . ' ';

            $this->CI->email->cc($email_tmpl_data['cc_emails']);

            $this->CI->email->subject($subject);

            $this->CI->email->message($message);
           
            if($this->CI->email->send())
            {

                $this->CI->candidates_model->save(array('last_email_on' => date(DB_DATE_FORMAT),'cron_status' => STATUS_ACTIVE),array('id'=>$email_tmpl_data['candidate_id']));

                $this->CI->candidates_model->save_task_manager('mail_sms_details',array('type' => 1,'candidate_id'=>$email_tmpl_data['cands_info_id'],'mail_sms_send' => date(DB_DATE_FORMAT)));

            }
           

        } catch (Exception $e) {
            log_message('error', 'MY_Email:: client_send_mail_trigger');
            log_message('error', $e->getMessage());
        }
    
    }

    protected function curlSMS($curl_url)
    {
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, $curl_url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $phoneList = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        return $phoneList;

    }

    protected function send_sms($numbers = '', $message) {
        log_message('error', '$message and mobile number');
        log_message('error', $message);
        $response = '';
        try {
            if(!empty($numbers) && $message) {
               
                $apiKey = urlencode('Nzc0MTZjMzI3MDMwMzk1NTY0NDc2ZTc2NTA2MzdhNmU=');
                $message = rawurlencode($message);
                $sender = urlencode(MASSAGESENDER);

                if(is_array($numbers))
                    $numbers = implode(',', $numbers);
            
                // Prepare data for POST request
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

                log_message('error', print_r($data, true));
                // Send the POST request with cURL
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $errors = curl_error($ch);
                $response_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response = curl_exec($ch);

                curl_close($ch);

                log_message('error', 'send_sms response');
                log_message('error', print_r( [ $response_http ] , TRUE) );
               
            }
        } catch (Exception $e) {

            log_message('error', 'Email Library ::send_sms');
            log_message('error', $e->getMessage());
        }
  
        return $response;
    }

    public function candidate_submit_comp($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'candidate_submit_component', $view_data, true);
     
        $this->CI->email->from($email_tmpl_data['from_email']);

        $this->CI->email->to($email_tmpl_data['to_emails']);

        $this->CI->email->cc($email_tmpl_data['cc_emails']);

        $subject = htmlentities($email_tmpl_data['component_name']) . ' details have been submitted for ' . htmlentities($email_tmpl_data['clientname']) . ' - ' .htmlentities($email_tmpl_data['cands_name']). ' ';
 
        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        return $this->CI->email->send();

    }

    public function candidate_submit_component($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data;

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'client_update_candidate', $view_data, true);

        //$this->CI->email->from(FROMEMAIL);
        $this->CI->email->from(FROMEMAIL);

        $this->CI->email->to($email_tmpl_data['to_emails']);

        $this->CI->email->cc($email_tmpl_data['cc_emails']);

        $this->CI->email->bcc($email_tmpl_data['bcc_emails']);

        $this->CI->email->subject($email_tmpl_data['subject']);

        $this->CI->email->message($message);

        return $this->CI->email->send();

    }

    public function address_approve_vendor_cases_mail_send($email_tmpl_data)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $cc_emails = VENDOREMAIL.",".MAINEMAIL;

        $cc_emails = explode(',', $cc_emails); 

        $cc_emails = array_unique($cc_emails); 

        $cc_emails = implode(",", $cc_emails);  
         
        $this->CI->email->from(VENDOREMAIL);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $this->CI->email->to(implode(', ', $to_emails));
       
        $this->CI->email->cc($cc_emails);

        $subject = $email_tmpl_data['subject'];
        $message = $email_tmpl_data['message'];

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);
        

        if($this->CI->email->send())
        {
           return "Success";
           
        }
        else
        {
            return "Error";  
        }

    }

    public function client_add_cases_and_pre_post($email_tmpl_data)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $from_emails = $email_tmpl_data['from_emails'];

        $to_emails = $email_tmpl_data['to_emails'];

        $this->CI->email->from($from_emails);

        $this->CI->email->to($to_emails);
       
        $this->CI->email->cc($from_emails);

        $subject = $email_tmpl_data['subject'];
        
        $message = $email_tmpl_data['message'];

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);
       
        return $this->CI->email->send();

    }


    public function admin_send_follow_up_mail($email_tmpl_data)
    {
        $view_data['email_info'] = $email_tmpl_data['detail_info'];
        $view_data['initiation_mail_detail'] = $email_tmpl_data['initiation_mail_detail'];
     //   $view_data['initiation_mail_data'] = $email_tmpl_data['initiation_mail_data'];
        $view_data['user_profile_info'] = $email_tmpl_data['user_profile_info'];

        $message = $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME . 'employment_follow_up_template', $view_data, true);

        $this->CI->email->from($email_tmpl_data['from_email']);

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $this->CI->email->to(implode(', ', $to_emails));

        $cc_emails = explode(",", $email_tmpl_data['cc_emails']);

        $bcc_emails = explode(",", $email_tmpl_data['bcc_emails']);

        $cc_bcc_emails = array_merge($cc_emails, $bcc_emails);

        $this->CI->email->cc(implode(', ', $cc_bcc_emails));

        $subject = 'RE: Employment Verification of ' . htmlentities($email_tmpl_data['detail_info']['CandidateName']) . '-' . htmlentities($email_tmpl_data['detail_info']['cmp_ref_no']) . ' ';

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

       /* $path = $email_tmpl_data['detail_info']['clientid'] . '/';

        $files = explode(',', $email_tmpl_data['detail_info']['file_names']);

        if ($email_tmpl_data['detail_info']['file_names'] != '') {
            for ($i = 0; $i < count($files); $i++) {
                $this->CI->email->attach($path . $files[$i]);
            }
        }*/
        return $this->CI->email->send();
    }

    public function component_send_insuff_raised($email_tmpl_data)
    {
        set_time_limit(0);
          
        $from_email = $email_tmpl_data['from_emails'];
           
        $to_emails = $email_tmpl_data['to_emails'];

        $cc_emails = $email_tmpl_data['cc_emails'];
       
        $cc_emails = explode(',', $cc_emails); 

        $cc_emails = array_unique($cc_emails); 

        $cc_emails = implode(",", $cc_emails); 
        
        $message = $email_tmpl_data['message'];

        $subject = $email_tmpl_data['subject'];    

        $this->CI->email->to($to_emails);

        $this->CI->email->cc($cc_emails);

        $this->CI->email->from($from_email);

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        return $this->CI->email->send();
    }

    public function address_invite_mail_sms($results) 
    {
        $this->CI->load->model('common_model');

        $details_arry = $this->CI->common_model->select_address_data($results);
   
        foreach ($details_arry as $key => $detail) {

            $login_url = SITE_URL.'av/'.base64_encode($detail['id']);

            // email
            $detail['login_url'] = $login_url;
 
            if($detail['cands_email_id'])
            {
              
                $view_data['email_info'] = $detail;

                $subject =  'Digital address verification for '.ucwords($detail['clientname']).' – '.ucwords($detail['CandidateName']);

                $message = $this->CI->load->view('email_tem/invite_mail', $view_data, TRUE);

                $this->CI->email->from(VERIFICATIONEMAIL);
                $this->CI->email->to($detail['cands_email_id']);
                $this->CI->email->cc(VENDOREMAIL.','.$detail['client_manager_email_id']);
                $this->CI->email->subject($subject);
                $this->CI->email->message($message);
                
                if($this->CI->email->send())  {
                     $this->CI->common_model->set_address_verification_status(array('address_is_mail_sent' => STATUS_ACTIVE,'is_mail_sent' => STATUS_ACTIVE,'last_email_on' => date(DB_DATE_FORMAT)),array('id'=>$detail['id']));

                     $this->CI->common_model->save('address_digital_mail_sms',array('address_id' => $detail['id'],' sms_mail_send_date_time' => date(DB_DATE_FORMAT),'type' => '1'));
                }
            }
           
            //sms
            if($detail['CandidatesContactNumber'])
            {
                $sms_content = "Greetings from MIST IT Services. Please click on the link below and fill for address verification ".$login_url;

                $file_responce = $this->send_sms($detail['CandidatesContactNumber'], $sms_content);
            }
            if(!empty($file_responce)) {

                $this->CI->common_model->set_address_verification_status(array('address_is_sms_sent' => STATUS_ACTIVE,'is_sms_sent' => STATUS_ACTIVE,'last_sms_on' => date(DB_DATE_FORMAT)),array('id'=>$detail['id']));
                
                $this->CI->common_model->save('address_digital_mail_sms',array('address_id' => $detail['id'],' sms_mail_send_date_time' => date(DB_DATE_FORMAT),'type' => '2'));
                
            }
          
            // call 
           /* $curl_url = "http://115.112.52.186:2086/bgvApi/ClickApi.php?customerNumber=".$detail['send_mobile_no']."&customerName=".$detail['candidate_name']."&clientName=".$detail['client_name'];
            
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, $curl_url);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            $phoneList = curl_exec($cURLConnection);
            curl_close($cURLConnection);

            $call_array[] = array('call_url' => $curl_url,'is_call_received' => 1,'id' => $detail['id']);*/
        }

        return true;
    }

    public function education_vendor_case_send_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $to_emails = $email_tmpl_data['to_emails'];

        $cc_emails = $email_tmpl_data['cc_email_id'];
       
        $message = $email_tmpl_data['message'];
        $subject = $email_tmpl_data['subject'];
        $vendor_id = $email_tmpl_data['vendor_id'];
        $vendor_id = $email_tmpl_data['vendor_id'];

    //$message .= $this->CI->load->view(EMAIL_VIEW_FOLDER_NAME.'emailer_signature', $view_data, TRUE);

        $this->CI->email->to($to_emails);

        $this->CI->email->cc($cc_emails);


        $this->CI->email->from(VENDOREMAIL);

        $this->CI->email->subject($subject);

        $this->CI->email->message($message);

        $path = SITE_BASE_PATH . EDUCATION .  'vendor_mail_file/' .$vendor_id ;

        $this->CI->email->attach($path . "/" . $email_tmpl_data['attchment']);

       return $this->CI->email->send();
    }

    public function employment_field_visit_mail_send($email_tmpl_data,$frm_details)
    {
        set_time_limit(0);

        $this->CI->email->from(VENDOREMAIL);

        $this->CI->email->to($frm_details['to_emails']);
        $this->CI->email->cc(VENDOREMAIL.','.MAINEMAIL);

        $this->CI->email->subject($frm_details['subject']);

        $this->CI->email->message($frm_details['message']);

        $path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'] . '/';

       
        if(!empty($email_tmpl_data))
        {
            for ($i = 0; $i < count($email_tmpl_data); $i++) {
   
                if(file_exists($path . $email_tmpl_data[$i]))
                {
                    $this->CI->email->attach($path . $email_tmpl_data[$i]);
                }
                  
            }
        }
        if($this->CI->email->send())
        {
           return "Success";
           
        }
        else
        {
            return "Error";  
        }
    }




}
