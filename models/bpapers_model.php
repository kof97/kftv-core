<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bpapers_model extends CI_Model 
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}
	
/*
 * Edit By : LYJ
 * Time : 2015.8.13
 * Function : newspapers
 * Review : LYJ . 2016.1.22
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

	function getAllbp($offset, $page_size) 
	{
		$sql = "select * from bnewspaper order by pub_time desc limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function bpAdd($nowIssue, $totalIssue, $picture, $time, $pubTime) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into bnewspaper (issue_now, issue_total, picture, time, pub_time) values 
				(?, ?, '$picture', '$time', '$pubTime') ";
  		$stmt = $this->db->conn_id->prepare($sql);
	  	$stmt->bind_param("ss", $nowIssue, $totalIssue);
	  	$stmt->execute();
	  	$stmt->close();
	}

	function getBpById($bid) 
	{
		$sql = "select * from bnewspaper where id = $bid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bpUpdate($nowIssue, $totalIssue, $picture, $time, $pubTime, $bid) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($picture) == "") {
			$sql = " update bnewspaper set issue_now = ?, issue_total = ?, time = '$time', 
					pub_time = '$pubTime' where id = '$bid' ";
		} else {
			$sql = " update bnewspaper set issue_now = ?, issue_total = ?, time = '$time', picture = '$picture',
					pub_time = '$pubTime' where id = '$bid' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $nowIssue, $totalIssue);
		$stmt->execute();
		$stmt->close();
	}

	function getPicUrl($bid) 
	{
		$sql = "select picture from bnewspaper where id = $bid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bpDel($bid) 
	{
		$this->db->trans_start();
			// delete newspapers
			$sql = " delete from bnewspaper where id = '$bid' ";
			$res = $this->db->query($sql);
			// delete articles
			$sql = " delete from bnpcontent where paperid = '$bid' ";
			$this->db->query($sql);
		$this->db->trans_complete();
	}


	function getOrderbp($offset, $page_size, $order) 
	{
		if ($order == 1) {
			$sql = "select * from bnewspaper order by time desc limit $offset, $page_size";
		} else if ($order == 2) {
			$sql = "select * from bnewspaper order by pub_time desc limit $offset, $page_size ";
		} else {
			$sql = "select * from bnewspaper order by pub_time desc limit $offset, $page_size ";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}

/*
 * Edit By : LYJ
 * Time : 2015.8.13
 * Function : articles
 * Review : LYJ . 2016.1.22
 */
	function bpArtAdd($paperid, $title, $content, $pubUser, $checked, $time, $source) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into bnpcontent (paperid, title, content, pub_user, checked, time, source) values 
				('$paperid', ?, ?, '$pubUser', '$checked', '$time', ?) ";
  		$stmt = $this->db->conn_id->prepare($sql);
	  	$stmt->bind_param("sss", $title, $content, $source);
	  	$stmt->execute();
	  	$stmt->close();
	}

 	function getBpArt($offset, $page_size, $paperid) 
 	{
 		if ($paperid != 0) {
			$sql = " select * from bnpcontent where paperid = $paperid order by time desc limit $offset, $page_size ";
		} else {
			$sql = " select * from bnpcontent order by time desc limit $offset, $page_size ";
		}
		$query = $this->db->query($sql);
		return $query->result();
 	}

 	function ischecked($bpArtId, $ischecked) 
 	{
		$sql = " update bnpcontent set checked = '$ischecked' where id = $bpArtId ";
		$this->db->query($sql);
	}

	function bpArtDel($bpArtId) 
	{
		$sql = " delete from bnpcontent where id = '$bpArtId' ";
		$this->db->query($sql);
	}

	function getBpArtDetail($bpArtId) 
	{
		$sql = "select * from bnpcontent where id = '$bpArtId'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bpArtUpdate($bpArtId, $title, $content, $pubUser, $checked, $time, $source) 
	{	
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " update bnpcontent set title = ?, content = ?, source = ?, time = '$time', 
				pub_user = '$pubUser', checked = '$checked' where id = '$bpArtId' ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("sss", $title, $content, $source);
		$stmt->execute();
		$stmt->close();
	}

	function getPic($id) 
	{
		$sql = "select * from bpaperspic where id = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getP($picId) 
	{
		$sql = "select pic from bpaperspic where id = '$picId'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function picUpdate($picId, $pic, $url) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($pic) == "") {
			$sql = " update bpaperspic set url = ? where id = '$picId' ";
		} else {
			$sql = " update bpaperspic set url = ?, pic = '$pic' where id = '$picId' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("s", $url);
		$stmt->execute();
		$stmt->close();
	}

/*
 * Edit By : LYJ
 * Time : 2015.8.14
 * Function : front end
 * Review : LYJ . 2016.1.22
 */
	function getBpList($offset, $page_size) 
	{
		$sql = "select * from bnewspaper order by pub_time desc limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getBp() 
	{
		$sql = "select * from bnewspaper order by pub_time desc";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getBpArtById($paperid) 
	{
		$sql = "select * from bnpcontent where paperid = $paperid and checked = 1 order by time desc";
		$query = $this->db->query($sql);
		return $query->result();
	}


}