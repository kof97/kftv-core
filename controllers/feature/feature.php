<?php 

	define('ACC',true);
	
	class Feature extends Ci_Controller {
		public static $uName;

		public function __construct() {
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
			session_start();
			self::$uName = $_SESSION['username'];
			self::$uName = addslashes(self::$uName);

			$ok = $_SESSION['admin'];
	 		if(!isset($ok) || $ok != 'kftv'){
	 			redirect("admin/login/denglu");
	 		}
 		}
/*
 *	Edit By : 阿诺
 *	Time : 2015.10.14
 *	function : 后台控制器-子站点
 */	
		public function f() {
			$this->load->view('admin/feature/feature_add');
		}

		public function featureAdd() {
			$this->load->model('feature_model');
			
			$name = $this->input->post('name');
			$title = $this->input->post('title');
			$info = $this->input->post('info');
			
			$this->feature_model->featureAdd($name, $title, $info);
			echo "添加成功";
		}

		public function featureManage() {
			$this->load->model('feature_model');

/*-----page-----*/
			$page_size = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('feature');
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('feature/feature/featureManage');
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 4;

			$this->pagination->initialize($config);
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/
			$data['total'] = $total;

			$data['feature'] = $this->feature_model->getAllFeature($offset, $page_size);
			$this->load->view('admin/feature/feature_manage', $data);
		}

		public function featureEdit() {
			$this->load->model('feature_model');

			$fid = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($fid);

			$this->load->view('admin/feature/feature_edit', $data);
		}

		public function featureUpdate() {
			$this->load->model('feature_model');

			$fid = $this->input->post('fid');
			$name = $this->input->post('name');
			$title = $this->input->post('title');
			$info = $this->input->post('info');

			$this->feature_model->featureUpdate($name, $title, $info, $fid);
			redirect('feature/feature/featureManage');
		}

/*
 *	Edit By : 阿诺
 *	Time : 2015.10.14
 *	function : 后台控制器-子站点信息
 */	
		public function featureView() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);
			$data['category'] = $this->feature_model->getCats($data['fid'], 0, 6);
			$data['banner'] = $this->feature_model->getBanners($data['fid']);
			$data['art3'] = $this->feature_model->getArt3s($data['fid']);

			$this->load->view('admin/feature/feature_view', $data);
		}

	/*
	 *	Edit By : 阿诺
	 *	Time : 2015.10.14
	 *	function : 后台控制器-logo
	 */	
		public function logoView() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);

			$this->load->view('admin/feature/logo_view', $data);
		}

		public function logoAdd() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));
			$operate = $this->input->post('operate');

/*----图片上传-----*/
			$save_path = 'KFTVresource/Feature/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
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
			$pic = $up?$save_path . $file_name:"";

			if ($operate == 0) {
				$name = "logo" . $fid;
				$this->feature_model->logoAdd($pic, $name, $fid);
			} else {
				$old = $this->feature_model->getLogo($fid, "logo" . $fid);

				if ($up) {
					$path = BASEPATH . "../" . $old->pic;
					$result = @unlink ($path); 
				/*	if($result == true){
						echo  "删除成功";
					}
					else{
						exit("删除失败");
					}*/
				}
				$this->feature_model->logoUpdate($fid, $pic, "logo" . $fid);
			}
			

			redirect('feature/feature/featureView/' . $fid);
		}

	/*
	 *	Edit By : 阿诺
	 *	Time : 2015.10.14
	 *	function : 后台控制器-category
	 */
		public function catAddV() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$this->load->view('admin/feature/cat_add', $data);
		}

		public function catAdd() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));

			$name = $this->input->post('name');
			$info = $this->input->post('info');

			$this->feature_model->catAdd($name, $info, $fid);
			
			redirect('feature/feature/featureView/' . $fid);
		}

		public function catEdit() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);

			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$this->load->view('admin/feature/cat_edit', $data);
		}

		public function catUpdate() {
			$this->load->model('feature_model');

			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
			$name = $this->input->post('name');
			$info = $this->input->post('info');

			$this->feature_model->catUpdate($name, $info, $cid);
			redirect('feature/feature/featureView/' . $fid);
		}

	/*
	 *	Edit By : 阿诺
	 *	Time : 2015.10.14
	 *	function : 后台控制器-banner
	 */
		public function bannerAddV() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);

			$this->load->view('admin/feature/banner_add', $data);
		}

		public function bannerAdd() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));
/*----图片上传-----*/
			$save_path = 'KFTVresource/Feature/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
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
			$pic = $up?$save_path . $file_name:"";

			$title = $this->input->post('title');
			$url = $this->input->post('url');

			$this->feature_model->bannerAdd($title, $url, $pic, $fid);		
			redirect('feature/feature/featureView/' . $fid);
		}

		public function bannerEdit() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['banner'] = $this->feature_model->getBanner($data['fid'], $data['id']);

			$this->load->view('admin/feature/banner_edit', $data);
		}

		public function bannerUpdate() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(5));
			$fid = intval($this->uri->segment(4));
/*----图片上传-----*/
			$save_path = 'KFTVresource/Feature/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
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
			$pic = $up?$save_path . $file_name:"";

			$title = $this->input->post('title');
			$url = $this->input->post('url');

			$old = $this->feature_model->getBanner($fid, $id);
			if ($up) {
				$path = BASEPATH . "../" . $old->pic;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}
			$this->feature_model->bannerUpdate($title, $pic, $url, $id);
			
			redirect('feature/feature/featureView/' . $fid);
		}

	/*
	 *	Edit By : 阿诺
	 *	Time : 2015.10.15
	 *	function : 后台控制器-art3
	 */
		public function art3AddV() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);

			$this->load->view('admin/feature/art3_add', $data);
		}

		public function art3Add() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));
/*----图片上传-----*/
			$save_path = 'KFTVresource/Feature/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
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
			$pic = $up?$save_path . $file_name:"";

			$title = $this->input->post('title');
			$url = $this->input->post('url');
			$content = $this->input->post('content');

			$this->feature_model->art3Add($title, $content, $url, $pic, $fid);		
			redirect('feature/feature/featureView/' . $fid);
		}

		public function art3Edit() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['art3'] = $this->feature_model->getArt3($data['fid'], $data['id']);

			$this->load->view('admin/feature/art3_edit', $data);
		}

		public function art3Update() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(5));
			$fid = intval($this->uri->segment(4));
/*----图片上传-----*/
			$save_path = 'KFTVresource/Feature/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
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
			$pic = $up?$save_path . $file_name:"";

			$title = $this->input->post('title');
			$url = $this->input->post('url');
			$content = $this->input->post('content');

			$old = $this->feature_model->getArt3($fid, $id);
			if ($up) {
				$path = BASEPATH . "../" . $old->pic;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}
			$this->feature_model->art3Update($title, $content, $pic, $url, $id);
			
			redirect('feature/feature/featureView/' . $fid);
		}

 	/*
	 *	Edit By : 阿诺
	 *	Time : 2015.10.16
	 *	function : 后台控制器-article
	 */
 		public function artManageV() {
 			$this->load->model('feature_model');

 			$data['fid'] = intval($this->uri->segment(4));
 			$fid = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$cid = intval($this->uri->segment(5));
/*-----page-----*/
			$page_size = 20;
			$offset = intval($this->uri->segment(6));

			$this->db->from('feature_article');
			$this->db->where('cid', $cid);
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('feature/feature/artManageV/' . $fid . '/' . $cid);
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 6;

			$this->pagination->initialize($config);
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/
			$data['total'] = $total;

			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$data['art'] = $this->feature_model->getArts($offset, $page_size, $fid, $cid);
			$this->load->view('admin/feature/art_manage', $data);
 		}

 		public function artAddV() {
 			$this->load->model('feature_model');

 			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);

			$this->load->view('admin/feature/art_add', $data);
 		}

 		public function artAdd() {
 			$this->load->model('feature_model');

 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
/*----视频上传-----*/
			$save_path = 'KFTVresource/Feature/video/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
	  		$config['allowed_types'] = 'wmv|mp4|mpeg|avi|mov|3gp|flv|f4v|rmvb';
	  		$config['max_size'] = '102400';
	  		$config['max_width']  = 0;
	  		$config['max_height']  = 0;
	  		$config['file_name'] = date("Ymdhis");
	  
	  		$this->load->library('upload', $config);
	 
/*	 	*/	$up = $this->upload->do_upload('userfile');
/*调试开启  if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
	  		 	exit(var_dump($error));
	  		} else {*/
	 	  		$data = array('upload_data' => $this->upload->data());
	/*		}*/
/*----视频上传-----*/
			$file_name = $data['upload_data']['file_name'];
			$hasVideo = $up?1:0;
			$video = $up?$save_path . $file_name:"";

			$title = addslashes($this->input->post('title'));
			$content = $this->input->post('content');
			$time = $this->input->post('pubTime') . " " . date("h:i:s");

			$this->feature_model->artAdd($title, $content, $time, $video, $fid, $cid);
			
			//echo "<script>alert('添加成功');</script>";
			redirect('feature/feature/artManageV/' . $fid . '/' . $cid);
 		}

 		public function videoView() {
 			$this->load->model('feature_model');

 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(6));

			$data['art'] = $this->feature_model->getArt($fid, $cid, $id);
			$this->load->view('admin/feature/video_view', $data);
 		}

 		public function artEdit() {
 			$this->load->model('feature_model');

 			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['id'] = intval($this->uri->segment(6));

			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$data['art'] = $this->feature_model->getArt($data['fid'], $data['cid'], $data['id']);
			$this->load->view('admin/feature/art_edit', $data);
 		}

 		public function artUpdate() {
 			$this->load->model('feature_model');

 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(6));
/*----视频上传-----*/
			$save_path = 'KFTVresource/Feature/video/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
	  		$config['allowed_types'] = 'wmv|mp4|mpeg|avi|mov|3gp|flv|f4v|rmvb';
	  		$config['max_size'] = '102400';
	  		$config['max_width']  = 0;
	  		$config['max_height']  = 0;
	  		$config['file_name'] = date("Ymdhis");
	  
	  		$this->load->library('upload', $config);
	 
/*	 	*/	$up = $this->upload->do_upload('userfile');
/*调试开启  if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
	  		 	exit(var_dump($error));
	  		} else {*/
	 	  		$data = array('upload_data' => $this->upload->data());
	/*		}*/
/*----视频上传-----*/
			$file_name = $data['upload_data']['file_name'];
			$hasVideo = $up?1:0;
			$video = $up?$save_path . $file_name:"";

			$title = addslashes($this->input->post('title'));
			$content = $this->input->post('content');
			$time = $this->input->post('pubTime') . " " . date("h:i:s");

			$old = $this->feature_model->getArt($fid, $cid, $id);
			if ($up) {
				$path = BASEPATH . "../" . $old->video;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}
			$this->feature_model->artUpdate($title, $content, $time, $video, $id);
			
			redirect('feature/feature/artManageV/' . $fid . '/' . $cid);
 		}

 		public function artDel() {
 			$this->load->model('feature_model');

 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));

			$check = $this->input->post('check');
			foreach ($check as $id) {
				$old = $this->feature_model->getArt($fid, $cid, $id);
				$path = BASEPATH . "../" . $old->video;
				$result = @unlink ($path);
				
				$this->feature_model->artDel($fid, $cid, $id);
			}

			redirect('feature/feature/artManageV/' . $fid . '/' . $cid);
 		}

		
	}

