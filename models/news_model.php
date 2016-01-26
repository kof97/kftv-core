<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_model extends CI_Model 
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
 * Time: 2015.7.15
 * Function: catgory
 * Review: LYJ . 2016.1.20
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

	function getAllCat($offset, $pageSize) 
	{
		$sql = "select * from news_category limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getCatMessage($cid) 
	{
		$sql = "select * from news_category where category_id = $cid ";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function using($cid, $use) 
	{	
		$sql = " update news_category set used = '$use' where category_id = $cid ";
		return $this->db->query($sql);
	}

	function catAdd($catName, $catDetail, $using) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into news_category (category_name ,category_detail ,used) values (?, ?, ?) ";
  		$stmt = $this->db->conn_id->prepare($sql);
	  	$stmt->bind_param("ssi", $catName, $catDetail, $using);

	  	$stmt->execute();
	  	$stmt->close();
	}

	function catUpdate($cid, $catName, $catDetail, $using) 
	{
		$this->db->trans_start();
			$stmt = $this->db->conn_id->stmt_init();
			// update category
			$sql = " update news_category set category_name = ?, category_detail = ?, used = '$using' where category_id = '$cid' ";
	  		$stmt = $this->db->conn_id->prepare($sql);
		  	$stmt->bind_param("ss", $catName, $catDetail);
		  	$stmt->execute();

		  	// update news
		  	$sql = " update news_article set category_name = ? where category_id = $cid ";
		  	$stmt = $this->db->conn_id->prepare($sql);
		  	$stmt->bind_param("s", $catName);
		  	$stmt->execute();

		  	$stmt->close();
	  	$this->db->trans_complete();
	}

	function catDel($cid) 
	{
		$this->db->trans_start();
			// delete catgory
			$sql = " delete from news_category where category_id = '$cid' ";
			$this->db->query($sql);
			// update news 
			$sql = " update news_article set category_name = '栏目已删除' where category_id = '$cid' ";
			$this->db->query($sql);
		$this->db->trans_complete();
	}

	function getUsingCat() 
	{
		$sql = "select * from news_category where used = 1";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getCatNameById($catId) 
	{
		$sql = "select category_name from news_category where category_id = $catId";
		$query = $this->db->query($sql);
      	return $query->row()->category_name;
	}
/**
 * Edit By: LYJ
 * Time: 2015.7.15
 * Review: LYJ . 2016.1.21
 */
	function artAdd($artTitle, $artContent, $source, $pubTime, $pubUser, $top, $hasVideo, $videoUrl, $isChecked, $cid, $catName, $checkC) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " insert into news_article (article_title, article_content, source, pub_time, pub_user, top, 
				has_video, video_url, ischecked, category_id, category_name, comment_checked) values (?, ?, ?, 
				'$pubTime', '$pubUser', '$top', '$hasVideo', '$videoUrl', '$isChecked', '$cid', ?, '$checkC') ";
	  	$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ssss", $artTitle, $artContent, $source, $catName);
		$stmt->execute();
		$stmt->close();
	}

/**
 * Edit By: LYJ
 * Time: 2015.7.15
 * Function: news
 * Review: LYJ . 2016.1.21
 */
	function getAllNews($offset, $pageSize) 
	{
		$sql = "select * from news_article order by pub_time desc limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getNewsByCatId($catId, $offset, $pageSize) 
	{
		$sql = " select * from news_article where category_id = '$catId' order by pub_time desc limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getSearchNews($offset, $pageSize, $catId, $searchKey, $order) 
	{	
		switch ($order) {
			case '1': $ordered = "category_name"; break;
			case '2': $ordered = "pub_time"; break;
			case '3': $ordered = "has_video"; break;
			case '4': $ordered = "ischecked"; break;
			case '5': $ordered = "comment_count"; break;
			case '6': $ordered = "hits"; break;
			default: $ordered = "pub_time"; break;
		}
		if ($catId != 0) {
			$sql = " select * from news_article where article_title like '%$searchKey%' and category_id = $catId order by $ordered desc limit $offset, $pageSize ";
		} else {
			$sql = " select * from news_article where article_title like '%$searchKey%' order by $ordered desc limit $offset,$pageSize ";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getNewsDetail($artId) 
	{
		$sql = "select * from news_article where article_id = $artId ";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function ischecked($artId, $ischecked) 
	{
		$sql = " update news_article set ischecked = '$ischecked' where article_id = $artId ";
		return $this->db->query($sql);
	}

	function artDel($artId) 
	{
		$this->db->trans_start();
			// delete article
			$sql = " delete from news_article where article_id = '$artId' ";
			$this->db->query($sql);
			// delete comment
			$sql = " delete from news_comment where article_id = '$artId' ";
			$this->db->query($sql);
		$this->db->trans_complete();
	}

	function artUpdate($artId, $artTitle, $artContent, $source, $pubTime, $pubUser, $top, $hasVideo, $videoUrl, $isChecked, $checkC) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($videoUrl) == "") {
			$sql = " update news_article set article_title = ?, article_content = ?, source = ?, pub_time = '$pubTime',  
					pub_user = '$pubUser', top = '$top', ischecked = '$isChecked', comment_checked = '$checkC' 
					where article_id = '$artId' ";
		} else {
			$sql = " update news_article set article_title = ?, article_content = ?, source = ?, pub_time = '$pubTime',  
					pub_user = '$pubUser', top = '$top', has_video = '$hasVideo', video_url = '$videoUrl', 
					ischecked = '$isChecked', comment_checked = '$checkC' where article_id = '$artId' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("sss", $artTitle, $artContent, $source);
		$stmt->execute();
		$stmt->close();
	}

	function artJumpUpdate($artId, $artTitle, $artContent, $pubTime, $pubUser) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = " update news_article set article_title = ?, article_content = ?, pub_time = '$pubTime',  
				pub_user = '$pubUser' where article_id = '$artId' ";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $artTitle, $artContent);
		$stmt->execute();
		$stmt->close();
	}

	function getVideoUrl($artId) 
	{
		$sql = "select video_url from news_article where article_id = '$artId'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getVideo($videoId) 
	{
		$sql = "select * from news_video where id = '$videoId'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getVideoU($videoId) 
	{
		$sql = "select video_url from news_video where id = $videoId";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function videoUpdate($videoId, $videoUrl) 
	{
		$sql = " update news_video set video_url = '$videoUrl' where id = $videoId ";
		$this->db->query($sql);
	}

	function changeStopS($stopId, $stopS) 
	{
		$sql = "update news_video set video_url = '$stopS' where id = '$stopId'";
		$this->db->query($sql);
	}

/**
 * Edit By: LYJ
 * Time: 2015.7.15
 * Function: comment
 * Review: LYJ . 2016.1.21
 */
	function getArtNameById($artId) 
	{
		$sql = "select article_title from news_article where article_id = $artId ";
		$query = $this->db->query($sql);
      	return $query->row()->article_title;
	}

	function getCommentsByNew($artId, $offset, $pageSize) 
	{
		$sql = "select * from news_comment where article_id = $artId order by comment_time desc limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function commentDel($commentId) 
	{
		$sql = " delete from news_comment where comment_id = '$commentId' ";
		$this->db->query($sql);
	}

	function commentChecked($commentId, $ischecked) 
	{
		$sql = " update news_comment set ischecked_c = '$ischecked' where comment_id = $commentId ";
		return $this->db->query($sql);
	}

	function getUncheckedComments($offset, $pageSize) 
	{
		$sql = "select * from news_comment where ischecked_c = 0 order by comment_time desc limit $offset, $pageSize";
      	$query = $this->db->query($sql);
      	return $query->result_array();
	}

	function getTotalComment() 
	{
		$sql = "select * from news_comment where ischecked_c = 0 ";
		$count = count( $this->db->query($sql)->result() );
		return $count;
	}

/**
 * Edit By: LYJ
 * Time: 2015.7.15
 * Function: picture
 * Review: LYJ . 2016.1.22
 */
	function getPic($picId) 
	{
		$sql = "select * from news_pic where id = $picId";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function getPicUrl($picId) 
	{
		$sql = "select * from news_pic where id = $picId";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function picUpdate($picId, $picUrl, $picLink) 
	{
		$stmt = $this->db->conn_id->stmt_init();
		if (trim($picUrl) == "") {
			$sql = " update news_pic set pic_link = ? where id = '$picId' ";
		} else {
			$sql = " update news_pic set pic_link = ?, pic_url = '$picUrl' where id = '$picId' ";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("s", $picLink);
		$stmt->execute();
		$stmt->close();
	}

/**
 * Edit By: LYJ
 * Time: 2015.7.17
 * Function: front end
 * Review: LYJ . 2016.1.22
 */	
	function getNewsByCat($offset, $pageSize, $catId) 
	{
		$sql = "select * from news_article where category_id = $catId and ischecked = 1 order by pub_time desc limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getPicNewsByCat($offset, $pageSize, $catId) 
	{
		$sql = "select * from news_article where category_id = $catId and ischecked = 1 and article_content like '%<img%' order by pub_time desc limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getVideoPicNews($offset, $pageSize, $hasVideo) 
	{
		$sql =  "select * from news_article where has_video = $hasVideo and ischecked = 1 and article_content like '%<img%' order by pub_time desc limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getVideoNews($offset, $pageSize, $hasVideo) 
	{
		$sql =  "select * from news_article where has_video = $hasVideo and ischecked = 1 order by pub_time desc limit $offset, $pageSize ";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getZixunjingxuan() 
	{
		$arr = array(6, 9, 10, 11, 12, 13, 14, 16, 17, 18, 19, 5);
		$query = array();
		foreach ($arr as $v) {
			$sql = "select * from news_article where category_id = $v and ischecked = 1 order by pub_time desc limit 0, 1";
			$query = array_merge($query, $this->db->query($sql)->result_array());	
		}
		return $query;
	}

	function getRecentlyNews($offset, $pageSize) 
	{
		$sql = "select * from news_article where ischecked = 1 order by pub_time desc limit $offset, $pageSize";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getHitsNews($offset, $pageSize) 
	{
		$sql = "select * from news_article where ischecked = 1 order by hits desc limit $offset, $pageSize";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getNewsComments($offset, $pageSize, $artId) 
	{
		$sql = "select * from news_comment where article_id = '$artId' and ischecked_c = 1 order by comment_time desc limit $offset, $pageSize";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getCommentsCount($artId) 
	{
		$this->db->from('news_comment');
		$this->db->where('article_id', $artId);
		$this->db->where('ischecked_c', 1);
		$total = $this->db->count_all_results();
		return $total;
	}

/**
 * Edit By: LYJ
 * Time: 2015.7.22
 * Function: front end operate
 * Review: LYJ . 2016.1.22
 */	
	function addHits($artId) 
	{
		$sql = "select hits from news_article where article_id = '$artId'";
		$hit = $this->db->query($sql)->result_array();
		$hit = $hit[0]['hits'] + 1;

		$sql = "update news_article set hits = '$hit' where article_id = '$artId'";
		$this->db->query($sql);
	}

	function addComment($commentContent, $time, $ischecked_c, $artId, $pubUser, $pubUserId = "") 
	{
		$s = "select comment_count from news_article where article_id = '$artId'";
		$num = $this->db->query($s)->row()->comment_count;
		$num += 1;
		$sq = "update news_article set comment_count = '$num' where article_id = '$artId'";
		$this->db->query($sq);

		$stmt = $this->db->conn_id->stmt_init();
		if (trim($pubUserId) == "") {
			$sql = "insert into news_comment (comment_content, comment_time, ischecked_c, article_id, comment_user) 
					values (?, '$time', '$ischecked_c', '$artId', '$pubUser')";
		} else {
			$sql = "insert into news_comment (comment_content, comment_time, ischecked_c, use_comment_id, article_id, 
					comment_user) values (?, '$time', '$ischecked_c', '$pubUserId', '$artId', '$pubUser')";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("s", $commentContent);
		$stmt->execute();
		$stmt->close();
	}

}