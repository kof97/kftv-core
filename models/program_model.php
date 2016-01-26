<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program_model extends CI_Model 
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}

/**
 * Edit By: LYJ
 * Time: 2015.8.20
 * Function: program
 * Review: LYJ . 2016.1.22
 */
	function picture($savePath)
	{
		$config['overwrite']  = true;
		$config['encrypt_name']  = true;

		$config['upload_path'] = './' . $savePath;
		$config['allowed_types'] = 'jpg|jpeg|gif|png';
		$config['max_size'] = '2048';
		$config['max_width']  = 0;
		$config['max_height']  = 0;
		$config['file_name'] = date("Ymdhis");
	
		$this->load->library('upload', $config);
	
		$up = $this->upload->do_upload('userfile');
		/*debug if ( ! $up ) {
					$error = array('error' => $this->upload->display_errors());
				 	exit(var_dump($error));
				} else {*/
			  		$data = array('upload_data' => $this->upload->data());
		/*		} */
		return $up?$data:0;
	}

	function video($savePath) 
	{
		$config['overwrite']  = true;
	  	$config['encrypt_name']  = true;

		$config['upload_path'] = './' . $savePath;
	  	$config['allowed_types'] = '*';
	  	$config['max_size'] = '102400';
	  	$config['max_width']  = 0;
	  	$config['max_height']  = 0;
	  	$config['file_name'] = date("Ymdhis");
	  
	  	$this->load->library('upload', $config);
	 
		$up = $this->upload->do_upload('userfile');
		/*debug 
			if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
		  		exit(var_dump($error));
		  	} else {*/
		 	  	$data = array('upload_data' => $this->upload->data());
		/*	}*/
		return $up?$data:0;
	}

	function page($pageSize, $offset, $total, $pageUri, $uriSegment = 4) 
	{
		$config['base_url'] = site_url($pageUri);
		$config['total_rows'] = $total;
		$config['per_page'] = $pageSize;
		$config['first_link'] = '首页';
		$config['last_link'] = '尾页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['uri_segment'] = $uriSegment;

		$this->pagination->initialize($config);
		$page = $this->pagination->create_links();
		return $page;
	}

	function pAdd($programName, $program_principal, $programTime, $programInfo, $programLogo, $using, $video) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into program (program_name, program_principal, program_time, program_info, program_logo, used, 
				video) values (?, ?, ?, ?, '$programLogo', $using, '$video') ";
  		$stmt = $this->db->conn_id->prepare($sql);
	  	$stmt->bind_param("ssss", $programName, $program_principal, $programTime, $programInfo);
	  	$stmt->execute();
	  	$stmt->close();
	}

	function getAllP($offset, $pageSize) 
	{
		$sql = "select * from program limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getAllPr() 
	{
		$sql = "select * from program";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function using($pid, $use) 
	{
		$sql = " update program set used = '$use' where id = $pid ";
		return $this->db->query($sql);
	}

	function pDel($pid) 
	{
		$sql = " delete from program where id = '$pid' ";
		$this->db->query($sql);
		$sql = " update program_video set program_name = '栏目已删除' where program_id = '$pid' ";
		$this->db->query($sql);
	}

	function getProgramMessage($pid) 
	{
		$sql = "select * from program where id = $pid ";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getProgramPicUrl($pid) 
	{
		$sql = "select program_logo from program where id = $pid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function pUpdate($programNmae, $program_principal, $programTime, $programInfo, $programLogo, $using, $video, $pid) 
	{	
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($programLogo) == "") {
			$sql = " update program set program_name = ?, program_principal = ?, program_time = ?, program_info = ?,  
					used = '$using', video = '$video' where id = '$pid' ";
		} else {
			$sql = " update program set program_name = ?, program_principal = ?, program_time = ?, program_info = ?,  
					program_logo = '$programLogo', used = '$using', video = '$video' where id = '$pid' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ssss", $programName, $program_principal, $programTime, $programInfo);
		$stmt->execute();
		$stmt->close();
	}

/**
 * Edit By: LYJ
 * Time: 2015.8.21
 * Function: video
 * Review: LYJ . 2016.1.22
 */
	function getUsingProgram() 
	{
		$sql = "select * from program where used = 1 ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getProgramNameById($programId) 
	{
		$sql = "select program_name from program where id = $programId";
		$query = $this->db->query($sql);
		return $query->row()->program_name;
	}

	function videoAdd($programId, $programName, $videoPic, $videoName, $videoInfo, $videoUrl, $time, $ischecked, $pubUser, $videoLong) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into program_video (program_id, program_name, video_pic, video_name, video_info, video_url, 
				time, ischecked, pub_user, video_long) values ($programId, ?, '$videoPic', ?, ?, '$videoUrl', '$time', 
				'$ischecked', '$pubUser', '$videoLong') ";
  		$stmt = $this->db->conn_id->prepare($sql);
	  	$stmt->bind_param("sss", $programName, $videoName, $videoInfo);
	  	$stmt->execute();
	  	$stmt->close();
	}

	function getProgramVideo($offset, $pageSize, $pid, $searchKey) 
	{
		if ($pid == 0) {
			$sql = "select * from program_video where video_name like '%$searchKey%' order by time desc limit $offset, $pageSize";
		} else {
			$sql = "select * from program_video where video_name like '%$searchKey%' and program_id = $pid order by time desc limit $offset, $pageSize";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}

	function ischecked($vid, $ischecked) 
	{
		$sql = " update program_video set ischecked = '$ischecked' where id = $vid ";
		return $this->db->query($sql);
	}

	function pVideoDel($vid) 
	{
		$sql = " delete from program_video where id = '$vid' ";
		$this->db->query($sql);
	}

	function getProgramVideoDetail($vid) 
	{
		$sql = "select * from program_video where id = $vid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getVideo($vid) 
	{
		$sql = "select * from program_video where id = $vid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getVideoUrl($vid) 
	{
		$sql = "select video_url from program_video where id = $vid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function videoUpdate($vid, $videoPic, $videoName, $videoInfo, $videoUrl, $time, $ischecked, $pubUser, $videoLong) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($videoUrl) == "") {
			$sql = " update program_video set video_pic = '$videoPic', video_name = ?, video_info = ?, time = '$time',  
					ischecked = '$ischecked', pub_user = '$pubUser' where id = '$vid' ";
		} else {
			$sql = " update program_video set video_pic = '$videoPic', video_name = ?, video_info = ?, time = '$time',  
					ischecked = '$ischecked', pub_user = '$pubUser', video_url = '$videoUrl', video_long = '$videoLong' 
					where id = '$vid' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $videoName, $videoInfo);
		$stmt->execute();
		$stmt->close();
	}

	// front end
	function getProgramVideos($offset, $pageSize, $pid) 
	{
		if ($pid == 0) {
			$sql = "select * from program_video where ischecked = 1 order by time desc limit $offset, $pageSize";
		} else {
			$sql = "select * from program_video where ischecked = 1 and program_id = $pid order by time desc limit $offset, $pageSize";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}

}