<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feature_model extends CI_Model 
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
 * Time: 2015.10.14
 * Function: sub site
 * Review: LYJ . 2016.1.23
 */		
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

	function featureAdd($name, $title, $info) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into feature (name, title, info) values (?, ?, ?) ";
  		$stmt = $this->db->conn_id->prepare($sql);
	  	$stmt->bind_param("sss", $name, $title, $info);
	  	$stmt->execute();
	  	$stmt->close();
	}

	function getAllFeature($offset, $page_size) 
	{
		$sql = "select * from feature limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getFeature($fid) 
	{
		$sql = "select * from feature where fid = $fid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function featureUpdate($name, $title, $info, $fid) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " update feature set name = ?, title = ?, info = ? where fid = '$fid' ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("sss", $name, $title, $info);
		$stmt->execute();
		$stmt->close();
	}

	/*-- logo --*/
	function getLogo($fid, $name) 
	{
		$sql = "select * from feature_article where fid = $fid and title = '$name' and type = 1";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function logoAdd($pic, $title, $fid) 
	{
		$sql = " insert into feature_article (title, pic, fid, type) values ('$title', '$pic', '$fid', '1') ";
		$this->db->query($sql);
	}

	function logoUpdate($fid, $pic, $title) 
	{
		if (trim($pic) != "") {
			$sql = " update feature_article set pic = '$pic' where fid = $fid and title = '$title'  and type = 1";
			$this->db->query($sql);
		}
	}

	/*-- category --*/
	function getCats($fid, $offset, $page_size) 
	{
		$sql = "select * from feature_category where fid = '$fid' limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function catAdd($name, $info, $fid) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into feature_category (name, info, fid) values (?, ?, '$fid') ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $name, $info);
		$stmt->execute();
		$stmt->close();
	}

	function getCat($fid, $cid) 
	{
		$sql = "select * from feature_category where fid = $fid and cid = '$cid'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function catUpdate($name, $info, $cid) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " update feature_category set name = ?, info = ? where cid = '$cid' ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $name, $info);
		$stmt->execute();
		$stmt->close();
	}

	/*-- banner --*/
	function getBanners($fid) 
	{
		$sql = "select * from feature_article where fid = $fid and type = 2";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function bannerAdd($title, $url, $pic, $fid) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into feature_article (title, url, pic, fid, type) values (?, ?, '$pic', '$fid', '2') ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $title, $url);
		$stmt->execute();
		$stmt->close();
	}

	function getBanner($fid, $id) 
	{
		$sql = "select * from feature_article where fid = $fid and id = $id and type = 2";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bannerUpdate($title, $pic, $url, $id) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($pic) == "") {
			$sql = " update feature_article set title = ?, url = ? where id = '$id' ";
		} else {
			$sql = " update feature_article set title = ?, url = ?, pic = '$pic' where id = '$id' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $title, $url);
		$stmt->execute();
		$stmt->close();
	}

	/*-- 3article --*/
	function getArt3s($fid) 
	{
		$sql = "select * from feature_article where fid = $fid and type = 3";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function art3Add($title, $content, $url, $pic, $fid) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into feature_article (title, content, url, pic, fid, type) values (?, ?, ?, '$pic', '$fid', '3') ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("sss", $title, $content, $url);
		$stmt->execute();
		$stmt->close();
	}

	function getArt3($fid, $id) 
	{
		$sql = "select * from feature_article where fid = $fid and id = $id and type = 3";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function art3Update($title, $content, $pic, $url, $id) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($pic) == "") {
			$sql = " update feature_article set title = ?, content = ?, url = ? where id = '$id' ";
		} else {
			$sql = " update feature_article set title = ?, content = ?, url = ?, pic = '$pic' where id = '$id' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("sss", $title, $content, $url);
		$stmt->execute();
		$stmt->close();
	}

	/*-- article --*/	
	function getArts($offset, $page_size, $fid, $cid) 
	{
		$sql = "select * from feature_article where fid = '$fid' and cid = '$cid' and type = 4 order by time desc limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getArt($fid, $cid, $id) 
	{
		$sql = "select * from feature_article where fid = '$fid' and cid = '$cid' and id = '$id' and type = 4";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function artAdd($title, $content, $time, $video, $fid, $cid) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into feature_article (title, content, time, video, fid, cid, type) values (?, ?, ?, '$video', '$fid', '$cid', 4) ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("sss", $title, $content, $time);
		$stmt->execute();
		$stmt->close();
	}

	function artUpdate($title, $content, $time, $video, $id) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($video) == "") {
			$sql = " update feature_article set title = ?, content = ?, time = ? where id = '$id' ";
		} else {
			$sql = " update feature_article set title = ?, content = ?, video = '$video', time = ? where id = '$id' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("sss", $title, $content, $time);
		$stmt->execute();
		$stmt->close();
	}

	function artDel($fid, $cid, $id) 
	{
		$sql = " delete from feature_article where fid = '$fid' and cid = '$cid' and id = '$id' and type = 4 ";
		$this->db->query($sql);
	}

}