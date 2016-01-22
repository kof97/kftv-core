<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News extends Ci_Controller 
	{
		public static $userName;

		public function __construct() 
		{
			parent::__construct();

			session_start();
			$ok = $_SESSION['admin'];
	 		if (!isset($ok) || $ok != 'kftv') {
	 			redirect("admin/login/denglu");
	 		}
	 		self::$userName = $_SESSION['username'];
			self::$userName = self::$userName;

			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
			$this->load->model('news_model');
 		}
/*
 * Edit By : LYJ
 * Time : 2015.7.14
 * Function : category
 * Review : LYJ . 2016.1.20
 */
		public function categoryManage() 
		{
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('news_category');
			$total = $this->db->count_all_results();
			$pageUri = 'news/news/categoryManage';

			$data['page'] = $this->news_model->page($pageSize, $offset, $total, $pageUri);

			$data['total'] = $total;
			$data['category'] = $this->news_model->getAllCat($offset, $pageSize);

			$this->load->view('admin/news/cat_list', $data);
		}

		public function using() 
		{
			$cid = intval($this->input->get("cid"));
			$use = intval($this->input->get("using"));
			// check cid and use
			if ($use != 0 && $use != 1) {
				exit("error in cid and use!");
			}
			$status = $this->news_model->using($cid, $use)?1:0;
			echo $status;
		}

		public function catAddView() 
		{
			$this->load->view('admin/news/cat_add');
		}

		public function catAdd() 
		{
			$catName = $this->input->post('catName', true);
			$catDetail = $this->input->post('catDetail', true);
			$using = $this->input->post('using')?1:0;
			
			$this->news_model->catAdd($catName, $catDetail, $using);
			redirect('news/news/categoryManage');
		}

		public function catEdit() 
		{
			$cid = intval($this->uri->segment(4));
			$data['cat'] = $this->news_model->getCatMessage($cid);

			$this->load->view('admin/news/cat_edit', $data);
		}

		public function catUpdate() 
		{
			$cid = intval($this->uri->segment(4));
			$catName = $this->input->post('catName', true);
			$catDetail = $this->input->post('catDetail', true);
			$using = $this->input->post('using')?1:0;
			
			$this->news_model->catUpdate($cid, $catName, $catDetail, $using);
			redirect('news/news/categoryManage');
		}

		public function catDel()
		{
			$cid = intval($this->uri->segment(4));
			$this->news_model->catDel($cid);

			redirect('news/news/categoryManage');
		}

/*
 * Edit By : LYJ
 * Time : 2015.7.15
 * Function : news
 * Review : LYJ . 2016.1.21
 */
		public function artAddView() 
		{
			$data['pubUser'] = self::$userName;
			$data['usingCat'] = $this->news_model->getUsingCat();
			$this->load->view('admin/news/art_add', $data);
		}

		public function artAddViewJump() 
		{
			$data['pubUser'] = self::$userName;
			$data['usingCat'] = $this->news_model->getUsingCat();
			$this->load->view('admin/news/art_add_jump', $data);
		}

		public function artAdd() 
		{
			/*-- video --*/
			$savePath = 'KFTVresource/News/video/';
			$data = $this->news_model->video($savePath);

			$file_name = $data['upload_data']['file_name'];
			$hasVideo = $data?1:0;
			$videoUrl = $data?$savePath . $file_name:"";

			$artTitle = $this->input->post('title', true);
			$artContent = $this->input->post('artContent', true);
			$source = $this->input->post('source', true);
			$pubUser = self::$userName;
			$pubTime = $this->input->post('pubTime') . " " . date("h:i:s");
			$top = $this->input->post('top')?1:0;
			$isChecked = $this->input->post('isChecked')?1:0;
			$checkC = $this->input->post('radio');

			$catId = $this->input->post("check");

			$this->db->trans_start();
			foreach ($catId as $cid) {
				$cid = intval($cid);
				$catName = $this->news_model->getCatNameById($cid);
				$this->news_model->artAdd($artTitle, $artContent, $source, $pubTime, $pubUser, $top, $hasVideo, $videoUrl, $isChecked, $cid, $catName, $checkC);
			}
			$this->db->trans_complete();

			//echo "<script>alert('添加成功');</script>";
			redirect("news/news/artAddView");
		}

		public function artAddJump() 
		{
			$hasVideo = 0;
			$videoUrl = "";

			$artTitle = $this->input->post('title', true);
			$artContent = trim($this->input->post('artContent', true));
			if ($artContent == "") {
				$artContent = "<script>location.href = '" . base_url() . "'</script>";
			} else {
				$artContent = "<script>location.href = '" . $artContent . "'</script>";
			}
			$source = "";
			$pubUser = self::$userName;
			$pubTime = date("Y-m-d h:i:s");
			$top = 0;
			$isChecked = 1;
			$checkC = 2;

			$catId = $this->input->post("check");
			$this->db->trans_start();
			foreach ($catId as $cid) {
				$cid = intval($cid);
				$catName = $this->news_model->getCatNameById($cid);
				$this->news_model->artAdd($artTitle, $artContent, $source, $pubTime, $pubUser, $top, $hasVideo, $videoUrl, $isChecked, $cid, $catName, $checkC);
			}
			$this->db->trans_complete();

			redirect("news/news/artAddViewJump");
		}
/*
 * Edit By : LYJ
 * Time : 2015.7.15
 * Function : news
 * Review : LYJ . 2016.1.21
 */
		public function showNews() 
		{
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('news_article');
			$total = $this->db->count_all_results();
			$pageUri = 'news/news/showNews';
			
			$data['page'] = $this->news_model->page($pageSize, $offset, $total, $pageUri);
			$data['total'] = $total;
			$data['searchKey'] = "";
			$data['usingCat'] = $this->news_model->getUsingCat();
			$data['artList'] = $this->news_model->getAllNews($offset, $pageSize);
			$this->load->view('admin/news/art_list', $data);
		}

		public function searchNews()
		{
			$catId = $this->input->post('select');
			$catId = $catId?intval($catId):0;
			$searchKey = $this->input->post('key');
			$order = intval($this->uri->segment(4));

			/*-- page --*/
			$pageSize = 20;

			$this->db->from('news_article');
			$this->db->like('article_title', $searchKey); 

			if ($catId != 0) {
				$this->db->where('category_id', $catId);
			}
			$total = $this->db->count_all_results();
			$offset = intval($this->uri->segment(5));
			$pageUri = 'news/news/searchNews/' . $order;

			$data['page'] = $this->news_model->page($pageSize, $offset, $total, $pageUri, 5);
			$data['total'] = $total;
			$data['catId'] = $catId;
			$data['searchKey'] = $searchKey;
			$data['usingCat'] = $this->news_model->getUsingCat();
			$data['artList'] = $this->news_model->getSearchNews($offset, $pageSize, $catId, $searchKey, $order);
			$this->load->view('admin/news/art_list', $data);
		}

		public function ischecked() 
		{
			$artId = intval($this->input->get("id"));
			$ischecked = intval($this->input->get("check"));
			// check artId and ischecked
			if ( $ischecked != 0 && $ischecked != 1 ) {
				exit("error in cid and use!");
			}
			$status = $this->news_model->ischecked($artId, $ischecked)?1:0;
			echo $status;
		}

		public function artDel() 
		{
			$check = $this->input->post('check');
			foreach ($check as $artId) {
				$artId = intval($artId);
				$this->news_model->artDel($artId);
			}
			redirect('news/news/showNews');
		}

		public function showNewsDetail() 
		{
			$artId = intval($this->uri->segment(4));

			$data['usingCat'] = $this->news_model->getUsingCat();
			$data['artDetail'] = $this->news_model->getNewsDetail($artId);

			$this->load->view('admin/news/art_detail', $data);
		}

		public function showJumpNewsDetail() 
		{
			$artId = intval($this->uri->segment(4));

			$data['usingCat'] = $this->news_model->getUsingCat();
			$data['artDetail'] = $this->news_model->getNewsDetail($artId);

			$this->load->view('admin/news/art_detail_j', $data);
		}

		public function artUpdate() {
			/*-- video --*/
			$savePath = 'KFTVresource/News/video/';
			$data = $this->news_model->video($savePath);

			$file_name = $data['upload_data']['file_name'];
			$hasVideo = $data?1:0;
			$videoUrl = $data?$savePath . $file_name:"";

			$artId = intval($this->uri->segment(4));

			$artTitle = $this->input->post('title', true);
			$artContent = $this->input->post('artContent', true);
			$source = $this->input->post('source', true);
			$pubUser = self::$userName;
			$pubTime = $pubTime = $this->input->post('pubTime') . " " . date("h:i:s");
			$top = $this->input->post('top')?1:0;
			$isChecked = $this->input->post('isChecked')?1:0;
			$checkC = $this->input->post('radio');

			$oldVideoUrl = $this->news_model->getVideoUrl($artId);

			if ($data) {
				$path = BASEPATH . "../" . $oldVideoUrl->video_url;
				$result = @unlink ($path); 
			}
			$this->news_model->artUpdate($artId, $artTitle, $artContent, $source, $pubTime, $pubUser, $top, $hasVideo, $videoUrl, $isChecked, $checkC);
			redirect('news/news/showNews');
		}

		public function artJumpUpdate() {
			$artId = intval($this->uri->segment(4));
			$artTitle = $this->input->post('title', true);
			$artContent = trim($this->input->post('artContent', true));
			if ($artContent == "") {
				$artContent = "<script>location.href = '" . base_url() . "'</script>";
			} else {
				$artContent = "<script>location.href = '" . $artContent . "'</script>";
			}
			$pubUser = self::$userName;
			$pubTime = date("Y-m-d h:i:s");

			$this->news_model->artJumpUpdate($artId, $artTitle, $artContent, $pubTime, $pubUser);
			redirect('news/news/showNews');
		}

/*
 * Edit By : LYJ
 * Time : 2015.7.26
 * Function : video
 * Review : LYJ . 2016.1.21
 */
		public function videoManage() 
		{
			$data['video1'] = $this->news_model->getVideo(1);
			$data['video2'] = $this->news_model->getVideo(2);
			$data['video3'] = $this->news_model->getVideo(3);
			$data['imgStop'] = $this->news_model->getVideo(4);
			$data['video5'] = $this->news_model->getVideo(5);
			$data['stopS'] = $this->news_model->getVideo(6);

			$this->load->view('admin/news/video_manage', $data);		
		}

		public function changeStopS() 
		{
			$stopId = intval($this->input->post("stopId"));
			$stopS = intval($this->input->post("stopS"));
			$this->news_model->changeStopS($stopId, $stopS);
			
			redirect('news/news/videoManage');
		}

		public function videoAddView() 
		{
			$data['videoId'] = intval($this->uri->segment(4));
			$this->load->view('admin/news/video_add', $data);	
		}

		public function videoAdd() 
		{
			$videoId = intval($this->uri->segment(4));

			/*-- video --*/
			$savePath = 'KFTVresource/News/video/';
			$data = $this->news_model->video($savePath);

			$file_name = $data['upload_data']['file_name'];
			$videoUrl = $data?$savePath . $file_name:"";

			$oldVideoUrl = $this->news_model->getVideoU($videoId);

			if ($data) {
				$path = BASEPATH . "../" . $oldVideoUrl->video_url;
				$result = @unlink ($path); 
			}
			$this->news_model->videoUpdate($videoId, $videoUrl);
			redirect('news/news/videoManage');
		}

		public function imgAdd() 
		{
			/*-- picture --*/
			$savePath = 'KFTVresource/News/pic/';
			$data = $this->news_model->picture($savePath);
			
			$file_name = $data['upload_data']['file_name'];
			$videoUrl = $data?$savePath . $file_name:"";
			$videoId = intval($this->uri->segment(4));

			$oldVideoUrl = $this->news_model->getVideoU($videoId);

			if ($data) {
				$path = BASEPATH . "../" . $oldVideoUrl->video_url;
				$result = @unlink ($path); 
			}
			$this->news_model->videoUpdate($videoId, $videoUrl);
			redirect('news/news/videoManage');			
		}

		public function videoView() 
		{
			$videoId = intval($this->uri->segment(4));
			$data['artDetail'] = $this->news_model->getVideoU($videoId);
			$this->load->view('admin/news/video_view', $data);			
		}

/*
 * Edit By : LYJ
 * Time : 2015.7.16
 * Function : comment
 * Review : LYJ . 2016.1.21
 */
		public function showCommentsByNew() {
			$artId = intval($this->uri->segment(4));
			/*-- page --*/
			$pageSize = 20;

			$this->db->from('news_comment');
			$this->db->where('article_id', $artId);
			$total = $this->db->count_all_results();
			$offset = intval($this->uri->segment(5));
			$pageUri = 'news/news/showCommentsByNew/' . $artId;

			$data['page'] = $this->news_model->page($pageSize, $offset, $total, $pageUri, 5);
			$data['total'] = $total;

			$data['artName'] = $this->news_model->getArtNameById($artId);
			$data['commentList'] = $this->news_model->getCommentsByNew($artId, $offset, $pageSize);
			$this->load->view('admin/news/news_comment', $data);
		}

		public function commentDel() {
			$artId = intval($this->uri->segment(4));
			$check = $this->input->post('check');
			foreach ($check as $commentId) {
				$commentId = intval($commentId);
				$this->news_model->commentDel($commentId);
			}
			redirect('news/news/showCommentsByNew/' . $artId);
		}

		public function commentDel_u() {
			$check = $this->input->post('check');
			foreach ($check as $commentId) {
				$this->news_model->commentDel($commentId);
			}
			redirect('news/news/commentUnchecked/');
		}

		public function commentChecked() {
			$commentId = intval($this->input->get("id"));
			$ischecked = intval($this->input->get("check"));
			$status = $this->news_model->commentChecked($commentId, $ischecked);
			echo $status;
		}

		public function commentChecked_u() {
			$commentId = intval($this->input->get("id"));
			$ischecked = intval($this->input->get("check"));
			$status = $this->news_model->commentChecked($commentId, $ischecked);
			echo $status;
		}

		public function commentUnchecked() {
			/*-- page --*/
			$pageSize = 20;
			$total = $this->news_model->getTotalComment();
			$offset = intval($this->uri->segment(4));
			$pageUri = 'news/news/commentUnchecked/';
	
			$data['page'] = $this->news_model->page($pageSize, $offset, $total, $pageUri);
			$data['total'] = $total;
			$data['commentList'] = $this->news_model->getUncheckedComments($offset, $pageSize);

			$this->load->view('admin/news/news_c_unchecked', $data);
		}

/*
*	Edit By : 阿诺
*	Time : 2015.7.23
*	function : 后台控制器-图片管理
*/
		public function picManage() {
			

			/*----head----*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);

			$data['pic6'] = $this->news_model->getPicUrl(6);
			$data['pic7'] = $this->news_model->getPicUrl(7);
			$data['pic8'] = $this->news_model->getPicUrl(8);
			$data['pic9'] = $this->news_model->getPicUrl(9);
			$data['pic10'] = $this->news_model->getPicUrl(10);
			$data['pic11'] = $this->news_model->getPicUrl(11);
			$data['pic12'] = $this->news_model->getPicUrl(12);
			$data['pic13'] = $this->news_model->getPicUrl(13);
			$data['pic14'] = $this->news_model->getPicUrl(14);
			$data['pic15'] = $this->news_model->getPicUrl(15);
			$data['pic16'] = $this->news_model->getPicUrl(16);
			$data['pic17'] = $this->news_model->getPicUrl(17);
			$data['pic26'] = $this->news_model->getPicUrl(26);

			$data['pic23'] = $this->news_model->getPicUrl(23);
			$data['pic24'] = $this->news_model->getPicUrl(24);
			$data['pic25'] = $this->news_model->getPicUrl(25);

			$this->load->view('admin/news/pic_view', $data);
		}

		public function picManageL() {
			

			/*----list, news----*/
			$data['pic18'] = $this->news_model->getPicUrl(18);
			$data['pic19'] = $this->news_model->getPicUrl(19);
			$data['pic20'] = $this->news_model->getPicUrl(20);
			$data['pic21'] = $this->news_model->getPicUrl(21);
			$data['pic22'] = $this->news_model->getPicUrl(22);

			$this->load->view('admin/news/pic_view_l', $data);
		}
		public function picEditView() {
			

			$picId = intval($this->uri->segment(4));
			$data['pic'] = $this->news_model->getPic($picId);

			$this->load->view('admin/news/pic_edit', $data);
		}

		public function picEdit() {
			

/*----图片上传-----*/
			$savePath = 'KFTVresource/News/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $savePath;
	  		$config['allowed_types'] = 'jpg|jpeg|gif|png';
	  		$config['max_size'] = '2048';
	  		$config['max_width']  = 0;
	  		$config['max_height']  = 0;
	  		$config['file_name'] = date("Ymdhis");
	  
	  		$this->load->library('upload', $config);
	 
/*	 	*/	$up = $this->upload->do_upload('userfile');
/*  		if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
	  		 	exit(var_dump($error));
	  		} else {*/
	 	  		$data = array('upload_data' => $this->upload->data());
/*			}
/*----图片上传-----*/
			$file_name = $data['upload_data']['file_name'];
			$picUrl = $up?$savePath . $file_name:"";

			$picId = intval($this->uri->segment(4));
			$picLink = "http://" . $this->input->post('link');

			$oldPicUrl = $this->news_model->getPicUrl($picId);

			if ($up) {
				$path = BASEPATH . "../" . $oldPicUrl->pic_url;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}
			$this->news_model->picUpdate($picId, $picUrl, $picLink);
			redirect('news/news/picManage');
		}

		
	}

