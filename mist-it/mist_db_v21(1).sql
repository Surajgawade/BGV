-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2022 at 02:19 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mist_db_v21`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_data`
--

CREATE TABLE `activity_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `add_result` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `parent_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `components_id` smallint(5) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `activity_remark` varchar(100) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `component_type` varchar(25) NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `address_activity_data`
--

CREATE TABLE `address_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `address_details`
--

CREATE TABLE `address_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `address_id` int(11) NOT NULL,
  `address_edit` varchar(250) DEFAULT NULL,
  `latitude` double(10,7) NOT NULL,
  `longitude` double(10,7) NOT NULL,
  `nature_of_residence` varchar(50) NOT NULL,
  `period_stay` varchar(50) DEFAULT NULL,
  `period_to` varchar(50) DEFAULT NULL,
  `verifier_name` varchar(100) NOT NULL,
  `relation_verifier_name` varchar(100) NOT NULL,
  `candidate_remarks` varchar(250) NOT NULL,
  `google_map_image` varchar(100) DEFAULT NULL,
  `selfie` varchar(250) NOT NULL,
  `address_proof` varchar(250) NOT NULL,
  `location_picture_1` varchar(250) NOT NULL,
  `location_picture_2` varchar(250) DEFAULT NULL,
  `house_pic_door` varchar(250) NOT NULL,
  `signature` varchar(250) NOT NULL,
  `selfie_lat_long` varchar(100) NOT NULL,
  `address_proof_lat_long` varchar(100) NOT NULL,
  `location_picture_lat_long_1` varchar(100) NOT NULL,
  `location_picture_lat_long_2` varchar(200) NOT NULL,
  `house_pic_door_lat_long` varchar(100) NOT NULL,
  `signature_lat_long` varchar(100) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `place_distance` varchar(20) DEFAULT '0' COMMENT 'in km',
  `status` tinyint(2) DEFAULT NULL,
  `reject_reason` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `address_digital_mail_sms`
--

CREATE TABLE `address_digital_mail_sms` (
  `id` int(11) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1 = mail 2 =sms',
  `sms_mail_send_date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `address_vendor_log`
--

CREATE TABLE `address_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `address_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_address_reject_vendor_assigned` AFTER UPDATE ON `address_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE addrver set vendor_id = 0 WHERE addrver.id = NEW.case_id;
        INSERT INTO address_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.modified_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO address_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.modified_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_address_vendor_assign` BEFORE INSERT ON `address_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO address_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `addrver`
--

CREATE TABLE `addrver` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) DEFAULT NULL,
  `candsid` int(11) DEFAULT NULL,
  `add_com_ref` varchar(50) NOT NULL,
  `stay_from` varchar(50) NOT NULL,
  `stay_to` varchar(50) NOT NULL,
  `address_type` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(50) NOT NULL,
  `mod_of_veri` varchar(100) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `has_case_id` bigint(20) UNSIGNED NOT NULL,
  `has_assigned_on` datetime NOT NULL,
  `iniated_date` date DEFAULT NULL,
  `add_re_open_date` varchar(100) DEFAULT NULL,
  `add_reinitiated_remark` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `vendor_digital_id` int(11) DEFAULT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vendor_digital_list_mode` varchar(100) DEFAULT NULL,
  `vendor_digital_assgined_on` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = Admin 1=Client 2=Candidate',
  `address_is_mail_sent` tinyint(2) DEFAULT NULL,
  `address_is_sms_sent` tinyint(2) DEFAULT NULL,
  `is_sms_sent` tinyint(4) DEFAULT NULL,
  `last_sms_on` datetime DEFAULT NULL,
  `is_mail_sent` tinyint(4) DEFAULT NULL,
  `last_email_on` datetime DEFAULT NULL,
  `user_clicked` tinyint(2) NOT NULL DEFAULT '0',
  `ip_address` varchar(60) DEFAULT NULL,
  `last_user_clicked` timestamp NULL DEFAULT NULL,
  `verification_status` tinyint(2) NOT NULL DEFAULT '1',
  `cancel_reason` varchar(500) DEFAULT NULL,
  `link_visit_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `addrver`
--
DELIMITER $$
CREATE TRIGGER `tgr_address_new_check` AFTER INSERT ON `addrver` FOR EACH ROW BEGIN
DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO addrverres (verfstatus,var_filter_status,var_report_status,clientid,candsid,addrverid,closuredate,res_address_type,res_address,res_city,res_pincode,res_state,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NULL,NEW.address_type,NEW.address,NEW.city,NEW.pincode,NEW.state,NEW.created_on,is_created_by,is_created_by);
                
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `addrverres`
--

CREATE TABLE `addrverres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) NOT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `closuredate` date DEFAULT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `addrverid` bigint(20) UNSIGNED NOT NULL,
  `res_address_type` varchar(50) NOT NULL,
  `res_address` varchar(255) NOT NULL,
  `res_city` varchar(50) DEFAULT NULL,
  `res_pincode` varchar(6) DEFAULT NULL,
  `res_state` varchar(50) DEFAULT NULL,
  `res_stay_from` varchar(40) NOT NULL,
  `res_stay_to` varchar(40) DEFAULT NULL,
  `address_action` varchar(50) DEFAULT NULL,
  `city_action` varchar(50) DEFAULT NULL,
  `pincode_action` varchar(50) DEFAULT NULL,
  `state_action` varchar(50) DEFAULT NULL,
  `stay_from_action` varchar(50) DEFAULT NULL,
  `stay_to_action` varchar(50) DEFAULT NULL,
  `neighbour_1` varchar(50) NOT NULL,
  `neighbour_details_1` varchar(255) NOT NULL,
  `neighbour_2` varchar(50) NOT NULL,
  `neighbour_details_2` varchar(255) NOT NULL,
  `mode_of_verification` varchar(50) NOT NULL,
  `resident_status` varchar(50) DEFAULT NULL,
  `landmark` varchar(250) DEFAULT NULL,
  `verified_by` varchar(50) NOT NULL,
  `addr_proof_collected` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `first_qc_approve` varchar(25) NOT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` tinyint(3) UNSIGNED NOT NULL,
  `activity_log_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `addrverres_result`
--

CREATE TABLE `addrverres_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `closuredate` date DEFAULT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `addrverid` bigint(20) UNSIGNED NOT NULL,
  `res_address_type` varchar(50) NOT NULL,
  `res_address` varchar(255) NOT NULL,
  `res_city` varchar(50) DEFAULT NULL,
  `res_pincode` varchar(6) DEFAULT NULL,
  `res_state` varchar(50) DEFAULT NULL,
  `res_stay_from` varchar(40) NOT NULL,
  `res_stay_to` varchar(40) DEFAULT NULL,
  `address_action` varchar(50) DEFAULT NULL,
  `city_action` varchar(50) DEFAULT NULL,
  `pincode_action` varchar(50) DEFAULT NULL,
  `state_action` varchar(50) DEFAULT NULL,
  `stay_from_action` varchar(50) DEFAULT NULL,
  `stay_to_action` varchar(50) DEFAULT NULL,
  `neighbour_1` varchar(50) NOT NULL,
  `neighbour_details_1` varchar(255) NOT NULL,
  `neighbour_2` varchar(50) NOT NULL,
  `neighbour_details_2` varchar(255) NOT NULL,
  `mode_of_verification` varchar(50) NOT NULL,
  `resident_status` varchar(50) DEFAULT NULL,
  `landmark` varchar(250) DEFAULT NULL,
  `verified_by` varchar(50) NOT NULL,
  `addr_proof_collected` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `first_qc_approve` varchar(25) NOT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` tinyint(3) UNSIGNED NOT NULL,
  `activity_log_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `addrver_files`
--

CREATE TABLE `addrver_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `addrver_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `addrver_insuff`
--

CREATE TABLE `addrver_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(500) DEFAULT NULL,
  `insuff_raise_remark` varchar(1000) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `addrverid` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete 4 = uppdated',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `addrver_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_address_insuff_raised` BEFORE INSERT ON `addrver_insuff` FOR EACH ROW BEGIN
UPDATE addrverres SET verfstatus = 18, var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE  	addrverid  = NEW.addrverid;
INSERT INTO address_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.addrverid,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_insuff_details_update` AFTER UPDATE ON `addrver_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO address_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.addrverid,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE addrverres SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE addrverid = NEW.addrverid;
    	INSERT INTO address_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.addrverid,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
    UPDATE addrverres SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE addrverid  = NEW.addrverid;
  INSERT INTO address_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.addrverid,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_menus`
--

CREATE TABLE `admin_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `controllers` varchar(50) NOT NULL,
  `parent_id` tinyint(4) NOT NULL,
  `serial_no` smallint(6) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `count` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `batch_update_log`
--

CREATE TABLE `batch_update_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `original_file_name` varchar(150) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidates_info`
--

CREATE TABLE `candidates_info` (
  `id` bigint(20) NOT NULL,
  `clientid` int(11) NOT NULL,
  `entity` int(10) UNSIGNED NOT NULL,
  `package` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `ClientRefNumber` varchar(25) DEFAULT NULL,
  `cmp_ref_no` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `CandidateName` varchar(50) NOT NULL,
  `DateofBirth` date DEFAULT NULL,
  `NameofCandidateFather` varchar(50) DEFAULT NULL,
  `MothersName` varchar(50) DEFAULT NULL,
  `CandidatesContactNumber` varchar(12) NOT NULL,
  `email_candidate` varchar(50) DEFAULT NULL,
  `email_password` varchar(250) DEFAULT NULL,
  `cands_email_id` varchar(50) NOT NULL,
  `ContactNo1` varchar(12) DEFAULT NULL,
  `ContactNo2` varchar(12) DEFAULT NULL,
  `DateofJoining` date DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `DesignationJoinedas` varchar(50) DEFAULT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `EmployeeCode` varchar(15) DEFAULT NULL,
  `PANNumber` varchar(10) DEFAULT NULL,
  `AadharNumber` varchar(12) DEFAULT NULL,
  `PassportNumber` varchar(10) DEFAULT NULL,
  `BatchNumber` varchar(5) DEFAULT NULL,
  `overallstatus` smallint(5) UNSIGNED NOT NULL,
  `overallclosuredate` date DEFAULT NULL,
  `caserecddate` date DEFAULT NULL,
  `overall_reiniated_date` date DEFAULT NULL,
  `remarks` longtext,
  `prasent_address` text,
  `cands_country` varchar(7) DEFAULT NULL,
  `cands_state` varchar(50) DEFAULT NULL,
  `cands_city` varchar(50) DEFAULT NULL,
  `cands_pincode` varchar(40) DEFAULT NULL,
  `build_date` varchar(300) DEFAULT NULL,
  `region` varchar(30) DEFAULT NULL,
  `branch_name` varchar(30) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `due_date_candidate` date DEFAULT NULL,
  `tat_status_candidate` varchar(15) DEFAULT NULL,
  `final_qc_updated_by` int(11) DEFAULT NULL,
  `final_qc` varchar(100) DEFAULT NULL,
  `final_qc_rejected_reason` varchar(500) DEFAULT NULL,
  `final_qc_arriving_timestamp` timestamp NULL DEFAULT NULL,
  `final_qc_approve_reject_timestamp` timestamp NULL DEFAULT NULL,
  `final_qc_send_mail` tinyint(4) DEFAULT '0',
  `final_qc_send_mail_timestamp` datetime DEFAULT NULL,
  `is_bulk_upload` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidates_info_logs`
--

CREATE TABLE `candidates_info_logs` (
  `id` int(11) NOT NULL,
  `candidates_info_id` int(11) NOT NULL,
  `clientid` int(11) DEFAULT NULL,
  `entity` int(11) DEFAULT NULL,
  `package` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `ClientRefNumber` varchar(255) DEFAULT NULL,
  `cmp_ref_no` varchar(50) DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `CandidateName` varchar(255) DEFAULT NULL,
  `DateofBirth` date DEFAULT NULL,
  `NameofCandidateFather` varchar(255) DEFAULT NULL,
  `MothersName` varchar(255) DEFAULT NULL,
  `CandidatesContactNumber` varchar(255) DEFAULT NULL,
  `email_candidate` varchar(50) DEFAULT NULL,
  `email_password` varchar(250) DEFAULT NULL,
  `ContactNo1` varchar(255) DEFAULT NULL,
  `ContactNo2` varchar(255) DEFAULT NULL,
  `cands_email_id` varchar(50) DEFAULT NULL,
  `DateofJoining` date DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `DesignationJoinedas` varchar(255) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  `EmployeeCode` varchar(255) DEFAULT NULL,
  `PANNumber` varchar(255) DEFAULT NULL,
  `AadharNumber` varchar(255) DEFAULT NULL,
  `PassportNumber` varchar(255) DEFAULT NULL,
  `BatchNumber` varchar(255) DEFAULT NULL,
  `overallstatus` varchar(40) DEFAULT NULL,
  `overallclosuredate` date DEFAULT NULL,
  `caserecddate` date DEFAULT NULL,
  `overall_reiniated_date` date DEFAULT NULL,
  `remarks` longtext,
  `prasent_address` text,
  `cands_country` varchar(7) DEFAULT NULL,
  `cands_state` varchar(50) DEFAULT NULL,
  `cands_city` varchar(50) DEFAULT NULL,
  `cands_pincode` varchar(40) DEFAULT NULL,
  `build_date` date DEFAULT NULL,
  `region` varchar(150) DEFAULT NULL,
  `branch_name` varchar(150) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED DEFAULT NULL,
  `final_qc` varchar(200) DEFAULT NULL,
  `final_qc_timestamp` timestamp NULL DEFAULT NULL,
  `is_bulk_upload` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_activity_record`
--

CREATE TABLE `candidate_activity_record` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `activity_status` varchar(100) DEFAULT NULL,
  `remark` text,
  `status` tinyint(2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `cancel_by` int(11) DEFAULT NULL,
  `cancel_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_files`
--

CREATE TABLE `candidate_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `clientname` varchar(255) NOT NULL,
  `clientmgr` varchar(20) NOT NULL,
  `sales_manager` varchar(20) NOT NULL,
  `comp_logo` varchar(150) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients_details`
--

CREATE TABLE `clients_details` (
  `id` int(11) NOT NULL,
  `tbl_clients_id` bigint(20) UNSIGNED NOT NULL,
  `entity` int(10) UNSIGNED NOT NULL,
  `package` int(10) UNSIGNED NOT NULL,
  `clientaddress` longtext NOT NULL,
  `clientcity` varchar(50) NOT NULL,
  `clientstate` varchar(50) NOT NULL,
  `clientpincode` varchar(6) NOT NULL,
  `clientphone1` varchar(50) NOT NULL,
  `clientphone2` varchar(50) DEFAULT NULL,
  `clientmobile` varchar(50) NOT NULL,
  `clientemail1` varchar(50) NOT NULL,
  `clientemail2` varchar(50) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `report_type` varchar(15) NOT NULL COMMENT 'full-report or only-annexure',
  `component_id` text NOT NULL,
  `component_name` text NOT NULL,
  `scope_of_work` text NOT NULL,
  `mode_of_verification` text NOT NULL,
  `candidate_component_count` text,
  `tat_addrver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_empver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_eduver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `interim_report` text NOT NULL,
  `final_report` text NOT NULL,
  `price` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_on` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `modified_by` bigint(20) DEFAULT NULL,
  `tat_refver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_courtver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_globdbver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_narcver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_crimver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_identity` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_cbrver` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_social_media` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `tat_KYC_collection` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `package_amount` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `candidate_report_type` tinyint(4) DEFAULT NULL,
  `candidate_add_component` tinyint(4) DEFAULT NULL,
  `final_qc` tinyint(4) DEFAULT NULL,
  `auto_report` tinyint(4) DEFAULT NULL,
  `insuff_report` tinyint(4) DEFAULT NULL,
  `case_type` tinyint(4) DEFAULT NULL,
  `billing_type` tinyint(4) DEFAULT NULL,
  `client_disclosures` tinyint(4) DEFAULT NULL,
  `case_activity` tinyint(2) DEFAULT NULL,
  `candidate_upload` tinyint(2) DEFAULT NULL,
  `user_upload` int(11) DEFAULT NULL,
  `pre_component` text,
  `post_component` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients_logs`
--

CREATE TABLE `clients_logs` (
  `clients_id` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `clientname` varchar(255) NOT NULL,
  `clientaddress` longtext NOT NULL,
  `clientcity` varchar(50) NOT NULL,
  `clientstate` varchar(50) NOT NULL,
  `clientpincode` varchar(6) NOT NULL,
  `clientphone1` varchar(50) NOT NULL,
  `clientphone2` varchar(50) DEFAULT NULL,
  `clientmobile` varchar(50) NOT NULL,
  `clientemail1` varchar(50) NOT NULL,
  `clientemail2` varchar(50) DEFAULT NULL,
  `addrver` varchar(50) DEFAULT NULL,
  `cbrver` varchar(50) DEFAULT NULL,
  `courtver` varchar(50) DEFAULT NULL,
  `crimver` varchar(50) DEFAULT NULL,
  `eduver` varchar(50) DEFAULT NULL,
  `empver` varchar(50) DEFAULT NULL,
  `globdbver` varchar(50) DEFAULT NULL,
  `narcver` varchar(50) DEFAULT NULL,
  `refver` varchar(50) DEFAULT NULL,
  `clientmgr` varchar(50) NOT NULL,
  `sales_manager` int(10) UNSIGNED NOT NULL,
  `tat_cbrver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_courtver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_crimver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_eduver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_empver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_globdbver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_narcver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_refver` mediumint(9) NOT NULL DEFAULT '0',
  `tat_addrver` mediumint(9) NOT NULL DEFAULT '0',
  `comp_logo` varchar(150) DEFAULT NULL,
  `aggrement_file` varchar(150) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `price_cbrver` varchar(5) DEFAULT NULL,
  `price_addrver` varchar(5) DEFAULT NULL,
  `client_remarks` varchar(2500) DEFAULT NULL,
  `price_refver` varchar(5) DEFAULT NULL,
  `price_narcver` varchar(5) DEFAULT NULL,
  `price_globdbver` varchar(5) DEFAULT NULL,
  `price_empver` varchar(5) DEFAULT NULL,
  `price_eduver` varchar(5) DEFAULT NULL,
  `price_crimver` varchar(5) DEFAULT NULL,
  `price_courtver` varchar(5) DEFAULT NULL,
  `claim_investigation` varchar(5) DEFAULT NULL,
  `tat_claim_investigation` mediumint(8) UNSIGNED DEFAULT NULL,
  `price_claim_investigation` varchar(5) DEFAULT NULL,
  `report_type` varchar(15) NOT NULL COMMENT 'full-report or only-annexure'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_aggr_details`
--

CREATE TABLE `client_aggr_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `aggr_start` date NOT NULL,
  `aggr_end` date DEFAULT NULL,
  `aggrement_file` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_candidates_info`
--

CREATE TABLE `client_candidates_info` (
  `id` bigint(20) NOT NULL,
  `cands_info_id` int(11) DEFAULT NULL,
  `clientid` int(11) NOT NULL,
  `entity` int(10) UNSIGNED NOT NULL,
  `package` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `ClientRefNumber` varchar(15) DEFAULT NULL,
  `cmp_ref_no` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `CandidateName` varchar(50) NOT NULL,
  `DateofBirth` date DEFAULT NULL,
  `NameofCandidateFather` varchar(50) DEFAULT NULL,
  `MothersName` varchar(50) DEFAULT NULL,
  `CandidatesContactNumber` varchar(12) NOT NULL,
  `email_candidate` varchar(50) DEFAULT NULL,
  `cands_email_id` varchar(50) NOT NULL,
  `ContactNo1` varchar(12) DEFAULT NULL,
  `ContactNo2` varchar(12) DEFAULT NULL,
  `DateofJoining` date DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `DesignationJoinedas` varchar(50) DEFAULT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `EmployeeCode` varchar(15) DEFAULT NULL,
  `PANNumber` varchar(10) DEFAULT NULL,
  `AadharNumber` varchar(12) DEFAULT NULL,
  `PassportNumber` varchar(10) DEFAULT NULL,
  `BatchNumber` varchar(5) DEFAULT NULL,
  `overallstatus` smallint(5) UNSIGNED NOT NULL,
  `overallclosuredate` date DEFAULT NULL,
  `caserecddate` date DEFAULT NULL,
  `overall_reiniated_date` date DEFAULT NULL,
  `remarks` longtext,
  `prasent_address` text,
  `cands_country` varchar(7) DEFAULT NULL,
  `cands_state` varchar(50) DEFAULT NULL,
  `cands_city` varchar(50) DEFAULT NULL,
  `cands_pincode` varchar(40) DEFAULT NULL,
  `build_date` varchar(20) DEFAULT NULL,
  `region` varchar(30) DEFAULT NULL,
  `branch_name` varchar(30) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `due_date_candidate` date DEFAULT NULL,
  `tat_status_candidate` varchar(15) DEFAULT NULL,
  `final_qc_updated_by` int(11) DEFAULT NULL,
  `final_qc` varchar(100) DEFAULT NULL,
  `final_qc_rejected_reason` varchar(500) DEFAULT NULL,
  `final_qc_arriving_timestamp` timestamp NULL DEFAULT NULL,
  `final_qc_approve_reject_timestamp` timestamp NULL DEFAULT NULL,
  `is_bulk_upload` tinyint(3) UNSIGNED NOT NULL,
  `check_mail_send` tinyint(4) NOT NULL DEFAULT '0',
  `public_key` varchar(100) DEFAULT NULL,
  `private_key` varchar(100) DEFAULT NULL,
  `address_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `employment_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `education_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `reference_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `court_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `global_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `identity_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `credit_report_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `drugs_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `pcc_component_check` tinyint(4) NOT NULL DEFAULT '0',
  `is_sms_sent` tinyint(4) DEFAULT NULL,
  `last_sms_on` datetime DEFAULT NULL,
  `cron_status` tinyint(4) DEFAULT NULL,
  `is_mail_sent` tinyint(4) DEFAULT NULL,
  `last_email_on` datetime DEFAULT NULL,
  `candidate_visit` tinyint(11) NOT NULL DEFAULT '0',
  `last_candidate_visit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_candidates_info_logs`
--

CREATE TABLE `client_candidates_info_logs` (
  `id` int(11) NOT NULL,
  `candidates_info_id` int(11) NOT NULL,
  `clientid` int(11) DEFAULT NULL,
  `entity` int(11) DEFAULT NULL,
  `package` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `ClientRefNumber` varchar(255) DEFAULT NULL,
  `cmp_ref_no` varchar(50) DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `CandidateName` varchar(255) DEFAULT NULL,
  `DateofBirth` date DEFAULT NULL,
  `NameofCandidateFather` varchar(255) DEFAULT NULL,
  `MothersName` varchar(255) DEFAULT NULL,
  `CandidatesContactNumber` varchar(255) DEFAULT NULL,
  `email_candidate` varchar(50) DEFAULT NULL,
  `email_password` varchar(250) DEFAULT NULL,
  `ContactNo1` varchar(255) DEFAULT NULL,
  `ContactNo2` varchar(255) DEFAULT NULL,
  `cands_email_id` varchar(50) DEFAULT NULL,
  `DateofJoining` date DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `DesignationJoinedas` varchar(255) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  `EmployeeCode` varchar(255) DEFAULT NULL,
  `PANNumber` varchar(255) DEFAULT NULL,
  `AadharNumber` varchar(255) DEFAULT NULL,
  `PassportNumber` varchar(255) DEFAULT NULL,
  `BatchNumber` varchar(255) DEFAULT NULL,
  `overallstatus` varchar(40) DEFAULT NULL,
  `overallclosuredate` date DEFAULT NULL,
  `caserecddate` date DEFAULT NULL,
  `overall_reiniated_date` date DEFAULT NULL,
  `remarks` longtext,
  `prasent_address` text,
  `cands_country` varchar(7) DEFAULT NULL,
  `cands_state` varchar(50) DEFAULT NULL,
  `cands_city` varchar(50) DEFAULT NULL,
  `cands_pincode` varchar(40) DEFAULT NULL,
  `build_date` date DEFAULT NULL,
  `region` varchar(150) DEFAULT NULL,
  `branch_name` varchar(150) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED DEFAULT NULL,
  `final_qc` varchar(200) DEFAULT NULL,
  `final_qc_timestamp` timestamp NULL DEFAULT NULL,
  `is_bulk_upload` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_candidate_files`
--

CREATE TABLE `client_candidate_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_login`
--

CREATE TABLE `client_login` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `password` varchar(80) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `mobile_no` varchar(11) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `profile_pic` varchar(100) DEFAULT NULL,
  `creted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` datetime DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `pass_reset_key` varchar(80) DEFAULT NULL,
  `pass_reset_expiry` date DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=clients 55=admin',
  `client_entity_access` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_mode_of_verification`
--

CREATE TABLE `client_mode_of_verification` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(250) NOT NULL,
  `component_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_new_cases`
--

CREATE TABLE `client_new_cases` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `total_cases` int(10) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL,
  `ekm_file` varchar(250) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `case_type` tinyint(4) DEFAULT NULL,
  `task_person_id` varchar(50) DEFAULT NULL,
  `task_completed_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `client_new_cases`
--
DELIMITER $$
CREATE TRIGGER `tgr_client_new_cases_log` AFTER INSERT ON `client_new_cases` FOR EACH ROW BEGIN

INSERT INTO client_new_cases_log (status,	client_id,total_cases,case_type,type,remarks,client_new_cases_id,created_on,created_by) VALUES (NEW.status,NEW.client_id,NEW.total_cases,NEW.case_type,NEW.type,NEW.remarks,NEW.id,NEW.created_on,NEW.created_by);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_client_new_cases_log_on_update` AFTER UPDATE ON `client_new_cases` FOR EACH ROW BEGIN

INSERT INTO client_new_cases_log (status,remarks,client_new_cases_id,created_on,created_by) VALUES (NEW.status,NEW.remarks,NEW.id,NEW.modified_on,NEW.modified_by);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `client_new_cases_log`
--

CREATE TABLE `client_new_cases_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `total_cases` int(11) DEFAULT NULL,
  `case_type` tinyint(4) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `remarks` varchar(250) NOT NULL,
  `client_new_cases_id` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_new_case_file`
--

CREATE TABLE `client_new_case_file` (
  `id` int(11) NOT NULL,
  `file_name` varchar(150) DEFAULT NULL,
  `real_filename` varchar(150) DEFAULT NULL,
  `client_new_case_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_new_case_rearranged_file`
--

CREATE TABLE `client_new_case_rearranged_file` (
  `id` int(11) NOT NULL,
  `file_name` varchar(300) DEFAULT NULL,
  `rearranged_file_id` int(11) DEFAULT NULL,
  `new_case_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_scope_of_work`
--

CREATE TABLE `client_scope_of_work` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scop_of_word` varchar(250) NOT NULL,
  `component_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_spoc_details`
--

CREATE TABLE `client_spoc_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clients_details_id` int(10) UNSIGNED NOT NULL,
  `spoc_name` varchar(40) NOT NULL,
  `spoc_email` varchar(200) NOT NULL,
  `spoc_mobile` varchar(12) DEFAULT NULL,
  `spoc_manager_email` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company_database`
--

CREATE TABLE `company_database` (
  `id` int(10) UNSIGNED NOT NULL,
  `cin_number` varchar(50) NOT NULL,
  `coname` varchar(100) NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `previous_emp_code` tinyint(4) DEFAULT NULL,
  `branch_location` tinyint(4) DEFAULT NULL,
  `experience_letter` tinyint(4) DEFAULT NULL,
  `loa` tinyint(4) DEFAULT NULL,
  `auto_initiate` tinyint(4) DEFAULT '1',
  `follow_up` tinyint(4) DEFAULT NULL,
  `client_disclosure` tinyint(4) DEFAULT NULL,
  `co_email_id` varchar(200) DEFAULT NULL,
  `cc_email_id` varchar(200) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `dropdown_status` tinyint(4) DEFAULT NULL,
  `modified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `company_database`
--
DELIMITER $$
CREATE TRIGGER `tbl_company_database_logs` BEFORE UPDATE ON `company_database` FOR EACH ROW INSERT INTO company_database_logs VALUES(OLD.id,OLD.coname,OLD.address,OLD.city,OLD.state,OLD.pincode,OLD.modified_by,OLD.modified_on,OLD.status)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `company_database_logs`
--

CREATE TABLE `company_database_logs` (
  `company_database_id` int(10) UNSIGNED NOT NULL,
  `coname` varchar(100) NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` int(10) UNSIGNED NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company_database_verifiers_details`
--

CREATE TABLE `company_database_verifiers_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empver_id` int(11) DEFAULT NULL,
  `company_database_id` int(10) UNSIGNED NOT NULL,
  `deputed_company` varchar(100) DEFAULT NULL,
  `domain_name` varchar(100) NOT NULL,
  `domain_purchased` varchar(50) NOT NULL,
  `verifiers_name` varchar(50) NOT NULL,
  `verifiers_designation` varchar(40) NOT NULL,
  `verifiers_contact_no` varchar(15) NOT NULL,
  `verifiers_email_id` varchar(50) NOT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company_database_verifiers_details_bk`
--

CREATE TABLE `company_database_verifiers_details_bk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `domain_name` varchar(100) NOT NULL,
  `domain_purchased` varchar(50) NOT NULL,
  `verifiers_name` varchar(50) NOT NULL,
  `verifiers_designation` varchar(40) NOT NULL,
  `verifiers_contact_no` varchar(15) NOT NULL,
  `verifiers_email_id` varchar(50) NOT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE `components` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `component_name` varchar(50) NOT NULL,
  `component_key` varchar(35) NOT NULL,
  `show_component_name` varchar(100) NOT NULL,
  `scope_of_work` text NOT NULL,
  `mode_of_verification` text NOT NULL,
  `images` varchar(50) DEFAULT NULL,
  `icons` varchar(50) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT 'define(''STATUS_ACTIVE'',1);  define(''STATUS_DEACTIVE'',0);  define(''STATUS_DELETED'',2);',
  `serial_id` smallint(5) UNSIGNED NOT NULL,
  `admin_page_id` tinyint(3) UNSIGNED NOT NULL,
  `vendor_icon` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `components_admin`
--

CREATE TABLE `components_admin` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `component_name` varchar(50) NOT NULL,
  `component_key` varchar(35) NOT NULL,
  `show_component_name` varchar(100) NOT NULL,
  `scope_of_work` text NOT NULL,
  `mode_of_verification` text NOT NULL,
  `images` varchar(50) DEFAULT NULL,
  `icons` varchar(50) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT 'define(''STATUS_ACTIVE'',1);  define(''STATUS_DEACTIVE'',0);  define(''STATUS_DELETED'',2);',
  `serial_id` smallint(5) UNSIGNED NOT NULL,
  `admin_page_id` tinyint(3) UNSIGNED NOT NULL,
  `vendor_icon` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `components_client`
--

CREATE TABLE `components_client` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `component_name` varchar(50) NOT NULL,
  `component_key` varchar(35) NOT NULL,
  `show_component_name` varchar(100) NOT NULL,
  `scope_of_work` text NOT NULL,
  `mode_of_verification` text NOT NULL,
  `images` varchar(50) DEFAULT NULL,
  `icons` varchar(50) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT 'define(''STATUS_ACTIVE'',1);  define(''STATUS_DEACTIVE'',0);  define(''STATUS_DELETED'',2);',
  `serial_id` smallint(5) UNSIGNED NOT NULL,
  `admin_page_id` tinyint(3) UNSIGNED NOT NULL,
  `vendor_icon` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `controller_mothod`
--

CREATE TABLE `controller_mothod` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `contoller_name` varchar(100) DEFAULT NULL,
  `method_name` varchar(100) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country_ip`
--

CREATE TABLE `country_ip` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_name` varchar(200) NOT NULL,
  `country_group` varchar(200) NOT NULL,
  `country_ips` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `courtver`
--

CREATE TABLE `courtver` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `court_com_ref` varchar(50) NOT NULL,
  `iniated_date` date NOT NULL,
  `address_type` varchar(50) NOT NULL,
  `street_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(50) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `courtver_re_open_date` varchar(500) DEFAULT NULL,
  `courtver_reinitiated_remark` varchar(500) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `courtver`
--
DELIMITER $$
CREATE TRIGGER `tgr_court_new_check` AFTER INSERT ON `courtver` FOR EACH ROW BEGIN
DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO courtver_result (verfstatus,var_filter_status,var_report_status,clientid,candsid,courtver_id,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NEW.created_on,is_created_by,is_created_by);
                
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courtver_files`
--

CREATE TABLE `courtver_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `courtver_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `courtver_insuff`
--

CREATE TABLE `courtver_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `courtver_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `courtver_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_court_insuff_raised` BEFORE INSERT ON `courtver_insuff` FOR EACH ROW BEGIN
UPDATE courtver_result SET verfstatus = 18, var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE courtver_id  = NEW.courtver_id;
INSERT INTO court_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.courtver_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_court_insuff_update` AFTER UPDATE ON `courtver_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO court_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.courtver_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE courtver_result SET verfstatus = 11,var_filter_status ='WIP',var_report_status='WIP' WHERE courtver_id  = NEW.courtver_id;
    	INSERT INTO court_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.courtver_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
    UPDATE courtver_result SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE courtver_id = NEW.courtver_id;
  INSERT INTO court_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.courtver_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courtver_result`
--

CREATE TABLE `courtver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `courtver_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `verified_date` date DEFAULT NULL,
  `advocate_name` varchar(50) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `courtver_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_court_autostamp` AFTER INSERT ON `courtver_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO court_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courtver_vendor_log`
--

CREATE TABLE `courtver_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `courtver_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_court_reject_vendor_assigned` AFTER UPDATE ON `courtver_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE courtver set vendor_id = 0 WHERE courtver.id = NEW.case_id;
     INSERT INTO court_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.created_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO court_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.created_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_court_vendor_assign` BEFORE INSERT ON `courtver_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO court_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courtver_ver_result`
--

CREATE TABLE `courtver_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `courtver_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `verified_date` date DEFAULT NULL,
  `advocate_name` varchar(50) NOT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `court_activity_data`
--

CREATE TABLE `court_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `court_vendor_details`
--

CREATE TABLE `court_vendor_details` (
  `sr_id` int(11) NOT NULL,
  `view_vendor_master_log_id` int(11) DEFAULT NULL,
  `date_of_verification` date DEFAULT NULL,
  `civil_civil_proceed` varchar(500) DEFAULT NULL,
  `civil_high_proceed` varchar(500) DEFAULT NULL,
  `criminal_magistrate_proceed` varchar(500) DEFAULT NULL,
  `criminal_sessions_proceed` varchar(500) DEFAULT NULL,
  `criminal_high_proceed` varchar(500) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_report`
--

CREATE TABLE `credit_report` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `credit_report_com_ref` varchar(50) NOT NULL,
  `iniated_date` date NOT NULL,
  `doc_submited` varchar(50) DEFAULT NULL,
  `id_number` varchar(40) DEFAULT NULL,
  `street_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(35) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `credit_report_re_open_date` varchar(100) DEFAULT NULL,
  `credit_report_reinitiated_remark` varchar(500) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `credit_report`
--
DELIMITER $$
CREATE TRIGGER `tgr_credit_report_new_check` AFTER INSERT ON `credit_report` FOR EACH ROW BEGIN

DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO credit_report_result(verfstatus,var_filter_status,var_report_status,clientid,candsid,credit_report_id,closuredate,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NULL,NEW.created_on,is_created_by,is_created_by);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `credit_report_activity_data`
--

CREATE TABLE `credit_report_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_report_files`
--

CREATE TABLE `credit_report_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `credit_report_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_report_insuff`
--

CREATE TABLE `credit_report_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `credit_report_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `credit_report_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_credit_insuff_details_update` AFTER UPDATE ON `credit_report_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO credit_report_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.credit_report_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE credit_report_result SET verfstatus = 11,var_filter_status ='WIP',var_report_status='WIP' WHERE credit_report_id  = NEW.credit_report_id;
    	INSERT INTO credit_report_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.credit_report_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
    UPDATE credit_report_result SET verfstatus = 1,var_filter_status ='WIP',var_report_status='WIP' WHERE credit_report_id = NEW.credit_report_id;
  INSERT INTO credit_report_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.credit_report_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_credit_insuff_raised` BEFORE INSERT ON `credit_report_insuff` FOR EACH ROW BEGIN
UPDATE credit_report_result SET verfstatus = 18, var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE credit_report_id = NEW.credit_report_id;
INSERT INTO credit_report_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.credit_report_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `credit_report_result`
--

CREATE TABLE `credit_report_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `credit_report_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `credit_report_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_credit_report_autostamp` AFTER INSERT ON `credit_report_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO credit_report_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `credit_report_vendor_log`
--

CREATE TABLE `credit_report_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `credit_report_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_credit_report_reject_vendor_assigned` AFTER UPDATE ON `credit_report_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE credit_report set vendor_id = 0 WHERE credit_report.id = NEW.case_id;
        INSERT INTO credit_report_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.created_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO credit_report_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.created_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_credit_report_vendor_assign` BEFORE INSERT ON `credit_report_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO credit_report_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `credit_report_ver_result`
--

CREATE TABLE `credit_report_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `credit_report_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cron_job`
--

CREATE TABLE `cron_job` (
  `id` int(11) NOT NULL,
  `activity_name` varchar(200) DEFAULT NULL,
  `description` text,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `executed_on` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cron_job_component`
--

CREATE TABLE `cron_job_component` (
  `id` int(11) NOT NULL,
  `cron_job_id` int(11) DEFAULT NULL,
  `cron_job_component_selection` varchar(500) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `cancel_by` int(11) DEFAULT NULL,
  `cancel_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drug_narcotis`
--

CREATE TABLE `drug_narcotis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `drug_com_ref` varchar(50) NOT NULL,
  `drug_re_open_date` varchar(100) DEFAULT NULL,
  `drug_reinitiated_remark` varchar(500) DEFAULT NULL,
  `iniated_date` date NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time NOT NULL,
  `spoc_no` varchar(14) NOT NULL,
  `drug_test_code` varchar(20) NOT NULL,
  `facility_name` varchar(25) NOT NULL,
  `street_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(50) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `drug_narcotis`
--
DELIMITER $$
CREATE TRIGGER `tgr_drug_narcotis_new_check` AFTER INSERT ON `drug_narcotis` FOR EACH ROW BEGIN

DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO drug_narcotis_result(verfstatus,var_filter_status,var_report_status,clientid,candsid,drug_narcotis_id,closuredate,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NULL,NEW.created_on,is_created_by,is_created_by);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_narcotis_activity_data`
--

CREATE TABLE `drug_narcotis_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drug_narcotis_files`
--

CREATE TABLE `drug_narcotis_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `drug_narcotis_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drug_narcotis_insuff`
--

CREATE TABLE `drug_narcotis_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `drug_narcotis_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `drug_narcotis_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_drug_insuff_raised` BEFORE INSERT ON `drug_narcotis_insuff` FOR EACH ROW BEGIN
UPDATE drug_narcotis_result SET verfstatus = 18, var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE drug_narcotis_id = NEW.drug_narcotis_id;

INSERT INTO drug_narcotis_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.drug_narcotis_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_drug_insuff_update` AFTER UPDATE ON `drug_narcotis_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO drug_narcotis_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.drug_narcotis_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE drug_narcotis_result SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE  drug_narcotis_id = NEW.drug_narcotis_id;
    	INSERT INTO drug_narcotis_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.drug_narcotis_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
    UPDATE drug_narcotis_result SET verfstatus = 1,var_filter_status ='WIP', var_report_status='WIP' WHERE  drug_narcotis_id  = NEW.drug_narcotis_id;
  INSERT INTO drug_narcotis_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.drug_narcotis_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_narcotis_result`
--

CREATE TABLE `drug_narcotis_result` (
  `id` int(10) UNSIGNED NOT NULL,
  `drug_narcotis_id` int(10) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(40) DEFAULT NULL,
  `remarks` text NOT NULL,
  `amphetamine_screen` varchar(12) NOT NULL,
  `cannabinoids_screen` varchar(12) NOT NULL,
  `cocaine_screen` varchar(12) NOT NULL,
  `opiates_screen` varchar(12) NOT NULL,
  `phencyclidine_screen` varchar(12) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) NOT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` tinyint(3) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `drug_narcotis_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_drug_narcotis_autostamp` AFTER INSERT ON `drug_narcotis_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO drug_narcotis_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_narcotis_vendor_log`
--

CREATE TABLE `drug_narcotis_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `drug_narcotis_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_drugs_reject_vendor_assigned` AFTER UPDATE ON `drug_narcotis_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE drug_narcotis set vendor_id = 0 WHERE drug_narcotis.id = NEW.case_id;
        INSERT INTO drug_narcotis_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('0','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.modified_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO drug_narcotis_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('0','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.modified_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_drugs_vendor_assign` BEFORE INSERT ON `drug_narcotis_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO drug_narcotis_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('0','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_narcotis_ver_result`
--

CREATE TABLE `drug_narcotis_ver_result` (
  `id` int(10) UNSIGNED NOT NULL,
  `drug_narcotis_id` int(10) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(40) DEFAULT NULL,
  `closuredate` date DEFAULT NULL,
  `remarks` text NOT NULL,
  `amphetamine_screen` varchar(12) NOT NULL,
  `cannabinoids_screen` varchar(12) NOT NULL,
  `cocaine_screen` varchar(12) NOT NULL,
  `opiates_screen` varchar(12) NOT NULL,
  `phencyclidine_screen` varchar(12) NOT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `first_qc_approve` varchar(25) NOT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` tinyint(3) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `education_com_ref` varchar(50) NOT NULL,
  `school_college` varchar(100) DEFAULT NULL,
  `university_board` varchar(100) DEFAULT NULL,
  `grade_class_marks` varchar(50) DEFAULT NULL,
  `qualification` varchar(60) DEFAULT NULL,
  `major` varchar(50) DEFAULT NULL,
  `course_start_date` date DEFAULT NULL,
  `course_end_date` date DEFAULT NULL,
  `month_of_passing` varchar(10) DEFAULT NULL,
  `year_of_passing` varchar(6) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `enrollment_no` varchar(20) DEFAULT NULL,
  `PRN_no` varchar(20) DEFAULT NULL,
  `documents_provided` varchar(40) DEFAULT NULL,
  `genuineness` varchar(20) DEFAULT NULL,
  `online_URL` varchar(100) DEFAULT NULL,
  `iniated_date` date NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(35) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `edu_re_open_date` varchar(100) DEFAULT NULL,
  `edu_reinitiated_remark` varchar(500) NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `vendor_stamp_id` int(11) DEFAULT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `verifiers_spoc_status` tinyint(2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) DEFAULT '0' COMMENT ' 	0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `education`
--
DELIMITER $$
CREATE TRIGGER `tgr_education_new_check` AFTER INSERT ON `education` FOR EACH ROW BEGIN
DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO education_result (verfstatus,var_filter_status,var_report_status,clientid,candsid,education_id,res_qualification,res_school_college,res_university_board,res_major,res_month_of_passing,res_year_of_passing,res_grade_class_marks,res_course_start_date,res_course_end_date,res_roll_no,res_enrollment_no,res_PRN_no,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NEW.qualification,NEW.school_college,NEW.university_board,NEW.major,NEW.month_of_passing,NEW.year_of_passing,NEW.grade_class_marks,NEW.course_start_date,NEW.course_end_date,NEW.roll_no,NEW.enrollment_no,NEW.PRN_no,NEW.created_on,is_created_by,is_created_by);
                
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `education_activity_data`
--

CREATE TABLE `education_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `education_files`
--

CREATE TABLE `education_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `education_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `education_insuff`
--

CREATE TABLE `education_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `education_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `education_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_education_insuff_details_update` AFTER UPDATE ON `education_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO education_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.education_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE education_result SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE education_id  = NEW.education_id;
    	INSERT INTO education_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.education_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
     UPDATE education_result SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE education_id  = NEW.education_id;
  INSERT INTO education_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.education_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_education_insuff_raised` AFTER INSERT ON `education_insuff` FOR EACH ROW BEGIN
UPDATE education_result SET verfstatus = 18, var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE education_id  = NEW.education_id;
INSERT INTO education_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.education_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `education_mail_details`
--

CREATE TABLE `education_mail_details` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `to_mail_id` varchar(500) DEFAULT NULL,
  `cc_mail_id` varchar(500) DEFAULT NULL,
  `education_id` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `education_result`
--

CREATE TABLE `education_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `education_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `res_qualification` varchar(50) DEFAULT NULL,
  `res_school_college` varchar(50) DEFAULT NULL,
  `res_university_board` varchar(50) DEFAULT NULL,
  `res_major` varchar(50) DEFAULT NULL,
  `res_month_of_passing` varchar(50) DEFAULT NULL,
  `res_year_of_passing` varchar(50) DEFAULT NULL,
  `res_grade_class_marks` varchar(20) DEFAULT NULL,
  `res_course_start_date` date DEFAULT NULL,
  `res_course_end_date` date DEFAULT NULL,
  `res_roll_no` varchar(20) DEFAULT NULL,
  `res_enrollment_no` varchar(20) DEFAULT NULL,
  `res_PRN_no` varchar(20) DEFAULT NULL,
  `res_mode_of_verification` varchar(30) DEFAULT NULL,
  `res_online_URL` varchar(100) DEFAULT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `verified_by` varchar(25) DEFAULT NULL,
  `verifier_designation` varchar(25) DEFAULT NULL,
  `verifier_contact_details` varchar(12) DEFAULT NULL,
  `res_genuineness` varchar(20) DEFAULT NULL,
  `remarks` text,
  `activity_log_id` int(11) DEFAULT NULL,
  `qualification_action` varchar(50) DEFAULT NULL,
  `school_college_action` varchar(50) DEFAULT NULL,
  `university_board_action` varchar(50) DEFAULT NULL,
  `major_action` varchar(50) DEFAULT NULL,
  `month_of_passing_action` varchar(50) DEFAULT NULL,
  `year_of_passing_action` varchar(50) DEFAULT NULL,
  `grade_class_marks_action` varchar(50) DEFAULT NULL,
  `course_start_date_action` varchar(50) DEFAULT NULL,
  `course_end_date_action` varchar(50) DEFAULT NULL,
  `roll_no_action` varchar(50) DEFAULT NULL,
  `enrollment_no_action` varchar(50) DEFAULT NULL,
  `prn_no_action` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `education_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_education_autostamp` AFTER INSERT ON `education_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO education_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.education_id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `education_url_details`
--

CREATE TABLE `education_url_details` (
  `id` int(11) NOT NULL,
  `education_id` int(11) DEFAULT NULL,
  `university_name` int(11) DEFAULT NULL,
  `qualification_name` int(11) DEFAULT NULL,
  `year_of_passing` varchar(20) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `cancel_by` int(11) DEFAULT NULL,
  `cancel_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `education_vendor_log`
--

CREATE TABLE `education_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `education_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_education_reject_vendor_assigned` AFTER UPDATE ON `education_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE education set vendor_id = 0 WHERE education.id = NEW.case_id;
    INSERT INTO education_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.created_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO education_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.created_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_education_vendor_assign` BEFORE INSERT ON `education_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO education_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `education_ver_result`
--

CREATE TABLE `education_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `education_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `res_qualification` varchar(50) DEFAULT NULL,
  `res_school_college` varchar(50) DEFAULT NULL,
  `res_university_board` varchar(50) DEFAULT NULL,
  `res_major` varchar(50) DEFAULT NULL,
  `res_month_of_passing` varchar(50) DEFAULT NULL,
  `res_year_of_passing` varchar(50) DEFAULT NULL,
  `res_grade_class_marks` varchar(20) DEFAULT NULL,
  `res_course_start_date` date DEFAULT NULL,
  `res_course_end_date` date DEFAULT NULL,
  `res_roll_no` varchar(20) DEFAULT NULL,
  `res_enrollment_no` varchar(20) DEFAULT NULL,
  `res_PRN_no` varchar(20) DEFAULT NULL,
  `res_mode_of_verification` varchar(30) DEFAULT NULL,
  `res_online_URL` varchar(100) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `verified_by` varchar(25) DEFAULT NULL,
  `verifier_designation` varchar(25) DEFAULT NULL,
  `verifier_contact_details` varchar(12) DEFAULT NULL,
  `res_genuineness` varchar(20) DEFAULT NULL,
  `remarks` text,
  `activity_log_id` int(11) DEFAULT NULL,
  `qualification_action` varchar(50) DEFAULT NULL,
  `school_college_action` varchar(50) DEFAULT NULL,
  `university_board_action` varchar(50) DEFAULT NULL,
  `major_action` varchar(50) DEFAULT NULL,
  `month_of_passing_action` varchar(50) DEFAULT NULL,
  `year_of_passing_action` varchar(50) DEFAULT NULL,
  `grade_class_marks_action` varchar(50) DEFAULT NULL,
  `course_start_date_action` varchar(50) DEFAULT NULL,
  `course_end_date_action` varchar(50) DEFAULT NULL,
  `roll_no_action` varchar(50) DEFAULT NULL,
  `enrollment_no_action` varchar(50) DEFAULT NULL,
  `prn_no_action` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employment_vendor_log`
--

CREATE TABLE `employment_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `employment_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_employment_reject_vendor_assigned` AFTER UPDATE ON `employment_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE empver set vendor_id = 0 WHERE empver.id = NEW.case_id;
        INSERT INTO empver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.created_on,NEW.modified_by,0);

    END IF;
    
    IF NEW.status = 1 THEN
    INSERT INTO empver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.created_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_employment_vendor_assign` BEFORE INSERT ON `employment_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO empver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `empver`
--

CREATE TABLE `empver` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `emp_com_ref` varchar(50) NOT NULL,
  `empid` varchar(50) DEFAULT NULL,
  `nameofthecompany` int(10) UNSIGNED NOT NULL,
  `deputed_company` varchar(60) DEFAULT NULL,
  `employment_type` varchar(15) DEFAULT NULL,
  `locationaddr` varchar(255) DEFAULT NULL,
  `citylocality` varchar(100) DEFAULT NULL,
  `pincode` varchar(6) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `compant_contact` varchar(13) DEFAULT NULL,
  `compant_contact_name` varchar(50) DEFAULT NULL,
  `compant_contact_email` varchar(50) DEFAULT NULL,
  `compant_contact_designation` varchar(50) DEFAULT NULL,
  `empfrom` varchar(50) DEFAULT NULL,
  `empto` varchar(50) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `remuneration` varchar(50) DEFAULT NULL,
  `clientid` int(10) UNSIGNED DEFAULT NULL,
  `iniated_date` date DEFAULT NULL,
  `r_manager_name` varchar(50) DEFAULT NULL,
  `r_manager_no` varchar(12) DEFAULT NULL,
  `r_manager_designation` varchar(50) DEFAULT NULL,
  `r_manager_email` varchar(50) DEFAULT NULL,
  `mail_sent_status` varchar(2) NOT NULL DEFAULT '0',
  `mail_sent_addrs` varchar(250) DEFAULT NULL,
  `mail_sent_on` timestamp NULL DEFAULT NULL,
  `reasonforleaving` text,
  `uan_no` varchar(200) DEFAULT NULL,
  `uan_remark` varchar(500) DEFAULT NULL,
  `has_assigned_on` datetime DEFAULT NULL,
  `has_case_id` bigint(20) UNSIGNED NOT NULL,
  `field_visit_status` varchar(50) DEFAULT NULL,
  `field_visit_additional_remark` varchar(500) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` datetime DEFAULT NULL,
  `emp_re_open_date` varchar(100) DEFAULT NULL,
  `emp_reinitiated_remark` varchar(500) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_bulk_uploaded` tinyint(4) DEFAULT '0',
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `reject_status` tinyint(4) NOT NULL DEFAULT '1',
  `fill_by` tinyint(4) DEFAULT '0' COMMENT ' 	0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `empver`
--
DELIMITER $$
CREATE TRIGGER `tgr_empver_logs` AFTER UPDATE ON `empver` FOR EACH ROW INSERT INTO empver_logs (empver_id,candsid,emp_com_ref,empid,nameofthecompany,deputed_company,employment_type,locationaddr,citylocality,pincode,state,compant_contact,empfrom,empto,designation,remuneration,clientid,iniated_date,mail_sent_status,mail_sent_addrs,mail_sent_on,reasonforleaving,has_assigned_on,has_case_id,created_by,created_on)  
VALUES(OLD.id,candsid,OLD.emp_com_ref,OLD.empid,OLD.nameofthecompany,OLD.deputed_company,OLD.employment_type,OLD.locationaddr,OLD.citylocality,OLD.pincode,OLD.state,OLD.compant_contact,OLD.empfrom,OLD.empto,OLD.designation,OLD.remuneration,OLD.clientid,OLD.iniated_date,OLD.mail_sent_status,OLD.mail_sent_addrs,OLD.mail_sent_on,OLD.reasonforleaving,OLD.has_assigned_on,OLD.has_case_id,OLD.created_by,OLD.created_on)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_new_check` AFTER INSERT ON `empver` FOR EACH ROW BEGIN
DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO empverres (verfstatus,var_filter_status,var_report_status,clientid,candsid,empverid,res_nameofthecompany,res_deputed_company,res_employment_type,employed_from,employed_to,emp_designation,res_empid,res_reasonforleaving,res_remuneration,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NEW.nameofthecompany,NEW.deputed_company,NEW.employment_type,NEW.empfrom,NEW.empto,NEW.designation,NEW.empid,NEW.reasonforleaving,NEW.remuneration,NEW.created_on,is_created_by,NEW.is_bulk_uploaded);
                
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `empverres`
--

CREATE TABLE `empverres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `closuredate` date DEFAULT NULL,
  `clientid` int(10) UNSIGNED DEFAULT NULL,
  `candsid` bigint(20) UNSIGNED DEFAULT NULL,
  `empverid` bigint(20) UNSIGNED DEFAULT NULL,
  `res_nameofthecompany` int(10) UNSIGNED NOT NULL,
  `res_deputed_company` varchar(60) DEFAULT NULL,
  `res_employment_type` varchar(25) DEFAULT NULL,
  `employed_from` varchar(50) DEFAULT NULL,
  `employed_to` varchar(50) DEFAULT NULL,
  `emp_designation` varchar(50) DEFAULT NULL,
  `res_empid` varchar(50) DEFAULT NULL,
  `reportingmanager` varchar(255) DEFAULT NULL,
  `res_reasonforleaving` varchar(255) DEFAULT NULL,
  `res_remuneration` varchar(50) DEFAULT NULL,
  `info_integrity_disciplinary_issue` varchar(50) DEFAULT NULL,
  `info_exitformalities` varchar(50) DEFAULT NULL,
  `info_eligforrehire` varchar(50) DEFAULT NULL,
  `integrity_disciplinary_issue` varchar(255) DEFAULT NULL,
  `exitformalities` varchar(255) DEFAULT NULL,
  `eligforrehire` varchar(255) DEFAULT NULL,
  `fmlyowned` varchar(50) DEFAULT NULL,
  `executive_name` varchar(60) DEFAULT NULL,
  `modeofverification` varchar(20) DEFAULT NULL,
  `remarks` varchar(2000) DEFAULT NULL,
  `verifiers_role` varchar(15) DEFAULT NULL,
  `verfname` varchar(255) DEFAULT NULL,
  `verfdesgn` varchar(100) DEFAULT NULL,
  `verifiers_contact_no` varchar(20) DEFAULT NULL,
  `verifiers_email_id` varchar(50) DEFAULT NULL,
  `justdialwebcheck` varchar(50) DEFAULT NULL,
  `mcaregn` varchar(50) DEFAULT NULL,
  `domainname` varchar(100) DEFAULT NULL,
  `domainpurch` varchar(10) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) NOT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_updated_by` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `final_qc_status` varchar(50) DEFAULT NULL,
  `final_qc_reject_resoan` varchar(250) DEFAULT NULL,
  `final_qc_approved_on` timestamp NULL DEFAULT NULL,
  `final_qc_approved_by` int(10) UNSIGNED NOT NULL,
  `qc_status` varchar(100) DEFAULT NULL,
  `is_bulk_uploaded` tinyint(3) UNSIGNED NOT NULL,
  `activity_log_id` int(11) DEFAULT NULL,
  `nameofthecompany_action` varchar(50) DEFAULT NULL,
  `deputed_company_action` varchar(50) DEFAULT NULL,
  `employment_type_action` varchar(50) DEFAULT NULL,
  `employed_from_action` varchar(50) DEFAULT NULL,
  `employed_to_action` varchar(50) DEFAULT NULL,
  `emp_designation_action` varchar(50) DEFAULT NULL,
  `empid_action` varchar(50) DEFAULT NULL,
  `reportingmanager_action` varchar(50) DEFAULT NULL,
  `reasonforleaving_action` varchar(50) DEFAULT NULL,
  `remuneration_action` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `empverres`
--
DELIMITER $$
CREATE TRIGGER `tgr_auto_stamp` AFTER INSERT ON `empverres` FOR EACH ROW BEGIN
DECLARE remark_by varchar(100);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO empver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.empverid,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `empverres_files`
--

CREATE TABLE `empverres_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `empver_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `empverres_insuff`
--

CREATE TABLE `empverres_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `empverres_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete 4=update',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `empverres_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_emp_insuff_details_update` AFTER UPDATE ON `empverres_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
  INSERT INTO empver_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.empverres_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE empverres SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE empverid  = NEW.empverres_id;
    	INSERT INTO empver_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.empverres_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
    UPDATE empverres SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE empverid  = NEW.empverres_id;
  INSERT INTO empver_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.empverres_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_employment_insuff_raised` AFTER INSERT ON `empverres_insuff` FOR EACH ROW BEGIN
UPDATE empverres SET verfstatus = 18,var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE `empverid` = NEW.empverres_id;
INSERT INTO empver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.empverres_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `empverres_logs`
--

CREATE TABLE `empverres_logs` (
  `sr_id` int(11) NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `closuredate` date DEFAULT NULL,
  `clientid` int(10) UNSIGNED DEFAULT NULL,
  `candsid` bigint(20) UNSIGNED DEFAULT NULL,
  `empverid` bigint(20) UNSIGNED DEFAULT NULL,
  `res_nameofthecompany` int(10) UNSIGNED NOT NULL,
  `res_deputed_company` varchar(60) NOT NULL,
  `res_employment_type` varchar(25) NOT NULL,
  `employed_from` varchar(25) DEFAULT NULL,
  `employed_to` varchar(25) DEFAULT NULL,
  `emp_designation` varchar(50) DEFAULT NULL,
  `res_empid` varchar(50) DEFAULT NULL,
  `reportingmanager` varchar(255) DEFAULT NULL,
  `res_reasonforleaving` varchar(255) DEFAULT NULL,
  `res_remuneration` varchar(50) DEFAULT NULL,
  `info_integrity_disciplinary_issue` varchar(50) DEFAULT NULL,
  `info_exitformalities` varchar(50) DEFAULT NULL,
  `info_eligforrehire` varchar(50) DEFAULT NULL,
  `integrity_disciplinary_issue` varchar(255) DEFAULT NULL,
  `exitformalities` varchar(255) DEFAULT NULL,
  `eligforrehire` varchar(255) DEFAULT NULL,
  `fmlyowned` varchar(50) DEFAULT NULL,
  `justdialwebcheck` varchar(50) DEFAULT NULL,
  `mcaregn` varchar(50) DEFAULT NULL,
  `domainname` varchar(100) DEFAULT NULL,
  `domainpurch` varchar(10) DEFAULT NULL,
  `executive_name` varchar(60) DEFAULT NULL,
  `modeofverification` varchar(20) DEFAULT NULL,
  `remarks` varchar(2000) DEFAULT NULL,
  `verifiers_role` varchar(15) DEFAULT NULL,
  `verfname` varchar(255) DEFAULT NULL,
  `verfdesgn` varchar(100) DEFAULT NULL,
  `verifiers_contact_no` varchar(20) DEFAULT NULL,
  `verifiers_email_id` varchar(50) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) NOT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_updated_by` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `final_qc_status` varchar(50) DEFAULT NULL,
  `final_qc_reject_resoan` varchar(250) DEFAULT NULL,
  `final_qc_approved_on` timestamp NULL DEFAULT NULL,
  `final_qc_approved_by` int(10) UNSIGNED NOT NULL,
  `qc_status` varchar(100) DEFAULT NULL,
  `is_bulk_uploaded` tinyint(3) UNSIGNED NOT NULL,
  `activity_log_id` int(11) DEFAULT NULL,
  `nameofthecompany_action` varchar(50) DEFAULT NULL,
  `deputed_company_action` varchar(50) DEFAULT NULL,
  `employment_type_action` varchar(50) DEFAULT NULL,
  `employed_from_action` varchar(50) DEFAULT NULL,
  `employed_to_action` varchar(50) DEFAULT NULL,
  `emp_designation_action` varchar(50) DEFAULT NULL,
  `empid_action` varchar(50) DEFAULT NULL,
  `reportingmanager_action` varchar(50) DEFAULT NULL,
  `reasonforleaving_action` varchar(50) DEFAULT NULL,
  `remuneration_action` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `empver_activity_data`
--

CREATE TABLE `empver_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `empver_logs`
--

CREATE TABLE `empver_logs` (
  `id` int(11) NOT NULL,
  `empver_id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `emp_com_ref` varchar(100) DEFAULT NULL,
  `empid` varchar(50) DEFAULT NULL,
  `nameofthecompany` int(10) UNSIGNED DEFAULT NULL,
  `deputed_company` varchar(60) DEFAULT NULL,
  `employment_type` varchar(15) DEFAULT NULL,
  `locationaddr` varchar(255) DEFAULT NULL,
  `citylocality` varchar(100) DEFAULT NULL,
  `pincode` varchar(6) DEFAULT NULL,
  `state` int(10) UNSIGNED DEFAULT NULL,
  `compant_contact` varchar(13) DEFAULT NULL,
  `empfrom` varchar(25) DEFAULT NULL,
  `empto` varchar(25) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `remuneration` varchar(50) DEFAULT NULL,
  `clientid` int(10) UNSIGNED DEFAULT NULL,
  `iniated_date` date DEFAULT NULL,
  `mail_sent_status` varchar(2) NOT NULL DEFAULT '0',
  `mail_sent_addrs` varchar(250) DEFAULT NULL,
  `mail_sent_on` timestamp NULL DEFAULT NULL,
  `reasonforleaving` text,
  `has_assigned_on` datetime DEFAULT NULL,
  `has_case_id` bigint(20) UNSIGNED DEFAULT NULL,
  `emp_re_open_date` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `empver_supervisor_details`
--

CREATE TABLE `empver_supervisor_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `empver_id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_name` varchar(50) NOT NULL,
  `supervisor_designation` varchar(50) NOT NULL,
  `supervisor_contact_details` varchar(15) NOT NULL,
  `supervisor_email_id` varchar(50) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emp_mail_details`
--

CREATE TABLE `emp_mail_details` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `from_email_id` varchar(500) DEFAULT NULL,
  `to_mail_id` varchar(500) DEFAULT NULL,
  `cc_mail_id` varchar(500) DEFAULT NULL,
  `empver_id` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `entity_package`
--

CREATE TABLE `entity_package` (
  `id` int(10) UNSIGNED NOT NULL,
  `tbl_client_id` int(10) UNSIGNED NOT NULL,
  `entity_package_name` varchar(100) NOT NULL,
  `is_entity` mediumint(8) UNSIGNED NOT NULL,
  `is_entity_package` mediumint(9) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fake_university`
--

CREATE TABLE `fake_university` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `u_name` text NOT NULL,
  `u_address` text NOT NULL,
  `u_state` text NOT NULL,
  `u_url` varchar(1000) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '1 = active 0 = deactive '
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `glodbver`
--

CREATE TABLE `glodbver` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `global_com_ref` varchar(50) NOT NULL,
  `glodbver_re_open_date` varchar(100) DEFAULT NULL,
  `glodbver_reinitiated_remark` varchar(500) DEFAULT NULL,
  `iniated_date` date NOT NULL,
  `address_type` varchar(50) NOT NULL,
  `street_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(50) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 	0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `glodbver`
--
DELIMITER $$
CREATE TRIGGER `tgr_globaldb_new_check` AFTER INSERT ON `glodbver` FOR EACH ROW BEGIN
DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO glodbver_result (verfstatus,var_filter_status,var_report_status,clientid,candsid,glodbver_id,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NEW.created_on,is_created_by,is_created_by);
                
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `glodbver_activity_data`
--

CREATE TABLE `glodbver_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `glodbver_files`
--

CREATE TABLE `glodbver_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `glodbver_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `glodbver_insuff`
--

CREATE TABLE `glodbver_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `glodbver_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `glodbver_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_global_insuff_raised` BEFORE INSERT ON `glodbver_insuff` FOR EACH ROW BEGIN
UPDATE glodbver_result SET verfstatus = 18,var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE glodbver_id  = NEW.glodbver_id;
INSERT INTO glodbver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.glodbver_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_glodbver_insuff_details_update` AFTER UPDATE ON `glodbver_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO glodbver_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.glodbver_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE glodbver_result SET verfstatus = 11, var_filter_status ='WIP',var_report_status='WIP' WHERE glodbver_id  = NEW.glodbver_id;
    	INSERT INTO glodbver_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.glodbver_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
    UPDATE glodbver_result SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE glodbver_id  = NEW.glodbver_id;
  INSERT INTO glodbver_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.glodbver_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `glodbver_result`
--

CREATE TABLE `glodbver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `glodbver_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `verified_by` varchar(30) NOT NULL,
  `verified_date` date DEFAULT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `glodbver_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_glodbver_new_check` AFTER INSERT ON `glodbver_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO glodbver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `glodbver_vendor_log`
--

CREATE TABLE `glodbver_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `glodbver_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_global_reject_vendor_assigned` AFTER UPDATE ON `glodbver_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE glodbver set vendor_id = 0 WHERE glodbver.id = NEW.case_id;
    INSERT INTO glodbver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.created_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO glodbver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.created_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_glodbver_vendor_assign` BEFORE INSERT ON `glodbver_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO glodbver_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `glodbver_ver_result`
--

CREATE TABLE `glodbver_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `glodbver_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `verified_by` varchar(30) NOT NULL,
  `verified_date` date DEFAULT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `reporting_manager` varchar(100) NOT NULL,
  `group_name` varchar(30) NOT NULL,
  `tbl_admin_menu_id` varchar(300) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `status` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holiday_dates`
--

CREATE TABLE `holiday_dates` (
  `id` int(10) UNSIGNED NOT NULL,
  `holiday_date` date NOT NULL,
  `remark` varchar(250) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `identity`
--

CREATE TABLE `identity` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `identity_com_ref` varchar(50) NOT NULL,
  `iniated_date` date NOT NULL,
  `doc_submited` varchar(50) DEFAULT NULL,
  `id_number` varchar(40) DEFAULT NULL,
  `street_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(35) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `identity_re_open_date` varchar(500) DEFAULT NULL,
  `identity_reinitiated_remark` varchar(500) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `identity`
--
DELIMITER $$
CREATE TRIGGER `tgr_identiy_new_check` AFTER INSERT ON `identity` FOR EACH ROW BEGIN

DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO identity_result(verfstatus,var_filter_status,var_report_status,clientid,candsid,identity_id,closuredate,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NULL,NEW.created_on,is_created_by,is_created_by);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `identity_activity_data`
--

CREATE TABLE `identity_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `identity_files`
--

CREATE TABLE `identity_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `identity_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `identity_insuff`
--

CREATE TABLE `identity_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `identity_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `identity_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_identity_insuff_details_update` AFTER UPDATE ON `identity_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO identity_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.identity_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE identity_result SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE identity_id  = NEW.identity_id;
    	INSERT INTO identity_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.identity_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
    UPDATE identity_result SET verfstatus = 1,var_filter_status	='WIP',var_report_status='WIP' WHERE identity_id = NEW.identity_id;
  INSERT INTO identity_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.identity_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_identity_result_insuff_raised` BEFORE INSERT ON `identity_insuff` FOR EACH ROW BEGIN
UPDATE identity_result SET verfstatus = 18, var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE identity_id  = NEW.identity_id;
INSERT INTO identity_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.identity_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `identity_result`
--

CREATE TABLE `identity_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `identity_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `identity_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_identity_autostamp` AFTER INSERT ON `identity_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO identity_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `identity_vendor_log`
--

CREATE TABLE `identity_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `identity_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_identity_reject_vendor_assigned	` AFTER UPDATE ON `identity_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE identity set vendor_id = 0 WHERE identity.id = NEW.case_id;
        INSERT INTO identity_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.created_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO identity_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mit it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.created_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_identity_vendor_assign` BEFORE INSERT ON `identity_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO identity_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `identity_ver_result`
--

CREATE TABLE `identity_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `identity_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `location_master`
--

CREATE TABLE `location_master` (
  `id` int(10) UNSIGNED NOT NULL,
  `location` varchar(100) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `initiated_data` varchar(10) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mail_sms_details`
--

CREATE TABLE `mail_sms_details` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `type` tinyint(2) DEFAULT NULL COMMENT '1 = mail 2 = sms',
  `mail_sms_send` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pcc`
--

CREATE TABLE `pcc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `pcc_com_ref` varchar(50) NOT NULL,
  `pcc_re_open_date` varchar(100) DEFAULT NULL,
  `pcc_reinitiated_remark` varchar(500) DEFAULT NULL,
  `iniated_date` date NOT NULL,
  `address_type` varchar(50) NOT NULL,
  `street_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(35) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `references` text,
  `references_no` text,
  `vendor_id` int(11) NOT NULL,
  `vendor_list_mode` varchar(50) DEFAULT NULL,
  `vendor_assgined_on` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `pcc`
--
DELIMITER $$
CREATE TRIGGER `tgr_pcc_new_check` AFTER INSERT ON `pcc` FOR EACH ROW BEGIN

DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO pcc_result(verfstatus,var_filter_status,var_report_status,clientid,candsid,pcc_id,closuredate,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NULL,NEW.created_on,is_created_by,is_created_by);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pcc_activity_data`
--

CREATE TABLE `pcc_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pcc_files`
--

CREATE TABLE `pcc_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `pcc_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pcc_insuff`
--

CREATE TABLE `pcc_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `pcc_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `pcc_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_pcc_insuff_raised` BEFORE INSERT ON `pcc_insuff` FOR EACH ROW BEGIN
UPDATE pcc_result SET verfstatus = 18, var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE pcc_id  = NEW.pcc_id;
INSERT INTO pcc_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.pcc_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_pcc_insuff_update` AFTER UPDATE ON `pcc_insuff` FOR EACH ROW BEGIN
	IF  NEW.status = 4 THEN
    	INSERT INTO pcc_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.pcc_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
    IF NEW.status = 2 THEN
    UPDATE pcc_result SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE pcc_id  = NEW.pcc_id;
    	INSERT INTO pcc_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.pcc_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
     UPDATE pcc_result SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE pcc_id  = NEW.pcc_id;
  INSERT INTO pcc_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.pcc_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pcc_result`
--

CREATE TABLE `pcc_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `pcc_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `application_id_ref` varchar(40) DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `police_station` varchar(100) DEFAULT NULL,
  `police_station_visit_date` date DEFAULT NULL,
  `name_designation_police` varchar(40) DEFAULT NULL,
  `contact_number_police` varchar(14) DEFAULT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `pcc_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_pcc_autostamp` AFTER INSERT ON `pcc_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO pcc_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pcc_vendor_log`
--

CREATE TABLE `pcc_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `pcc_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_pcc_reject_vendor_assigned` AFTER UPDATE ON `pcc_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE pcc set vendor_id = 0 WHERE pcc.id = NEW.case_id;
        
     INSERT INTO pcc_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Rejected','Mist it',NULL,NULL,(SELECT concat(user_name,' has  rejected the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id ), ' stating ' ,NEW.remarks ) as user_name  FROM user_profile WHERE id  = NEW.modified_by ),NEW.created_on,NEW.modified_by,0);

    END IF;
    
   
    IF NEW.status = 1 THEN
  INSERT INTO pcc_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Approve','Mist it',NULL,NULL,(SELECT concat(user_name,' has approved the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.approval_by ),NEW.created_on,NEW.approval_by,0);


    END IF;
    
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_pcc_vendor_assign` BEFORE INSERT ON `pcc_vendor_log` FOR EACH ROW BEGIN
IF NEW.status = 0 THEN
INSERT INTO pcc_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.case_id,NULL,'Assign','Mist it',NULL,NULL,(SELECT concat(user_name,' has assigned the case to ',(SELECT 
vendor_name as vendor_name FROM vendors WHERE id  = NEW.vendor_id )) as user_name  FROM user_profile WHERE id  = NEW.created_by ),NEW.created_on,NEW.created_by,0);

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pcc_ver_result`
--

CREATE TABLE `pcc_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `pcc_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `application_id_ref` varchar(40) DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `police_station` varchar(100) DEFAULT NULL,
  `police_station_visit_date` date DEFAULT NULL,
  `name_designation_police` varchar(40) DEFAULT NULL,
  `contact_number_police` varchar(14) DEFAULT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_post_details`
--

CREATE TABLE `pre_post_details` (
  `id` int(11) NOT NULL,
  `type` tinyint(2) DEFAULT NULL,
  `task_manager_id` int(11) DEFAULT NULL,
  `task_manager_id_post` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `entity` int(11) DEFAULT NULL,
  `package` int(11) DEFAULT NULL,
  `initiation_date` date DEFAULT NULL,
  `post_initiation_date` date DEFAULT NULL,
  `client_ref_no` varchar(50) DEFAULT NULL,
  `component_ref_no` varchar(50) DEFAULT NULL,
  `candidate_name` varchar(100) DEFAULT NULL,
  `primary_contact` varchar(50) DEFAULT NULL,
  `contact_two` varchar(50) DEFAULT NULL,
  `contact_three` varchar(50) DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `remarks_post` varchar(500) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'processing',
  `status_post` varchar(100) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qc_status`
--

CREATE TABLE `qc_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `component` tinyint(3) UNSIGNED NOT NULL,
  `component_id` int(10) UNSIGNED NOT NULL,
  `first_qc_status` varchar(250) NOT NULL,
  `first_qc_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `final_qc_status` varchar(250) DEFAULT NULL,
  `final_qc_timestamp` timestamp NULL DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qualification_master`
--

CREATE TABLE `qualification_master` (
  `id` int(10) UNSIGNED NOT NULL,
  `qualification` varchar(500) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `raising_insuff_dropdown`
--

CREATE TABLE `raising_insuff_dropdown` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `component_id` int(10) UNSIGNED NOT NULL,
  `reason` varchar(250) NOT NULL,
  `remarks` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE `reference` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `reference_com_ref` varchar(50) NOT NULL,
  `iniated_date` date NOT NULL,
  `name_of_reference` varchar(50) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `contact_no` varchar(13) NOT NULL,
  `contact_no_first` varchar(13) DEFAULT NULL,
  `contact_no_second` varchar(13) DEFAULT NULL,
  `email_id` varchar(40) NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `reference_re_open_date` varchar(100) DEFAULT NULL,
  `reference_reinitiated_remark` varchar(500) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `vendor_assgined_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fill_by` tinyint(4) DEFAULT '0' COMMENT '0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `reference`
--
DELIMITER $$
CREATE TRIGGER `tgr_reference_new_check` AFTER INSERT ON `reference` FOR EACH ROW BEGIN
DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO reference_result (verfstatus,var_filter_status,var_report_status,clientid,candsid,reference_id,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NEW.created_on,is_created_by,is_created_by);
                
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reference_activity_data`
--

CREATE TABLE `reference_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reference_files`
--

CREATE TABLE `reference_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reference_insuff`
--

CREATE TABLE `reference_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `reference_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_reference_insuff_details_update` AFTER UPDATE ON `reference_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO reference_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.reference_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE reference_result SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE reference_id = NEW.reference_id;
    	INSERT INTO reference_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.reference_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
       UPDATE reference_result SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE reference_id = NEW.reference_id;
  INSERT INTO reference_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.reference_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_reference_insuff_raised` AFTER INSERT ON `reference_insuff` FOR EACH ROW BEGIN
UPDATE reference_result SET verfstatus = 18,  var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE reference_id = NEW.reference_id;
INSERT INTO reference_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.reference_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reference_result`
--

CREATE TABLE `reference_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `handle_pressure` tinyint(3) UNSIGNED NOT NULL,
  `handle_pressure_value` varchar(500) DEFAULT NULL,
  `attendance` tinyint(3) UNSIGNED NOT NULL,
  `attendance_value` varchar(500) DEFAULT NULL,
  `integrity` tinyint(3) UNSIGNED NOT NULL,
  `integrity_value` varchar(500) DEFAULT NULL,
  `leadership_skills` tinyint(3) UNSIGNED NOT NULL,
  `leadership_skills_value` varchar(500) DEFAULT NULL,
  `responsibilities` tinyint(3) UNSIGNED NOT NULL,
  `responsibilities_value` varchar(500) DEFAULT NULL,
  `achievements` tinyint(3) UNSIGNED NOT NULL,
  `achievements_value` varchar(500) DEFAULT NULL,
  `strengths` tinyint(3) UNSIGNED NOT NULL,
  `strengths_value` varchar(500) DEFAULT NULL,
  `team_player` tinyint(3) UNSIGNED NOT NULL,
  `team_player_value` varchar(500) DEFAULT NULL,
  `weakness` varchar(10) NOT NULL,
  `weakness_value` varchar(500) DEFAULT NULL,
  `overall_performance` varchar(10) DEFAULT NULL,
  `additional_comments` varchar(500) DEFAULT NULL,
  `mode_of_verification` varchar(30) DEFAULT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `reference_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_reference_autostamp` AFTER INSERT ON `reference_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO reference_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reference_vendor_log`
--

CREATE TABLE `reference_vendor_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `reference_vendor_log`
--
DELIMITER $$
CREATE TRIGGER `tgr_reference_reject_vendor_assigned` AFTER UPDATE ON `reference_vendor_log` FOR EACH ROW BEGIN
	IF NEW.status = 2 THEN
		UPDATE reference set vendor_id = 0 WHERE reference.id = NEW.case_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reference_ver_result`
--

CREATE TABLE `reference_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `handle_pressure` tinyint(3) UNSIGNED NOT NULL,
  `handle_pressure_value` varchar(250) DEFAULT NULL,
  `attendance` tinyint(3) UNSIGNED NOT NULL,
  `attendance_value` varchar(250) DEFAULT NULL,
  `integrity` tinyint(3) UNSIGNED NOT NULL,
  `integrity_value` varchar(250) DEFAULT NULL,
  `leadership_skills` tinyint(3) UNSIGNED NOT NULL,
  `leadership_skills_value` varchar(250) DEFAULT NULL,
  `responsibilities` tinyint(3) UNSIGNED NOT NULL,
  `responsibilities_value` varchar(250) DEFAULT NULL,
  `achievements` tinyint(3) UNSIGNED NOT NULL,
  `achievements_value` varchar(250) DEFAULT NULL,
  `strengths` tinyint(3) UNSIGNED NOT NULL,
  `strengths_value` varchar(250) DEFAULT NULL,
  `team_player` tinyint(3) UNSIGNED NOT NULL,
  `team_player_value` varchar(250) DEFAULT NULL,
  `weakness` varchar(10) NOT NULL,
  `weakness_value` varchar(250) DEFAULT NULL,
  `overall_performance` varchar(10) DEFAULT NULL,
  `additional_comments` varchar(500) DEFAULT NULL,
  `mode_of_verification` varchar(30) DEFAULT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ref_mail_details`
--

CREATE TABLE `ref_mail_details` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `to_mail_id` varchar(500) DEFAULT NULL,
  `cc_mail_id` varchar(500) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `report_generated_user`
--

CREATE TABLE `report_generated_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `query` text NOT NULL,
  `type` varchar(200) NOT NULL,
  `filter` text NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `report_requested`
--

CREATE TABLE `report_requested` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `requested_id` int(11) DEFAULT NULL,
  `file_name` varchar(500) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `mail_send_status` tinyint(4) DEFAULT NULL,
  `folder_generated_status` tinyint(4) DEFAULT NULL,
  `downloaded_status` tinyint(4) DEFAULT NULL,
  `downloaded_on_date` timestamp NULL DEFAULT NULL,
  `folder_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(40) NOT NULL,
  `groups_id` varchar(100) NOT NULL,
  `role_description` varchar(250) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `permissionID` int(10) UNSIGNED NOT NULL,
  `tbl_roles_id` int(10) UNSIGNED NOT NULL,
  `access_admin_list_view` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_admin_list_add` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_admin_list_edit` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_admin_list_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_admin_list_import` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_admin_list_export` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_role_view` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_role_add` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_role_edit` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_role_delete` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_role_import` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_role_export` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_group_view` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_group_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_admin_group_edit` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_group_delete` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_group_import` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_group_export` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_holiday_view` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_holiday_add` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_holiday_edit` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_holiday_delete` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_activity_view` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_activity_add` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_activity_edit` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_activity_delete` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_activity_import` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_activity_export` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_vendor_view` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_vendor_edit` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_vendor_approve` tinyint(1) NOT NULL DEFAULT '0',
  `access_admin_vendor_reject` tinyint(1) NOT NULL DEFAULT '0',
  `access_clients_list_view` tinyint(4) DEFAULT '0',
  `access_clients_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_clients_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_clients_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_clients_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_clients_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_special_acitivity_status` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_list_file_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_candidates_overall_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_assign_executive` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_re_assign_executive` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_list_insuff_delete` tinyint(4) DEFAULT NULL,
  `access_address_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_employment_visits_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_employment_visits_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_employment_visits_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_employment_visits_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_employment_visits_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_employment_visits_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_location_database_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_location_database_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_location_database_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_location_database_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_location_database_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_location_database_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_vendor_database_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_vendor_database_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_vendor_database_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_vendor_database_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_vendor_database_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_vendor_database_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_assign_add_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_address_assign_add_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_generic_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_initiation_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_followup_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_summary_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_list_insuff_delete` tinyint(1) NOT NULL,
  `access_employment_list_field_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_suspicious_company_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_suspicious_company_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_suspicious_company_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_suspicious_company_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_suspicious_company_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_suspicious_company_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_hr_database_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_hr_database_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_hr_database_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_hr_database_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_hr_database_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_hr_database_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_employment_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_initiation_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_followup_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_summary_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_list_insuff_delete` tinyint(1) NOT NULL,
  `access_education_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_universities_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_universities_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_universities_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_universities_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_universities_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_universities_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_fake_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_fake_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_fake_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_fake_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_fake_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_fake_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_assign_edu_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_education_assign_edu_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_initiation_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_followup_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_summary_email` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_reference_list_insuff_delete` tinyint(1) NOT NULL,
  `access_court_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_list_insuff_delete` tinyint(1) NOT NULL,
  `access_court_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_assign_court_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_court_assign_court_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_list_insuff_delete` tinyint(1) NOT NULL,
  `access_global_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_assign_global_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_global_assign_global_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_list_insuff_delete` tinyint(1) NOT NULL,
  `access_drugs_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_assign_drugs_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_drugs_assign_drugs_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_list_insuff_delete` tinyint(1) NOT NULL,
  `access_pcc_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_assign_pcc_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_pcc_assign_pcc_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_list_insuff_delete` tinyint(1) NOT NULL,
  `access_identity_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_assign_identity_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_identity_assign_identity_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_reverification` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_sla` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_activity` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_insuff_raise` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_insuff_clear` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_insuff_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_list_insuff_delete` tinyint(1) NOT NULL,
  `access_credit_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_aq_allow` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_assign_credit_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_credit_assign_credit_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_add` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_edit` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_delete` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_import` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_export` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_task_list_re_assign` tinyint(4) NOT NULL DEFAULT '0',
  `access_final_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_final_list_download` tinyint(4) NOT NULL DEFAULT '0',
  `access_final_list_approved` tinyint(4) NOT NULL DEFAULT '0',
  `access_final_aq_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_final_aq_status` tinyint(4) NOT NULL DEFAULT '0',
  `access_final_annexture_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_final_annexture_download` tinyint(4) NOT NULL DEFAULT '0',
  `access_report_list_user` tinyint(4) NOT NULL DEFAULT '0',
  `access_report_list_hourly` tinyint(4) NOT NULL DEFAULT '0',
  `access_report_list_cases` tinyint(4) NOT NULL DEFAULT '0',
  `access_report_list_client` tinyint(4) NOT NULL DEFAULT '0',
  `access_report_list_vendor` tinyint(4) NOT NULL DEFAULT '0',
  `access_report_list_aq` tinyint(4) DEFAULT '0',
  `access_report_schedule_list` tinyint(4) NOT NULL,
  `access_report_schedule_run` tinyint(4) NOT NULL,
  `access_report_cron_list` tinyint(4) NOT NULL DEFAULT '0',
  `access_report_cron_run` tinyint(4) NOT NULL DEFAULT '0',
  `access_social_media_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `access_social_media_list_add` tinyint(4) NOT NULL,
  `access_social_media_list_edit` tinyint(4) NOT NULL,
  `access_social_media_list_delete` tinyint(4) NOT NULL,
  `access_social_media_list_import` tinyint(4) NOT NULL,
  `access_social_media_list_export` tinyint(4) NOT NULL,
  `access_soical_media_list_assign` tinyint(4) NOT NULL,
  `access_soical_media_list_re_assign` tinyint(4) NOT NULL,
  `access_social_media_list_activity` tinyint(4) NOT NULL,
  `access_social_media_list_insuff_raise` tinyint(4) NOT NULL,
  `access_social_media_list_insuff_clear` tinyint(4) NOT NULL,
  `access_social_media_list_insuff_edit` tinyint(4) NOT NULL,
  `access_social_media_list_insuff_delete` tinyint(4) NOT NULL,
  `access_social_media_list_reverification` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_login`
--

CREATE TABLE `sales_login` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `username` varchar(35) DEFAULT NULL,
  `password` varchar(80) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `mobile_no` varchar(11) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `profile_pic` varchar(100) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `modified_by` datetime DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `pass_reset_key` varchar(80) DEFAULT NULL,
  `pass_reset_expiry` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scheduler_list`
--

CREATE TABLE `scheduler_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `report_name` varchar(200) NOT NULL,
  `date_range` varchar(200) DEFAULT NULL,
  `file_name` varchar(500) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `run_status` tinyint(4) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED DEFAULT NULL,
  `last_run_by` int(10) UNSIGNED DEFAULT NULL,
  `last_run_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scheduler_task`
--

CREATE TABLE `scheduler_task` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `report_name` varchar(200) NOT NULL,
  `schedule_time` time NOT NULL,
  `activity_days` varchar(100) NOT NULL,
  `portal_users` varchar(100) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `is_executive` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `executive_on` timestamp NULL DEFAULT NULL,
  `mail_sent` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sla_default_setting`
--

CREATE TABLE `sla_default_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `component_id` int(10) UNSIGNED NOT NULL,
  `particulars` varchar(350) NOT NULL,
  `section` varchar(550) NOT NULL,
  `selected_selection` varchar(150) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sla_setting`
--

CREATE TABLE `sla_setting` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `client_component` varchar(100) DEFAULT NULL,
  `question` varchar(5000) DEFAULT NULL,
  `selected_selection` varchar(200) DEFAULT NULL,
  `remarks` longtext,
  `status` tinyint(1) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(11) NOT NULL,
  `candsid` bigint(20) NOT NULL,
  `social_media_com_ref` varchar(50) NOT NULL,
  `iniated_date` date NOT NULL,
  `mode_of_veri` varchar(100) DEFAULT NULL,
  `has_case_id` bigint(20) NOT NULL,
  `has_assigned_on` timestamp NULL DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `is_bulk_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `social_media_re_open_date` varchar(500) DEFAULT NULL,
  `social_media_reinitiated_remark` varchar(500) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `build_date` varchar(50) DEFAULT NULL,
  `tat_status` varchar(15) DEFAULT NULL,
  `fill_by` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = Admin 1=Client 2=Candidate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `social_media`
--
DELIMITER $$
CREATE TRIGGER `tgr_social_media_new_check` AFTER INSERT ON `social_media` FOR EACH ROW BEGIN
DECLARE is_created_by integer;
IF NEW.is_bulk_uploaded = 0 THEN
	SET is_created_by = NEW.created_by;
ELSE 
	SET is_created_by = 1;
END IF;     
	INSERT INTO social_media_result (verfstatus,var_filter_status,var_report_status,clientid,candsid,social_media_id,created_on,created_by,is_bulk_uploaded)VALUES(14,'WIP','WIP',NEW.clientid,NEW.candsid,NEW.id,NEW.created_on,is_created_by,is_created_by);
                
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_activity_data`
--

CREATE TABLE `social_media_activity_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `ClientRefNumber` varchar(35) NOT NULL,
  `comp_table_id` bigint(20) UNSIGNED NOT NULL,
  `activity_mode` varchar(100) DEFAULT NULL,
  `activity_status` varchar(50) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `remarks` text,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_auto_filled` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_files`
--

CREATE TABLE `social_media_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `real_filename` varchar(150) NOT NULL,
  `social_media_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = data-entry || 1 = add-result || 2 = CS',
  `serialno` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_insuff`
--

CREATE TABLE `social_media_insuff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insuff_raised_date` date DEFAULT NULL,
  `insff_reason` varchar(250) DEFAULT NULL,
  `insuff_raise_remark` varchar(500) DEFAULT NULL,
  `insuff_clear_date` date DEFAULT NULL,
  `insuff_cleared_timestamp` timestamp NULL DEFAULT NULL,
  `insuff_cleared_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `insuff_remarks` text,
  `social_media_id` bigint(20) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '1 rasied 2 =cleared 3 = delete',
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `auto_stamp` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hold_days` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `social_media_insuff`
--
DELIMITER $$
CREATE TRIGGER `tgr_social_media_insuff_details_update` AFTER UPDATE ON `social_media_insuff` FOR EACH ROW BEGIN
	IF NEW.status = 4 THEN
    	INSERT INTO social_media_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.social_media_id,'','Insufficiency Details Updated','Edited','Insufficiency',NULL,'Insufficiency Details Updated',NEW.created_on,NEW.modified_by,1);
	END IF;
    
	IF NEW.status = 2 THEN
    UPDATE social_media_result SET verfstatus = 11,var_filter_status	='WIP',	var_report_status='WIP' WHERE social_media_id = NEW.social_media_id;
    	INSERT INTO social_media_activity_data 
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.social_media_id,'','Insufficiency cleared','Cleared','Insufficiency Cleared',NULL,(SELECT concat(' insuff clear date ', DATE_FORMAT(NEW.insuff_clear_date,'%d-%m-%Y'),' and remark ', NEW.insuff_remarks) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.insuff_cleared_by,1);
	END IF;
    
    IF NEW.status = 3 THEN
       UPDATE social_media_result SET verfstatus = 1,var_filter_status	='WIP',	var_report_status='WIP' WHERE social_media_id = NEW.social_media_id;
  INSERT INTO social_media_activity_data
(candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) 
VALUES ('','',NEW.social_media_id,'','Insufficiency Delete','Deleted','Insufficiency',NULL,(SELECT concat( 'Insuffciency deleted by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.modified_on, '%d-%m-%Y')) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.modified_by,1);
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tgr_social_media_insuff_raised` AFTER INSERT ON `social_media_insuff` FOR EACH ROW BEGIN
UPDATE social_media_result SET verfstatus = 18,  var_filter_status='Insufficiency',var_report_status ='Insufficiency' WHERE social_media_id = NEW.social_media_id;
INSERT INTO social_media_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES ('','',NEW.social_media_id,'','Insufficiency Raised','Insufficiency Raised','Insufficiency',NULL,(SELECT concat( 'Insuffciency raised by', ' ' ,user_name,' on ', DATE_FORMAT(NEW.insuff_raised_date, '%d-%m-%Y') ,' because ', NEW.insuff_raise_remark) as user_name  FROM user_profile WHERE id  = NEW.created_by),NEW.created_on,NEW.created_by,1);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_result`
--

CREATE TABLE `social_media_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `social_media_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `first_qu_updated_by` int(11) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `social_media_result`
--
DELIMITER $$
CREATE TRIGGER `tgr_social_media_autostamp` AFTER INSERT ON `social_media_result` FOR EACH ROW BEGIN
DECLARE remark_by varchar(50);
IF NEW.is_bulk_uploaded = 0 THEN
	SET remark_by = 'New Check Added';
ELSE 
	SET remark_by = (SELECT concat( 'New check added by', ' ' ,user_name) as user_name  FROM user_profile WHERE id  = NEW.created_by);
END IF;
 INSERT INTO social_media_activity_data (candsid,ClientRefNumber,comp_table_id,activity_mode,activity_status,activity_type,action,next_follow_up_date,remarks,created_on,created_by,is_auto_filled) VALUES (NEW.candsid,'',NEW.id,'','New check','New check','New check',NULL,remark_by,NEW.created_on,NEW.created_by,NEW.is_bulk_uploaded);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_ver_result`
--

CREATE TABLE `social_media_ver_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closuredate` date DEFAULT NULL,
  `verfstatus` smallint(5) UNSIGNED NOT NULL,
  `var_filter_status` varchar(50) DEFAULT NULL,
  `var_report_status` varchar(50) DEFAULT NULL,
  `social_media_id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `candsid` bigint(20) UNSIGNED NOT NULL,
  `mode_of_verification` varchar(30) DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `first_qc_approve` varchar(25) DEFAULT NULL,
  `first_qc_updated_on` timestamp NULL DEFAULT NULL,
  `first_qu_reject_reason` varchar(500) DEFAULT NULL,
  `is_bulk_uploaded` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `activity_log_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(10) UNSIGNED NOT NULL,
  `state` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `status_value` varchar(35) NOT NULL,
  `filter_status` varchar(50) NOT NULL,
  `report_status` varchar(50) NOT NULL,
  `components_id` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `university_master`
--

CREATE TABLE `university_master` (
  `id` int(10) UNSIGNED NOT NULL,
  `universityname` varchar(255) NOT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL DEFAULT '2',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `vendor_id` int(11) DEFAULT NULL,
  `year_of_passing` varchar(20) DEFAULT NULL,
  `url_link` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `university_master_image`
--

CREATE TABLE `university_master_image` (
  `id` int(11) NOT NULL,
  `university_id` int(11) DEFAULT NULL,
  `file_name` varchar(500) NOT NULL,
  `real_file_name` varchar(500) NOT NULL,
  `status` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_activitity_data`
--

CREATE TABLE `users_activitity_data` (
  `sr_id` int(11) NOT NULL,
  `component` varchar(500) DEFAULT NULL,
  `candidate_name` varchar(500) NOT NULL,
  `ref_no` varchar(100) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `activity_type` varchar(50) DEFAULT NULL,
  `action` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_bgv_sessions`
--

CREATE TABLE `user_bgv_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(60) NOT NULL,
  `email_password` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `password1` varchar(100) DEFAULT NULL,
  `password2` varchar(100) DEFAULT NULL,
  `tbl_roles_id` int(11) NOT NULL,
  `reporting_manager` int(10) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `profile_pic` varchar(200) DEFAULT NULL,
  `title` varchar(5) NOT NULL,
  `designation` varchar(25) NOT NULL,
  `department` varchar(25) NOT NULL,
  `joining_date` date NOT NULL,
  `relieving_date` date DEFAULT NULL,
  `office_phone` varchar(15) NOT NULL,
  `mobile_phone` varchar(20) NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(35) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `state` varchar(25) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(11) DEFAULT '0',
  `pass_reset_key` varchar(100) DEFAULT NULL,
  `pass_reset_expiry` timestamp NULL DEFAULT NULL,
  `is_login_or_not` tinyint(4) NOT NULL DEFAULT '1',
  `ip_address` varchar(50) DEFAULT NULL,
  `bill_date_permission` varchar(50) DEFAULT NULL,
  `import_permission` tinyint(4) DEFAULT NULL,
  `rr_id` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `vendor_managers` varchar(50) DEFAULT NULL,
  `street_address` varchar(250) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `sopc_name` varchar(50) NOT NULL,
  `primary_contact` varchar(15) DEFAULT NULL,
  `email_id` varchar(500) NOT NULL,
  `aggr_start_date` date DEFAULT NULL,
  `aggr_file` varchar(100) DEFAULT NULL,
  `aggr_end_date` date DEFAULT NULL,
  `attchament` varchar(150) NOT NULL,
  `vendor_remarks` varchar(250) DEFAULT NULL,
  `vendors_components` varchar(250) NOT NULL,
  `vendors_components_tat` text NOT NULL,
  `price_tier_1` text NOT NULL,
  `price_tier_2` text NOT NULL,
  `price_tier_3` text NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(10) UNSIGNED DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `address_state` varchar(500) DEFAULT NULL,
  `address_city` varchar(500) DEFAULT NULL,
  `employment_states` varchar(500) DEFAULT NULL,
  `employment_city` varchar(500) DEFAULT NULL,
  `court_client` varchar(200) DEFAULT NULL,
  `global_client` varchar(200) DEFAULT NULL,
  `credit_client` varchar(200) DEFAULT NULL,
  `panel_code` varchar(50) DEFAULT NULL,
  `education_verification_status` tinyint(2) DEFAULT NULL,
  `adv_name` varchar(100) DEFAULT NULL,
  `generate` tinyint(2) DEFAULT NULL,
  `pcc_mov` varchar(50) DEFAULT NULL,
  `pcc_mov_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendors_login`
--

CREATE TABLE `vendors_login` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendors_id` bigint(20) UNSIGNED NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `mobile_no` varchar(11) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `profile_pic` varchar(100) DEFAULT NULL,
  `creted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `modified_on` datetime DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `pass_reset_key` varchar(80) DEFAULT NULL,
  `pass_reset_expiry` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_activity_log`
--

CREATE TABLE `vendor_activity_log` (
  `id` bigint(20) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `component` varchar(50) DEFAULT NULL,
  `action` varchar(500) DEFAULT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_cost_details`
--

CREATE TABLE `vendor_cost_details` (
  `id` int(11) NOT NULL,
  `vendor_master_log_id` int(11) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `additional_cost` int(11) DEFAULT NULL,
  `remark` blob,
  `components_tbl_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `accept_reject_cost` tinyint(4) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_on` datetime DEFAULT NULL,
  `rejected_by` int(11) DEFAULT NULL,
  `rejected_on` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_cost_details_file`
--

CREATE TABLE `vendor_cost_details_file` (
  `id` int(11) NOT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `real_filename` varchar(250) DEFAULT NULL,
  `vendor_cost_details_id` int(11) DEFAULT NULL,
  `component_tbl_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_executive_login`
--

CREATE TABLE `vendor_executive_login` (
  `id` int(11) NOT NULL,
  `vendor_login_id` int(11) NOT NULL,
  `user_name` varchar(500) DEFAULT NULL,
  `email_id` varchar(50) NOT NULL,
  `password` varchar(80) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `mobile_no` varchar(11) DEFAULT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` int(11) DEFAULT NULL,
  `profile_pic` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT '1',
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `cancel_id` int(11) DEFAULT NULL,
  `cancel_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_master_log`
--

CREATE TABLE `vendor_master_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `costing` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tat_status` varchar(10) DEFAULT NULL,
  `vendor_tat_days` int(10) UNSIGNED DEFAULT '0',
  `trasaction_id` varchar(25) NOT NULL,
  `component` varchar(25) NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed',
  `remarks` varchar(250) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vendor_status` int(10) UNSIGNED NOT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_spoc_details`
--

CREATE TABLE `vendor_spoc_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_details_id` int(10) UNSIGNED NOT NULL,
  `spoc_name` varchar(40) NOT NULL,
  `spoc_email` varchar(40) NOT NULL,
  `spoc_mobile` varchar(12) DEFAULT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_vendor_master_log`
--

CREATE TABLE `view_vendor_master_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `trasaction_id` varchar(25) NOT NULL DEFAULT '',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0  = Allocated || 1 = assigned  || 2 = reject || 3 = acknowledge || 4 = reject_by_vendor || 5 = closed || 6=on vendor  call reject',
  `component` varchar(25) NOT NULL DEFAULT '',
  `allocated_by` varchar(25) DEFAULT NULL,
  `allocated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approval_by` varchar(25) DEFAULT NULL,
  `approval_on` datetime DEFAULT NULL,
  `vendors_components_tat` text NOT NULL,
  `component_ref` varchar(50) NOT NULL DEFAULT '',
  `ClientRefNumber` varchar(15) DEFAULT NULL,
  `VIZRefNumber` varchar(50) NOT NULL DEFAULT '',
  `costing` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `additional_costing` int(11) NOT NULL DEFAULT '0',
  `tat_status` varchar(10) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `rejected_by` varchar(100) DEFAULT NULL,
  `rejected_on` datetime DEFAULT NULL,
  `vendor_rejected_by` int(11) DEFAULT NULL,
  `vendor_reject_on` datetime DEFAULT NULL,
  `vendor_actual_status` varchar(50) NOT NULL DEFAULT 'wip',
  `final_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wip',
  `vendor_remark` varchar(255) DEFAULT NULL,
  `vendor_date` date DEFAULT NULL,
  `initaion_date` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_tat_days` int(10) UNSIGNED DEFAULT NULL,
  `vendor_status` int(11) DEFAULT NULL,
  `component_tbl_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `has_case_id` int(11) DEFAULT NULL,
  `has_assigned_on` datetime DEFAULT NULL,
  `digital_insuff` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_vendor_master_log_closure_status`
--

CREATE TABLE `view_vendor_master_log_closure_status` (
  `id` int(11) NOT NULL,
  `view_vendor_master_log_id` int(11) NOT NULL,
  `approve_reject_status` tinyint(4) DEFAULT NULL,
  `reject_reasons` varchar(500) DEFAULT NULL,
  `approve_rejected_on` datetime DEFAULT NULL,
  `approve_reject_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_vendor_master_log_executive`
--

CREATE TABLE `view_vendor_master_log_executive` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `latitude` double(10,7) DEFAULT NULL,
  `longitude` double(10,7) DEFAULT NULL,
  `address_action` varchar(10) DEFAULT NULL,
  `stay_from_action` varchar(10) DEFAULT NULL,
  `saty_to_action` varchar(10) DEFAULT NULL,
  `street_address` varchar(500) DEFAULT NULL,
  `stay_from` varchar(50) DEFAULT NULL,
  `stay_to` varchar(50) DEFAULT NULL,
  `res_street_address` varchar(500) DEFAULT NULL,
  `res_stay_from` varchar(50) DEFAULT NULL,
  `res_stay_to` varchar(50) DEFAULT NULL,
  `mode_of_verification` varchar(50) DEFAULT NULL,
  `resident_status` varchar(50) DEFAULT NULL,
  `landmark` varchar(300) DEFAULT NULL,
  `verified_by` varchar(50) DEFAULT NULL,
  `neighbour_1` varchar(50) DEFAULT NULL,
  `neighbour_details_1` varchar(255) DEFAULT NULL,
  `neighbour_2` varchar(50) DEFAULT NULL,
  `neighbour_details_2` varchar(255) DEFAULT NULL,
  `addr_proof_collected` varchar(255) DEFAULT NULL,
  `address_proof_front` varchar(250) DEFAULT NULL,
  `address_proof_back` varchar(250) DEFAULT NULL,
  `address_proof` varchar(250) DEFAULT NULL,
  `house_pic_door` varchar(250) DEFAULT NULL,
  `location_picture_1` varchar(250) DEFAULT NULL,
  `location_picture_2` varchar(250) DEFAULT NULL,
  `location_picture_3` varchar(250) DEFAULT NULL,
  `signature` varchar(250) DEFAULT NULL,
  `address_proof_front_lat_long` varchar(100) DEFAULT NULL,
  `address_proof_back_lat_long` varchar(100) DEFAULT NULL,
  `address_proof_lat_long` varchar(100) DEFAULT NULL,
  `house_pic_door_lat_long` varchar(100) DEFAULT NULL,
  `location_pictures_1_lat_long` varchar(100) DEFAULT NULL,
  `location_pictures_2_lat_long` varchar(100) DEFAULT NULL,
  `location_pictures_3_lat_long` varchar(100) DEFAULT NULL,
  `signature_lat_long` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_vendor_master_log_file`
--

CREATE TABLE `view_vendor_master_log_file` (
  `id` int(11) NOT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `real_filename` varchar(250) DEFAULT NULL,
  `view_venor_master_log_id` int(11) DEFAULT NULL,
  `component_tbl_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `serialno` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_data`
--
ALTER TABLE `activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cands_id` (`candsid`,`comp_table_id`);

--
-- Indexes for table `address_activity_data`
--
ALTER TABLE `address_activity_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cands_id` (`candsid`,`comp_table_id`);

--
-- Indexes for table `address_details`
--
ALTER TABLE `address_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `address_digital_mail_sms`
--
ALTER TABLE `address_digital_mail_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `address_vendor_log`
--
ALTER TABLE `address_vendor_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_case_id` (`case_id`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `addrver`
--
ALTER TABLE `addrver`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uni_add_com_ref` (`add_com_ref`),
  ADD KEY `candsid` (`candsid`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_has_case_id` (`has_case_id`);

--
-- Indexes for table `addrverres`
--
ALTER TABLE `addrverres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`clientid`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_addrverid` (`addrverid`),
  ADD KEY `idx_verification_status` (`verfstatus`);

--
-- Indexes for table `addrverres_result`
--
ALTER TABLE `addrverres_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`clientid`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_addrverid` (`addrverid`),
  ADD KEY `idex_verification_result_status` (`verfstatus`);

--
-- Indexes for table `addrver_files`
--
ALTER TABLE `addrver_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_addrver_id` (`addrver_id`) USING BTREE;

--
-- Indexes for table `addrver_insuff`
--
ALTER TABLE `addrver_insuff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inx_empverres` (`addrverid`) USING BTREE,
  ADD KEY `idx_user_profile` (`created_by`),
  ADD KEY `idx_userprofile` (`insuff_cleared_by`);

--
-- Indexes for table `admin_menus`
--
ALTER TABLE `admin_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch_update_log`
--
ALTER TABLE `batch_update_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidates_info`
--
ALTER TABLE `candidates_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `index_client_viz_name` (`ClientRefNumber`,`cmp_ref_no`,`CandidateName`),
  ADD KEY `idx_entity` (`entity`),
  ADD KEY `idx_package` (`package`);

--
-- Indexes for table `candidates_info_logs`
--
ALTER TABLE `candidates_info_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `index_client_viz_name` (`ClientRefNumber`,`cmp_ref_no`,`CandidateName`);

--
-- Indexes for table `candidate_activity_record`
--
ALTER TABLE `candidate_activity_record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_files`
--
ALTER TABLE `candidate_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candidate_id` (`candidate_id`) USING BTREE;

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients_details`
--
ALTER TABLE `clients_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_clients_id` (`tbl_clients_id`);

--
-- Indexes for table `clients_logs`
--
ALTER TABLE `clients_logs`
  ADD KEY `ind_clients_log_id` (`clients_id`);

--
-- Indexes for table `client_aggr_details`
--
ALTER TABLE `client_aggr_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`client_id`);

--
-- Indexes for table `client_candidates_info`
--
ALTER TABLE `client_candidates_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `index_client_viz_name` (`ClientRefNumber`,`cmp_ref_no`,`CandidateName`),
  ADD KEY `idx_entity` (`entity`),
  ADD KEY `idx_package` (`package`);

--
-- Indexes for table `client_candidates_info_logs`
--
ALTER TABLE `client_candidates_info_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `index_client_viz_name` (`ClientRefNumber`,`cmp_ref_no`,`CandidateName`);

--
-- Indexes for table `client_candidate_files`
--
ALTER TABLE `client_candidate_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candidate_id` (`candidate_id`) USING BTREE;

--
-- Indexes for table `client_login`
--
ALTER TABLE `client_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_mode_of_verification`
--
ALTER TABLE `client_mode_of_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_component_id` (`component_id`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `client_new_cases`
--
ALTER TABLE `client_new_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `client_new_cases_log`
--
ALTER TABLE `client_new_cases_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_new_cases` (`client_new_cases_id`),
  ADD KEY `idx_created_on` (`created_by`);

--
-- Indexes for table `client_new_case_file`
--
ALTER TABLE `client_new_case_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_new_case_rearranged_file`
--
ALTER TABLE `client_new_case_rearranged_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_scope_of_work`
--
ALTER TABLE `client_scope_of_work`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_component_id` (`component_id`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `client_spoc_details`
--
ALTER TABLE `client_spoc_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_clients_details` (`clients_details_id`);

--
-- Indexes for table `company_database`
--
ALTER TABLE `company_database`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_database_verifiers_details`
--
ALTER TABLE `company_database_verifiers_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_database_verifiers_details_bk`
--
ALTER TABLE `company_database_verifiers_details_bk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `components_admin`
--
ALTER TABLE `components_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `components_client`
--
ALTER TABLE `components_client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `controller_mothod`
--
ALTER TABLE `controller_mothod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country_ip`
--
ALTER TABLE `country_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courtver`
--
ALTER TABLE `courtver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `courtver_files`
--
ALTER TABLE `courtver_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courtver_insuff`
--
ALTER TABLE `courtver_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courtver_result`
--
ALTER TABLE `courtver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_cands` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_courtver` (`courtver_id`);

--
-- Indexes for table `courtver_vendor_log`
--
ALTER TABLE `courtver_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courtver_ver_result`
--
ALTER TABLE `courtver_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_candsid` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_curtver` (`courtver_id`);

--
-- Indexes for table `court_activity_data`
--
ALTER TABLE `court_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `court_vendor_details`
--
ALTER TABLE `court_vendor_details`
  ADD PRIMARY KEY (`sr_id`);

--
-- Indexes for table `credit_report`
--
ALTER TABLE `credit_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `credit_report_activity_data`
--
ALTER TABLE `credit_report_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_report_files`
--
ALTER TABLE `credit_report_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_report_insuff`
--
ALTER TABLE `credit_report_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_report_result`
--
ALTER TABLE `credit_report_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_candsid` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_creditid` (`credit_report_id`) USING BTREE;

--
-- Indexes for table `credit_report_vendor_log`
--
ALTER TABLE `credit_report_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_report_ver_result`
--
ALTER TABLE `credit_report_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_cands` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_courtverid` (`credit_report_id`);

--
-- Indexes for table `cron_job`
--
ALTER TABLE `cron_job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_job_component`
--
ALTER TABLE `cron_job_component`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_narcotis`
--
ALTER TABLE `drug_narcotis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `drug_narcotis_activity_data`
--
ALTER TABLE `drug_narcotis_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_narcotis_files`
--
ALTER TABLE `drug_narcotis_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_narcotis_insuff`
--
ALTER TABLE `drug_narcotis_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_narcotis_result`
--
ALTER TABLE `drug_narcotis_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_cands` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_res_drugsid` (`drug_narcotis_id`);

--
-- Indexes for table `drug_narcotis_vendor_log`
--
ALTER TABLE `drug_narcotis_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_narcotis_ver_result`
--
ALTER TABLE `drug_narcotis_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_result_drugsid` (`drug_narcotis_id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`),
  ADD KEY `idx_university` (`university_board`),
  ADD KEY `idx_qualification` (`qualification`),
  ADD KEY `idx_vendor_id` (`vendor_id`);

--
-- Indexes for table `education_activity_data`
--
ALTER TABLE `education_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_files`
--
ALTER TABLE `education_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_insuff`
--
ALTER TABLE `education_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_mail_details`
--
ALTER TABLE `education_mail_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_result`
--
ALTER TABLE `education_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`),
  ADD KEY `idx_res_university` (`res_university_board`),
  ADD KEY `idx_res_qualification` (`res_qualification`),
  ADD KEY `idx_educationid` (`education_id`);

--
-- Indexes for table `education_url_details`
--
ALTER TABLE `education_url_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_vendor_log`
--
ALTER TABLE `education_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_ver_result`
--
ALTER TABLE `education_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_cands` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_educationid` (`education_id`),
  ADD KEY `idx_result_qualification` (`res_qualification`),
  ADD KEY `idx_result_university` (`res_university_board`) USING BTREE;

--
-- Indexes for table `employment_vendor_log`
--
ALTER TABLE `employment_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empver`
--
ALTER TABLE `empver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candsid` (`candsid`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_comapny_name` (`nameofthecompany`);

--
-- Indexes for table `empverres`
--
ALTER TABLE `empverres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ver_client_id` (`clientid`),
  ADD KEY `idx_ver_candsid` (`candsid`),
  ADD KEY `idx_empver_d` (`empverid`),
  ADD KEY `idx_verstatus` (`verfstatus`) USING BTREE,
  ADD KEY `idex_res_company` (`res_nameofthecompany`);

--
-- Indexes for table `empverres_files`
--
ALTER TABLE `empverres_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empverres_insuff`
--
ALTER TABLE `empverres_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empverres_logs`
--
ALTER TABLE `empverres_logs`
  ADD PRIMARY KEY (`sr_id`),
  ADD KEY `idx_result_cands` (`candsid`),
  ADD KEY `idex_result_client` (`clientid`),
  ADD KEY `idx_result_empver_id` (`empverid`),
  ADD KEY `idx_result_company` (`res_nameofthecompany`);

--
-- Indexes for table `empver_activity_data`
--
ALTER TABLE `empver_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empver_logs`
--
ALTER TABLE `empver_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empver_supervisor_details`
--
ALTER TABLE `empver_supervisor_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_mail_details`
--
ALTER TABLE `emp_mail_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entity_package`
--
ALTER TABLE `entity_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fake_university`
--
ALTER TABLE `fake_university`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `glodbver`
--
ALTER TABLE `glodbver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `glodbver_activity_data`
--
ALTER TABLE `glodbver_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `glodbver_files`
--
ALTER TABLE `glodbver_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `glodbver_insuff`
--
ALTER TABLE `glodbver_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `glodbver_result`
--
ALTER TABLE `glodbver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_cands` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_glodbverid` (`glodbver_id`) USING BTREE;

--
-- Indexes for table `glodbver_vendor_log`
--
ALTER TABLE `glodbver_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `glodbver_ver_result`
--
ALTER TABLE `glodbver_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_cands` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_glodbverid` (`glodbver_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holiday_dates`
--
ALTER TABLE `holiday_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identity`
--
ALTER TABLE `identity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `identity_activity_data`
--
ALTER TABLE `identity_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identity_files`
--
ALTER TABLE `identity_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identity_insuff`
--
ALTER TABLE `identity_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identity_result`
--
ALTER TABLE `identity_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_cands` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_res_identityid` (`identity_id`);

--
-- Indexes for table `identity_vendor_log`
--
ALTER TABLE `identity_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identity_ver_result`
--
ALTER TABLE `identity_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_cands` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_identityid` (`identity_id`);

--
-- Indexes for table `location_master`
--
ALTER TABLE `location_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_sms_details`
--
ALTER TABLE `mail_sms_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcc`
--
ALTER TABLE `pcc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `pcc_activity_data`
--
ALTER TABLE `pcc_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcc_files`
--
ALTER TABLE `pcc_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcc_insuff`
--
ALTER TABLE `pcc_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcc_result`
--
ALTER TABLE `pcc_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_cands` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_res_pccid` (`pcc_id`);

--
-- Indexes for table `pcc_vendor_log`
--
ALTER TABLE `pcc_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcc_ver_result`
--
ALTER TABLE `pcc_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_candsid` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_pccid` (`pcc_id`);

--
-- Indexes for table `pre_post_details`
--
ALTER TABLE `pre_post_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qc_status`
--
ALTER TABLE `qc_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qualification_master`
--
ALTER TABLE `qualification_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raising_insuff_dropdown`
--
ALTER TABLE `raising_insuff_dropdown`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference`
--
ALTER TABLE `reference`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `reference_activity_data`
--
ALTER TABLE `reference_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference_files`
--
ALTER TABLE `reference_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference_insuff`
--
ALTER TABLE `reference_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference_result`
--
ALTER TABLE `reference_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_cands` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_referenceid` (`reference_id`),
  ADD KEY `idx_verfstatus` (`verfstatus`);

--
-- Indexes for table `reference_vendor_log`
--
ALTER TABLE `reference_vendor_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference_ver_result`
--
ALTER TABLE `reference_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_cands` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_referenceid` (`reference_id`);

--
-- Indexes for table `ref_mail_details`
--
ALTER TABLE `ref_mail_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_generated_user`
--
ALTER TABLE `report_generated_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_requested`
--
ALTER TABLE `report_requested`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`permissionID`);

--
-- Indexes for table `sales_login`
--
ALTER TABLE `sales_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduler_list`
--
ALTER TABLE `scheduler_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduler_task`
--
ALTER TABLE `scheduler_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sla_default_setting`
--
ALTER TABLE `sla_default_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sla_setting`
--
ALTER TABLE `sla_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candsid` (`candsid`),
  ADD KEY `idx_clientid` (`clientid`);

--
-- Indexes for table `social_media_activity_data`
--
ALTER TABLE `social_media_activity_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_files`
--
ALTER TABLE `social_media_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_insuff`
--
ALTER TABLE `social_media_insuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_result`
--
ALTER TABLE `social_media_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_res_cands` (`candsid`),
  ADD KEY `idx_res_clientid` (`clientid`),
  ADD KEY `idx_res_identityid` (`social_media_id`);

--
-- Indexes for table `social_media_ver_result`
--
ALTER TABLE `social_media_ver_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_result_cands` (`candsid`),
  ADD KEY `idx_result_client` (`clientid`),
  ADD KEY `idx_result_identityid` (`social_media_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `university_master`
--
ALTER TABLE `university_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `university_master_image`
--
ALTER TABLE `university_master_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_activitity_data`
--
ALTER TABLE `users_activitity_data`
  ADD PRIMARY KEY (`sr_id`);

--
-- Indexes for table `user_bgv_sessions`
--
ALTER TABLE `user_bgv_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors_login`
--
ALTER TABLE `vendors_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_activity_log`
--
ALTER TABLE `vendor_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_cost_details`
--
ALTER TABLE `vendor_cost_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_cost_details_file`
--
ALTER TABLE `vendor_cost_details_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_executive_login`
--
ALTER TABLE `vendor_executive_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_master_log`
--
ALTER TABLE `vendor_master_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_spoc_details`
--
ALTER TABLE `vendor_spoc_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_clients_details` (`vendor_details_id`);

--
-- Indexes for table `view_vendor_master_log`
--
ALTER TABLE `view_vendor_master_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `view_vendor_master_log_closure_status`
--
ALTER TABLE `view_vendor_master_log_closure_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `view_vendor_master_log_executive`
--
ALTER TABLE `view_vendor_master_log_executive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `view_vendor_master_log_file`
--
ALTER TABLE `view_vendor_master_log_file`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_data`
--
ALTER TABLE `activity_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address_activity_data`
--
ALTER TABLE `address_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address_details`
--
ALTER TABLE `address_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address_digital_mail_sms`
--
ALTER TABLE `address_digital_mail_sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address_vendor_log`
--
ALTER TABLE `address_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addrver`
--
ALTER TABLE `addrver`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addrverres`
--
ALTER TABLE `addrverres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addrverres_result`
--
ALTER TABLE `addrverres_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addrver_files`
--
ALTER TABLE `addrver_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addrver_insuff`
--
ALTER TABLE `addrver_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_menus`
--
ALTER TABLE `admin_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `batch_update_log`
--
ALTER TABLE `batch_update_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidates_info`
--
ALTER TABLE `candidates_info`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidates_info_logs`
--
ALTER TABLE `candidates_info_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_activity_record`
--
ALTER TABLE `candidate_activity_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_files`
--
ALTER TABLE `candidate_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_details`
--
ALTER TABLE `clients_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_logs`
--
ALTER TABLE `clients_logs`
  MODIFY `clients_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_aggr_details`
--
ALTER TABLE `client_aggr_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_candidates_info`
--
ALTER TABLE `client_candidates_info`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_candidates_info_logs`
--
ALTER TABLE `client_candidates_info_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_candidate_files`
--
ALTER TABLE `client_candidate_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_login`
--
ALTER TABLE `client_login`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_mode_of_verification`
--
ALTER TABLE `client_mode_of_verification`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_new_cases`
--
ALTER TABLE `client_new_cases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_new_cases_log`
--
ALTER TABLE `client_new_cases_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_new_case_file`
--
ALTER TABLE `client_new_case_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_new_case_rearranged_file`
--
ALTER TABLE `client_new_case_rearranged_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_scope_of_work`
--
ALTER TABLE `client_scope_of_work`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_spoc_details`
--
ALTER TABLE `client_spoc_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_database`
--
ALTER TABLE `company_database`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_database_verifiers_details`
--
ALTER TABLE `company_database_verifiers_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_database_verifiers_details_bk`
--
ALTER TABLE `company_database_verifiers_details_bk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `components`
--
ALTER TABLE `components`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `components_admin`
--
ALTER TABLE `components_admin`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `components_client`
--
ALTER TABLE `components_client`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `controller_mothod`
--
ALTER TABLE `controller_mothod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country_ip`
--
ALTER TABLE `country_ip`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courtver`
--
ALTER TABLE `courtver`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courtver_files`
--
ALTER TABLE `courtver_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courtver_insuff`
--
ALTER TABLE `courtver_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courtver_result`
--
ALTER TABLE `courtver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courtver_vendor_log`
--
ALTER TABLE `courtver_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courtver_ver_result`
--
ALTER TABLE `courtver_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `court_activity_data`
--
ALTER TABLE `court_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `court_vendor_details`
--
ALTER TABLE `court_vendor_details`
  MODIFY `sr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_report`
--
ALTER TABLE `credit_report`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_report_activity_data`
--
ALTER TABLE `credit_report_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_report_files`
--
ALTER TABLE `credit_report_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_report_insuff`
--
ALTER TABLE `credit_report_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_report_result`
--
ALTER TABLE `credit_report_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_report_vendor_log`
--
ALTER TABLE `credit_report_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_report_ver_result`
--
ALTER TABLE `credit_report_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_job`
--
ALTER TABLE `cron_job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_job_component`
--
ALTER TABLE `cron_job_component`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_narcotis`
--
ALTER TABLE `drug_narcotis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_narcotis_activity_data`
--
ALTER TABLE `drug_narcotis_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_narcotis_files`
--
ALTER TABLE `drug_narcotis_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_narcotis_insuff`
--
ALTER TABLE `drug_narcotis_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_narcotis_result`
--
ALTER TABLE `drug_narcotis_result`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_narcotis_vendor_log`
--
ALTER TABLE `drug_narcotis_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_narcotis_ver_result`
--
ALTER TABLE `drug_narcotis_ver_result`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_activity_data`
--
ALTER TABLE `education_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_files`
--
ALTER TABLE `education_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_insuff`
--
ALTER TABLE `education_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_mail_details`
--
ALTER TABLE `education_mail_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_result`
--
ALTER TABLE `education_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_url_details`
--
ALTER TABLE `education_url_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_vendor_log`
--
ALTER TABLE `education_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_ver_result`
--
ALTER TABLE `education_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employment_vendor_log`
--
ALTER TABLE `employment_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empver`
--
ALTER TABLE `empver`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empverres`
--
ALTER TABLE `empverres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empverres_files`
--
ALTER TABLE `empverres_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empverres_insuff`
--
ALTER TABLE `empverres_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empverres_logs`
--
ALTER TABLE `empverres_logs`
  MODIFY `sr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empver_activity_data`
--
ALTER TABLE `empver_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empver_logs`
--
ALTER TABLE `empver_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empver_supervisor_details`
--
ALTER TABLE `empver_supervisor_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_mail_details`
--
ALTER TABLE `emp_mail_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `entity_package`
--
ALTER TABLE `entity_package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fake_university`
--
ALTER TABLE `fake_university`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glodbver`
--
ALTER TABLE `glodbver`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glodbver_activity_data`
--
ALTER TABLE `glodbver_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glodbver_files`
--
ALTER TABLE `glodbver_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glodbver_insuff`
--
ALTER TABLE `glodbver_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glodbver_result`
--
ALTER TABLE `glodbver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glodbver_vendor_log`
--
ALTER TABLE `glodbver_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glodbver_ver_result`
--
ALTER TABLE `glodbver_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holiday_dates`
--
ALTER TABLE `holiday_dates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity`
--
ALTER TABLE `identity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity_activity_data`
--
ALTER TABLE `identity_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity_files`
--
ALTER TABLE `identity_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity_insuff`
--
ALTER TABLE `identity_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity_result`
--
ALTER TABLE `identity_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity_vendor_log`
--
ALTER TABLE `identity_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity_ver_result`
--
ALTER TABLE `identity_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_master`
--
ALTER TABLE `location_master`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_sms_details`
--
ALTER TABLE `mail_sms_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcc`
--
ALTER TABLE `pcc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcc_activity_data`
--
ALTER TABLE `pcc_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcc_files`
--
ALTER TABLE `pcc_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcc_insuff`
--
ALTER TABLE `pcc_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcc_result`
--
ALTER TABLE `pcc_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcc_vendor_log`
--
ALTER TABLE `pcc_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcc_ver_result`
--
ALTER TABLE `pcc_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_post_details`
--
ALTER TABLE `pre_post_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qc_status`
--
ALTER TABLE `qc_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qualification_master`
--
ALTER TABLE `qualification_master`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `raising_insuff_dropdown`
--
ALTER TABLE `raising_insuff_dropdown`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference`
--
ALTER TABLE `reference`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_activity_data`
--
ALTER TABLE `reference_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_files`
--
ALTER TABLE `reference_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_insuff`
--
ALTER TABLE `reference_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_result`
--
ALTER TABLE `reference_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_vendor_log`
--
ALTER TABLE `reference_vendor_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_ver_result`
--
ALTER TABLE `reference_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ref_mail_details`
--
ALTER TABLE `ref_mail_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_generated_user`
--
ALTER TABLE `report_generated_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_requested`
--
ALTER TABLE `report_requested`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  MODIFY `permissionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_login`
--
ALTER TABLE `sales_login`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduler_list`
--
ALTER TABLE `scheduler_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduler_task`
--
ALTER TABLE `scheduler_task`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sla_default_setting`
--
ALTER TABLE `sla_default_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sla_setting`
--
ALTER TABLE `sla_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_activity_data`
--
ALTER TABLE `social_media_activity_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_files`
--
ALTER TABLE `social_media_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_insuff`
--
ALTER TABLE `social_media_insuff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_result`
--
ALTER TABLE `social_media_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_ver_result`
--
ALTER TABLE `social_media_ver_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_master`
--
ALTER TABLE `university_master`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_master_image`
--
ALTER TABLE `university_master_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_activitity_data`
--
ALTER TABLE `users_activitity_data`
  MODIFY `sr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors_login`
--
ALTER TABLE `vendors_login`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_activity_log`
--
ALTER TABLE `vendor_activity_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_cost_details`
--
ALTER TABLE `vendor_cost_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_cost_details_file`
--
ALTER TABLE `vendor_cost_details_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_executive_login`
--
ALTER TABLE `vendor_executive_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_master_log`
--
ALTER TABLE `vendor_master_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_spoc_details`
--
ALTER TABLE `vendor_spoc_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `view_vendor_master_log`
--
ALTER TABLE `view_vendor_master_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `view_vendor_master_log_closure_status`
--
ALTER TABLE `view_vendor_master_log_closure_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `view_vendor_master_log_executive`
--
ALTER TABLE `view_vendor_master_log_executive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `view_vendor_master_log_file`
--
ALTER TABLE `view_vendor_master_log_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
