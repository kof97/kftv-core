<?php
class Program_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}

/*
*	Edit By : 阿诺
*	Time : 2015.8.20
*	function : 模型-电视栏目管理
*/
	function pAdd($program_name, $program_principal, $program_time, $program_info, $program_logo, $using, $video) {
		$sql = " insert into program (program_name, program_principal, program_time, program_info, program_logo, used, video) values ('$program_name', '$program_principal', '$program_time', '$program_info', '$program_logo', $using, '$video') ";
		$this->db->query($sql);
	}

	function getAllP($offset, $page_size) {
		$sql = "select * from program limit $offset, $page_size ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getAllPr() {
		$sql = "select * from program";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function using($pid, $use) {
		$sql = " update program set used = '$use' where id = $pid ";
		$this->db->query($sql);
	}

	function pDel($pid) {
		$sql = " delete from program where id = '$pid' ";
		$this->db->query($sql);
	}

	function getProgramMessage($pid) {
		$sql = "select * from program where id = $pid ";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getProgramPicUrl($pid) {
		$sql = "select program_logo from program where id = $pid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function pUpdate($program_name, $program_principal, $program_time, $program_info, $program_logo, $using, $video, $pid) {	
		if (trim($program_logo) == "") {
			$data = array(
	            'program_name' => $program_name ,
	            'program_principal' => $program_principal ,
	            'program_time' => $program_time ,
	            'program_info' => $program_info ,
	            'used' => $using ,
	            'video' => $video
	        );
		} else {
			$data = array(
	            'program_name' => $program_name ,
	            'program_principal' => $program_principal ,
	            'program_time' => $program_time ,
	            'program_info' => $program_info ,
	            'program_logo' => $program_logo ,
	            'used' => $using ,
	            'video' => $video
	        );
		}
		$this->db->where('id', $pid);
		$this->db->update('program', $data);
	}

/*
*	Edit By : 阿诺
*	Time : 2015.8.21
*	function : 模型-电视栏目视频管理
*/
	function getUsingProgram() {
		$sql = "select * from program where used = 1 ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getProgramNameById($program_id) {
		$sql = "select program_name from program where id = $program_id";
		$query = $this->db->query($sql);
		return $query->row()->program_name;
	}

	function videoAdd($program_id, $program_name, $video_pic, $video_name, $video_info, $video_url, $time, $ischecked, $pub_user, $video_long) {
		$data = array(
               		'program_id' => $program_id ,
               		'program_name' => $program_name ,
               		'video_pic' => $video_pic ,
               		'video_name' => $video_name ,
               		'video_info' => $video_info ,
               		'video_url' => $video_url ,
               		'time' => $time ,
               		'ischecked' => $ischecked ,
               		'pub_user' => $pub_user ,
               		'video_long' => $video_long
            	);
		$this->db->insert('program_video', $data); 
	}

	function getProgramVideo($offset, $page_size, $pid, $searchKey) {
		if ($pid == 0) {
			$sql = "select * from program_video where video_name like '%$searchKey%' order by time desc limit $offset, $page_size";
		} else {
			$sql = "select * from program_video where video_name like '%$searchKey%' and program_id = $pid order by time desc limit $offset, $page_size";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}

	function ischecked($vid, $ischecked) {
		$sql = " update program_video set ischecked = '$ischecked' where id = $vid ";
		$this->db->query($sql);
	}

	function pVideoDel($vid) {
		$sql = " delete from program_video where id = '$vid' ";
		$this->db->query($sql);
	}

	function getProgramVideoDetail($vid) {
		$sql = "select * from program_video where id = $vid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getVideo($vid) {
		$sql = "select * from program_video where id = $vid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getVideoUrl($vid) {
		$sql = "select video_url from program_video where id = $vid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function videoUpdate($vid, $video_pic, $video_name, $video_info, $video_url, $time, $ischecked, $pub_user, $video_long) {
		if (trim($video_url) == "") {
			$data = array(
	            'video_pic' => $video_pic ,
	            'video_name' => $video_name ,
	            'video_info' => $video_info ,
	            'time' => $time ,
	            'ischecked' => $ischecked ,
	            'pub_user' => $pub_user
	        );
		} else {
			$data = array(
	            'video_pic' => $video_pic ,
	            'video_name' => $video_name ,
	            'video_info' => $video_info ,
	            'time' => $time ,
	            'ischecked' => $ischecked ,
	            'pub_user' => $pub_user ,
	            'video_url' => $video_url ,
	            'video_long' => $video_long
	        );
		}
		$this->db->where('id', $vid);
		$this->db->update('program_video', $data);
	}








	

}