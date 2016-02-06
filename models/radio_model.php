<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Radio_model extends CI_Model 
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}

/**
 * PHP version 5
 * Reviewed by LYJ . 2016.1.23
 *
 * @category PHP
 * @author 	 LYJ <1048434786@qq.com>
 * @version  2015.9.13
 * @link 	 https://git.oschina.net/kofyu/KFTV-core
 */
	function getRadioById($id) 
	{
		$sql = "select * from radio_station where id = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getRadioTime($bid) 
	{
		$sql = "select * from radio_time where bid = '$bid'";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function radioUpdate($mms, $info, $id) 
	{	
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " update radio_station set mms = ?, info = ? where id = '$id' ";
  		$stmt = $this->db->conn_id->prepare($sql);
	  	$stmt->bind_param("ss", $mms, $info);
	  	$stmt->execute();
	  	$stmt->close();
	}

	function radioTimeUpdate($start, $end, $id) 
	{
		$data = array(
	            'start' => $start ,
	            'end' => $end
	   	 	);
		$this->db->where('id', $id);
		$this->db->update('radio_time', $data);
	}

	function radioAdd($id) 
	{
		$sql = "insert into radio_time(bid) values ('$id')";
		$query = $this->db->query($sql);
	}

	function radioDel($id) 
	{
		$sql = "delete from radio_time where id = '$id'";
		$query = $this->db->query($sql);
	}


}