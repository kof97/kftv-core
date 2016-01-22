<?php
class Radio_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}

/*
*	Edit By : 阿诺
*	Time : 2015.9.13
*	function : 模型-广播电台
*/
	function getRadioById($id) {
		$sql = "select * from radio_station where id = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getRadioTime($bid) {
		$sql = "select * from radio_time where bid = '$bid'";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function radioUpdate($mms, $info, $id) {
		$data = array(
	            'mms' => $mms ,
	            'info' => $info
	   	 	);
		$this->db->where('id', $id);
		$this->db->update('radio_station', $data);
	}

	function radioTimeUpdate($start, $end, $id) {
		$data = array(
	            'start' => $start ,
	            'end' => $end
	   	 	);

		$this->db->where('id', $id);
		$this->db->update('radio_time', $data);
	}

	function radioAdd($id) {
		$sql = "insert into radio_time(bid) values ('$id')";
		$query = $this->db->query($sql);
	}

	function radioDel($id) {
		$sql = "delete from radio_time where id = '$id'";
		$query = $this->db->query($sql);
	}


}