<?php
defined('BASEPATH') or exit('No direct script access allowed');

defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', true);

defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/* site constant start */
define('SITE_BASE_PATH', FCPATH . '/');

define('SITE_FOLDER', 'mist-it/');

if (php_sapi_name() === 'cli') {
	define('SITE_URL', 'https://localhost/' . SITE_FOLDER);
} else {
	define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . SITE_FOLDER);
}

define('SMTPHOST', 'mail.mistitservices.com');
define('SMTPUSER', 'employment.ver@mistitservices.com');
define('SMTPPASSWORD', 'M%GU@80#02');

define('CRMNAME', 'MIST IT Services Private Ltd');
define('SMS_NAME', 'MIST IT Services');
define('SHORTCRMNAME', 'MIST');
define('SHORT_CO_NAME', 'Mist IT');
define('COMPANYREFNO', 'Mist Ref No');
define('COMPANYREFNOCAP', 'MIST REF NO');
define('COMPANYADDRESS', '185, GARHWALI MOHALLA, BLOCK-I, LAXMI NAGAR,');
define('COMPANYADDRESSCITYPIN', 'New Delhi - 110092');
define('SHORT_FOLDER', 'M');

define('REFNO', 'CRM Ref No');
define('CMPREFNO', 'Component Ref No');
define('FROMEMAIL', 'employment.ver@mistitservices.com');
define('VENDOREMAIL', 'vendors@mistitservices.com');
define('VERIFICATIONEMAIL', 'verification@mistitservices.com');
define('MAINEMAIL', 'binoy@mistitservices.com');
define('REPORTEMAIL', 'bgvreports@mistitservices.com');
define('DISCEMAIL', 'binoy@mistitservices.com');

define('WEBSITE', 'www.mistitservices.com');
define('SHORTWEBSITE', 'mistitservices.com');
define('HTTPWEBSITE', 'https://www.mistitservices.com');

define('MOBILENO', '98670 10011');
define('MASSAGESENDER', 'MISTIT');

define('ADMIN_SITE_URL', SITE_URL . 'admin/');

define('VENDOR_SITE_URL', SITE_URL . 'vendor/');

define('CLIENT_SITE_URL', SITE_URL . 'client/');

define('CANDIDATE_SITE_URL', SITE_URL . 'candidate_info/');

define('VENDOR_EXECUTIVE_SITE_URL', SITE_URL . 'executive/');



define('SERVER_URL' , 'https://mistitservices.in/');
define('CANDIDATE_VERIFY_LINK' , SERVER_URL.SITE_FOLDER.'ver/');


define('IP_SITE_URL', SITE_URL);

define('ASSETS_FOLDER', 'assets/');

define('UPLOAD_FOLDER', 'uploads/');

define('PROFILE_PIC_PATH', UPLOAD_FOLDER . 'user_profile/');
define('VENDOR_PROFILE_PIC_PATH', UPLOAD_FOLDER . 'vendor_profile/');
define('UNIVERSITY_PIC', UPLOAD_FOLDER . 'university_pic/');


define('SITE_IMAGES_URL', SITE_URL . ASSETS_FOLDER . 'images/');

define('SITE_JS_URL', SITE_URL . ASSETS_FOLDER . 'js/');

define('SITE_CSS_URL', SITE_URL . ASSETS_FOLDER . 'css/');

define('SITE_PLUGINS_URL', SITE_URL . ASSETS_FOLDER . 'plugins/');

define('CANDIDATES_BULK_FILES', UPLOAD_FOLDER . 'candidate_bulk_upload/');

define('CANDIDATE_REPORT_FILE', SITE_URL . UPLOAD_FOLDER . 'candidate_report_file/');

define('DB_DATE_FORMAT', "Y-m-d H:i:s");

define('DISPLAY_DATE_FORMAT12', "d-m-Y H:i");

define('DISPLAY_DATE_FORMAT1', "d-m-Y_H:i");

define('DISPLAY_DATE_FORMATDATE', "d-m-Y");

define('UPLOAD_FILE_DATE_FORMAT', "Y-m-d-H-i-s");

define('UPLOAD_FILE_DATE_FORMAT_FILE_NAME', "d-m-Y_H-i-s");

define('DATE_ONLY', "Y-m-d");

define('MONTH_FORMAT', "d-M-Y");

define('BULK_UPLOAD_MAX_SIZE_MB', 10);

define('SUCCESS_CODE', 200); //status ok

define('ERROR_CODE', 400); //status error

define('STATUS_ACTIVE', 1);

define('STATUS_DEACTIVE', 0);

define('STATUS_DELETED', 2);

define('REST_CONTROLLER_PATH', APPPATH . 'libraries/REST_Controller.php');

define('REST_ACCESS_TOKEN_EXPIRY_SEC', 60 * 60 * 5); // 5 hrs in seconds

define('EMAIL_VIEW_FOLDER_NAME', 'email_tem/');

define('ADMIN_ROLE', '55');

const OVERALL_STATUS = array('WIP' => 'WIP', 'Insufficiency' => "Insufficiency", 'Discrepancy' => 'Discrepancy', 'No Record Found' => 'No Record Found', 'Unable to Verify' => 'Unable to Verify', 'Stop/Check' => 'Stop/Check', 'Clear' => 'Clear');

const COMPONENT_REF_NO = array('CANDIDATES' => 'MIST-', 'ADDRESS' => 'ADD-', 'EMPLOYMENT' => 'EMP-', 'COURT' => 'CRT-', 'GLOBAL' => 'GDB-', 'PCC' => 'PCC-', 'EDUCATION' => 'EDU-', 'DRUGS' => 'DRN-', 'REFERENCES' => 'REF-', 'CREDIT_REPORT' => 'CBR-', 'KYC' => 'KYC-', 'SOCIAL' => 'SMC-', 'IDENTITY' => 'IDE-', 'KYC' => "KYC-", 'CG' => "CG-");

const GENDER = array('0' => 'Select', 'male' => 'Male', 'female' => 'Female', 'other' => 'Other');

const MODEOFVERIFICATION = array('Verbal' => 'Verbal', 'Personal visit' => 'Personal visit', 'Others' => 'Others', 'E-mail' => 'E-mail');

const CLIENT_CONST = array('CLIENT_LOGO_PATH' => SITE_BASE_PATH . UPLOAD_FOLDER . 'logos', 'CLIENT_AGGREMENT_PATH' => SITE_BASE_PATH . UPLOAD_FOLDER . 'aggregate_file');

const QC_STATUS = array('First QC Pending' => 'First QC Pending', 'First QC Approved' => 'First QC Approved', 'Final QC Reject' => 'Final QC Reject', 'Final QC Pending' => 'Final QC Pending', 'Final QC Approved' => 'Final QC Approved', 'First QC Reject' => 'First QC Reject');

const ADDRESS_TYPE = array('' => 'Select Type', 'permanent' => 'Permanent', 'current' => 'Current', 'current/permanent' => 'Current/Permanent');

const RESIDENCE_STATUS = array('Rented'=> 'Rented','Owned'=>'Owned','PG'=>'PG','Shared'=> 'Shared','Relative'=>'Relative','Hostel'=>'Hostel');

define('COM_ATTACHMENTS_PATH', SITE_URL);

define('ADDRESS', UPLOAD_FOLDER . 'address/');

define('EMPLOYMENT', UPLOAD_FOLDER . 'employment/');

define('EDUCATION', UPLOAD_FOLDER . 'education/');

define('CANDIDATES', UPLOAD_FOLDER . 'candidates/');

define('CLIENT', UPLOAD_FOLDER . 'aggregate_file/');

define('CLIENT_LOGO', UPLOAD_FOLDER . 'logos/');

define('PCC', UPLOAD_FOLDER . 'pcc/');

define('SOCIAL_MEDIA', UPLOAD_FOLDER . 'social_media/');

define('CG', UPLOAD_FOLDER . 'cg/');

define('GLOBAL_DB', UPLOAD_FOLDER . 'global_db/');

define('DRUGS', UPLOAD_FOLDER . 'drugs/');

define('IDENTITY', UPLOAD_FOLDER . 'identity/');

define('CREDIT_REPORT', UPLOAD_FOLDER . 'credit_report/');

define('BILLING_REPORT', UPLOAD_FOLDER . 'billing/');

define('COURT_VERIFICATION', UPLOAD_FOLDER . 'court_verification/');

define('REFERENCES', UPLOAD_FOLDER . 'references/');

define('CASHLESS_INVEST', UPLOAD_FOLDER . 'cashless_investigation/');

define('CANDIDATE_REPORT', UPLOAD_FOLDER . 'candidate_report_file/');

define('COMPANYLOGO', 'http://www.mistitservices.com/images/logo/mlogo.png');
define('NASSCOM', 'https://www.mistitservices.com/images/allcred.png');

define('AUTHOR', 'MIST');

define('SMTPHOSTNAME', '3.109.13.110');

define('SMTPHOSTEMAIL', 'mail.mistitservices.com');






      