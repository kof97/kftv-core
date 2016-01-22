<?php
class Bpapers_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}
	
/*
*	Edit By : 阿诺
*	Time : 2015.8.13
*	function : 模型-报刊管理
*/
	function getAllbp($offset, $page_size) {
		$sql = "select * from bnewspaper order by pub_time desc limit $offset, $page_size ";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function bpAdd($nowIssue, $totalIssue, $picture, $time, $pubTime) {
		$sql = " insert into bnewspaper (issue_now, issue_total, picture, time, pub_time) values ('$nowIssue', '$totalIssue', '$picture', '$time', '$pubTime') ";
		$this->db->query($sql);
	}

	function getBpById($bid) {
		$sql = "select * from bnewspaper where id = $bid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bpUpdate($nowIssue, $totalIssue, $picture, $time, $pubTime, $bid) {
		if (trim($picture) == "") {
			$data = array(
		            'issue_now' => $nowIssue ,
		            'issue_total' => $totalIssue ,
		            'time' => $time ,
		            'pub_time' => $pubTime
		        );
		} else {
			$data = array(
		            'issue_now' => $nowIssue ,
		            'issue_total' => $totalIssue ,
		            'picture' => $picture ,
		            'time' => $time ,
		            'pub_time' => $pubTime
		        );
		}
		$this->db->where('id', $bid);
		$this->db->update('bnewspaper', $data); 
	}

	function getPicUrl($bid) {
		$sql = "select picture from bnewspaper where id = $bid";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bpDel($bid) {
		$sql = " delete from bnewspaper where id = '$bid' ";
		$res = $this->db->query($sql);
		return $res;
	}

	function bpArtDelById($bid) {
		$sql = " delete from bnpcontent where paperid = '$bid' ";
		$this->db->query($sql);
	}

	function getOrderbp($offset, $page_size, $order) {
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
*	Edit By : 阿诺
*	Time : 2015.8.13
*	function : 模型-报刊文章管理
*/
	function bpArtAdd($paperid, $title, $content, $pub_user, $checked, $time, $source) {
		$sql = " insert into bnpcontent (paperid, title, content, pub_user, checked, time, source) values ('$paperid', '$title', '$content', '$pub_user', '$checked', '$time', '$source') ";
		$this->db->query($sql);
	}

 	function getBpArt($offset, $page_size, $paperid) {
 		if ($paperid != 0) {
			$sql = " select * from bnpcontent where paperid = $paperid order by time desc limit $offset, $page_size ";
		} else {
			$sql = " select * from bnpcontent order by time desc limit $offset, $page_size ";
		}
		$query = $this->db->query($sql);
		return $query->result();
 	}

 	function ischecked($bpArtId, $ischecked) {
		$sql = " update bnpcontent set checked = '$ischecked' where id = $bpArtId ";
		$this->db->query($sql);
	}

	function bpArtDel($bpArtId) {
		$sql = " delete from bnpcontent where id = '$bpArtId' ";
		$this->db->query($sql);
	}

	function getBpArtDetail($bpArtId) {
		$sql = "select * from bnpcontent where id = '$bpArtId'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function bpArtUpdate($bpArtId, $title, $content, $pub_user, $checked, $time, $source) {
		$data = array(
			'title' => $title ,
			'content' => $content ,
			'pub_user' => $pub_user ,
			'checked' => $checked ,
			'time' => $time ,
			'source' => $source
		);
		$this->db->where('id', $bpArtId);
		$this->db->update('bnpcontent', $data); 
	}

	function getPic($id) {
		$sql = "select * from bpaperspic where id = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getP($picId) {
		$sql = "select pic from bpaperspic where id = '$picId'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function picUpdate($picId, $pic, $url) {
		if (trim($pic) == "") {
			$data = array('url' => $url);
		} else {
			$data = array(
		            'pic' => $pic ,
		            'url' => $url
		    );
		}
		$this->db->where('id', $picId);
		$this->db->update('bpaperspic', $data); 
	}

/*
*	Edit By : 阿诺
*	Time : 2015.8.14
*	function : 模型-前台输出
*/
	function getBpList($offset, $page_size) {
		$sql = "select * from bnewspaper order by pub_time desc limit $offset, $page_size";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getBp() {
		$sql = "select * from bnewspaper order by pub_time desc";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getBpArtById($paperid) {
		$sql = "select * from bnpcontent where paperid = $paperid and checked = 1 order by time desc";
		$query = $this->db->query($sql);
		return $query->result();
	}





}