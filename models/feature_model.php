<?php
class Feature_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}

/*
 *	Edit By : 阿诺
 *	Time : 2015.10.14
 *	function : 模型-子站点管理
 */	
	function featureAdd($name, $title, $info) {
		$sql = " insert into feature (name, title, info) values ('$name', '$title', '$info') ";
		$this->db->query($sql);
	}

	function getAllFeature($offset, $page_size) {
		$sql = "select * from feature limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getFeature($fid) {
		$sql = "select * from feature where fid = $fid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function featureUpdate($name, $title, $info, $fid) {
		$data = array(
            'name' => $name ,
            'title' => $title ,
            'info' => $info
        );
		$this->db->where('fid', $fid);
		$this->db->update('feature', $data); 
	}

/* logo */
	function getLogo($fid, $name) {
		$sql = "select * from feature_article where fid = $fid and title = '$name' and type = 1";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function logoAdd($pic, $title, $fid) {
		$sql = " insert into feature_article (title, pic, fid, type) values ('$title', '$pic', '$fid', '1') ";
		$this->db->query($sql);
	}

	function logoUpdate($fid, $pic, $title) {
		if (trim($pic) != "") {
			$sql = " update feature_article set pic = '$pic' where fid = $fid and title = '$title'  and type = 1";
			$this->db->query($sql);
		}
	}

/* category */
	function getCats($fid, $offset, $page_size) {
		$sql = "select * from feature_category where fid = '$fid' limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function catAdd($name, $info, $fid) {
		$sql = " insert into feature_category (name, info, fid) values ('$name', '$info', '$fid') ";
		$this->db->query($sql);
	}

	function getCat($fid, $cid) {
		$sql = "select * from feature_category where fid = $fid and cid = '$cid'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function catUpdate($name, $info, $cid) {
		$data = array(
            'name' => $name ,
            'info' => $info
        );
		$this->db->where('cid', $cid);
		$this->db->update('feature_category', $data); 
	}

/* banner */
	function getBanners($fid) {
		$sql = "select * from feature_article where fid = $fid and type = 2";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function bannerAdd($title, $url, $pic, $fid) {
		$sql = " insert into feature_article (title, url, pic, fid, type) values ('$title', '$url', '$pic', '$fid', '2') ";
		$this->db->query($sql);
	}

	function getBanner($fid, $id) {
		$sql = "select * from feature_article where fid = $fid and id = $id and type = 2";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bannerUpdate($title, $pic, $url, $id) {
		if (trim($pic) == "") {
			$data = array(
               		'title' => $title ,
               		'url' => $url
            	);
		} else {
			$data = array(
               		'title' => $title ,
               		'url' => $url ,
               		'pic' => $pic
            	);
		}
		$this->db->where('id', $id);
		$this->db->update('feature_article', $data); 
	}

/* 3article */
	function getArt3s($fid) {
		$sql = "select * from feature_article where fid = $fid and type = 3";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function art3Add($title, $content, $url, $pic, $fid) {
		$sql = " insert into feature_article (title, content, url, pic, fid, type) values ('$title', '$content', '$url', '$pic', '$fid', '3') ";
		$this->db->query($sql);
	}

	function getArt3($fid, $id) {
		$sql = "select * from feature_article where fid = $fid and id = $id and type = 3";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function art3Update($title, $content, $pic, $url, $id) {
		if (trim($pic) == "") {
			$data = array(
               		'title' => $title ,
               		'content' => $content ,
               		'url' => $url
            	);
		} else {
			$data = array(
               		'title' => $title ,
               		'content' => $content ,
               		'url' => $url ,
               		'pic' => $pic
            	);
		}
		$this->db->where('id', $id);
		$this->db->update('feature_article', $data); 
	}

/* article */	
	function getArts($offset, $page_size, $fid, $cid) {
		$sql = "select * from feature_article where fid = '$fid' and cid = '$cid' and type = 4 order by time desc limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getArt($fid, $cid, $id) {
		$sql = "select * from feature_article where fid = '$fid' and cid = '$cid' and id = '$id' and type = 4";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function artAdd($title, $content, $time, $video, $fid, $cid) {
		$sql = " insert into feature_article (title, content, time, video, fid, cid, type) values ('$title', '$content', '$time', '$video', '$fid', '$cid', 4) ";
		$this->db->query($sql);
	}

	function artUpdate($title, $content, $time, $video, $id) {
		if (trim($video) == "") {
			$data = array(
               		'title' => $title ,
               		'content' => $content ,
               		'time' => $time
            	);
		} else {
			$data = array(
               		'title' => $title ,
               		'content' => $content ,
               		'video' => $video ,
               		'time' => $time
            	);
		}
		$this->db->where('id', $id);
		$this->db->update('feature_article', $data); 
	}

	function artDel($fid, $cid, $id) {
		$sql = " delete from feature_article where fid = '$fid' and cid = '$cid' and id = '$id' and type = 4 ";
		$this->db->query($sql);
	}











}