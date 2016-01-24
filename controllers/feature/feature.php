<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Feature extends Ci_Controller 
	{

		public function __construct() 
		{
			parent::__construct();

			session_start();
			$ok = $_SESSION['admin'];
	 		if ( !isset($ok) || $ok != 'kftv' ) {
	 			redirect("admin/login/denglu");
	 		}
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
			$this->load->model('feature_model');
 		}
/*
 * Edit By : LYJ
 * Time : 2015.10.14
 * Function : sub site
 * Review : LYJ . 2016.1.23
 */	
		public function featureAddView() 
		{
			$this->load->view('admin/feature/feature_add');
		}

		public function featureAdd() 
		{			
			$name = $this->input->post('name', true);
			$title = $this->input->post('title', true);
			$info = $this->input->post('info', true);
			
			$this->feature_model->featureAdd($name, $title, $info);
			echo "添加成功";
		}

		public function featureManage() 
		{
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('feature');
			$total = $this->db->count_all_results();
			$pageUri = 'feature/feature/featureManage';
			
			$data['page'] = $this->feature_model->page($pageSize, $offset, $total, $pageUri);
			$data['total'] = $total;

			$data['feature'] = $this->feature_model->getAllFeature($offset, $pageSize);
			$this->load->view('admin/feature/feature_manage', $data);
		}

		public function featureEdit() 
		{
			$fid = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($fid);

			$this->load->view('admin/feature/feature_edit', $data);
		}

		public function featureUpdate() 
		{
			$fid = intval($this->input->post('fid'));
			$name = $this->input->post('name', true);
			$title = $this->input->post('title', true);
			$info = $this->input->post('info', true);

			$this->feature_model->featureUpdate($name, $title, $info, $fid);
			redirect('feature/feature/featureManage');
		}

/*
 * Edit By : LYJ
 * Time : 2015.10.14
 * Function : infomation about the sub site
 * Review : LYJ . 2016.1.23
 */	
		public function featureView() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);
			$data['category'] = $this->feature_model->getCats($data['fid'], 0, 6);
			$data['banner'] = $this->feature_model->getBanners($data['fid']);
			$data['art3'] = $this->feature_model->getArt3s($data['fid']);

			$this->load->view('admin/feature/feature_view', $data);
		}

		/*-- logo --*/
		public function logoView() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);

			$this->load->view('admin/feature/logo_view', $data);
		}

		public function logoAdd() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));
			$operate = intval($this->input->post('operate'));

			/*-- picture --*/
			$savePath = 'KFTVresource/Feature/pic/';
	 	  	$data = $this->feature_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$pic = $data?$savePath . $file_name:"";

			if ($operate == 0) {
				$name = "logo" . $fid;
				$this->feature_model->logoAdd($pic, $name, $fid);
			} else {
				$old = $this->feature_model->getLogo($fid, "logo" . $fid);
				if ($data) {
					$path = BASEPATH . "../" . $old->pic;
					$result = @unlink ($path); 
				}
				$this->feature_model->logoUpdate($fid, $pic, "logo" . $fid);
			}
			redirect('feature/feature/featureView/' . $fid);
		}

		/*-- category --*/
		public function catAddV() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$this->load->view('admin/feature/cat_add', $data);
		}

		public function catAdd() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));

			$name = $this->input->post('name', true);
			$info = $this->input->post('info', true);
			$this->feature_model->catAdd($name, $info, $fid);
			
			redirect('feature/feature/featureView/' . $fid);
		}

		public function catEdit() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);

			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$this->load->view('admin/feature/cat_edit', $data);
		}

		public function catUpdate() 
		{
			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
			$name = $this->input->post('name', true);
			$info = $this->input->post('info', true);

			$this->feature_model->catUpdate($name, $info, $cid);
			redirect('feature/feature/featureView/' . $fid);
		}

		/*-- banner --*/
		public function bannerAddV() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);

			$this->load->view('admin/feature/banner_add', $data);
		}

		public function bannerAdd() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));
			/*-- picture --*/
			$savePath = 'KFTVresource/Feature/pic/';
			$data = $this->feature_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$pic = $data?$savePath . $file_name:"";

			$title = $this->input->post('title', true);
			$url = $this->input->post('url', true);
			$this->feature_model->bannerAdd($title, $url, $pic, $fid);		

			redirect('feature/feature/featureView/' . $fid);
		}

		public function bannerEdit() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['banner'] = $this->feature_model->getBanner($data['fid'], $data['id']);

			$this->load->view('admin/feature/banner_edit', $data);
		}

		public function bannerUpdate() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(5));
			$fid = intval($this->uri->segment(4));
			/*-- picture --*/
			$savePath = 'KFTVresource/Feature/pic/';
			$data = $this->feature_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$pic = $data?$savePath . $file_name:"";
			$title = $this->input->post('title', true);
			$url = $this->input->post('url', true);

			$old = $this->feature_model->getBanner($fid, $id);
			if ($data) {
				$path = BASEPATH . "../" . $old->pic;
				$result = @unlink ($path); 
			}
			$this->feature_model->bannerUpdate($title, $pic, $url, $id);
			
			redirect('feature/feature/featureView/' . $fid);
		}

		/*-- art3 --*/
		public function art3AddV() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);

			$this->load->view('admin/feature/art3_add', $data);
		}

		public function art3Add() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$fid = intval($this->uri->segment(4));
			/*-- picture --*/
			$savePath = 'KFTVresource/Feature/pic/';
			$data = $this->feature_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$pic = $data?$savePath . $file_name:"";

			$title = $this->input->post('title', true);
			$url = $this->input->post('url', true);
			$content = $this->input->post('content', true);
			$this->feature_model->art3Add($title, $content, $url, $pic, $fid);	

			redirect('feature/feature/featureView/' . $fid);
		}

		public function art3Edit() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['art3'] = $this->feature_model->getArt3($data['fid'], $data['id']);

			$this->load->view('admin/feature/art3_edit', $data);
		}

		public function art3Update() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['id'] = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(5));
			$fid = intval($this->uri->segment(4));
			/*-- picture --*/
			$savePath = 'KFTVresource/Feature/pic/';
			$data = $this->feature_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$pic = $data?$savePath . $file_name:"";

			$title = $this->input->post('title', true);
			$url = $this->input->post('url', true);
			$content = $this->input->post('content', true);

			$old = $this->feature_model->getArt3($fid, $id);
			if ($data) {
				$path = BASEPATH . "../" . $old->pic;
				$result = @unlink ($path); 
			}
			$this->feature_model->art3Update($title, $content, $pic, $url, $id);
			
			redirect('feature/feature/featureView/' . $fid);
		}

 		/*-- article --*/
 		public function artManageV() 
 		{
 			$data['fid'] = intval($this->uri->segment(4));
 			$fid = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$cid = intval($this->uri->segment(5));
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(6));
			$this->db->from('feature_article');
			$this->db->where('cid', $cid);
			$total = $this->db->count_all_results();
			$pageUri = 'feature/feature/artManageV/' . $fid . '/' . $cid;
			
			$data['page'] = $this->feature_model->page($pageSize, $offset, $total, $pageUri, 6);
			$data['total'] = $total;

			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$data['art'] = $this->feature_model->getArts($offset, $pageSize, $fid, $cid);
			$this->load->view('admin/feature/art_manage', $data);
 		}

 		public function artAddV() 
 		{
 			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);

			$this->load->view('admin/feature/art_add', $data);
 		}

 		public function artAdd() 
 		{
 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
			/*-- video --*/
			$savePath = 'KFTVresource/Feature/video/';
			$data = $this->feature_model->video($savePath);

			$file_name = $data['upload_data']['file_name'];
			$hasVideo = $data?1:0;
			$video = $data?$savePath . $file_name:"";

			$title = $this->input->post('title', true);
			$content = $this->input->post('content', true);
			$time = $this->input->post('pubTime') . " " . date("h:i:s");
			$this->feature_model->artAdd($title, $content, $time, $video, $fid, $cid);

			//echo "<script>alert('添加成功');</script>";
			redirect('feature/feature/artManageV/' . $fid . '/' . $cid);
 		}

 		public function videoView() 
 		{
 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(6));

			$data['art'] = $this->feature_model->getArt($fid, $cid, $id);
			$this->load->view('admin/feature/video_view', $data);
 		}

 		public function artEdit() 
 		{
 			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['id'] = intval($this->uri->segment(6));

			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$data['art'] = $this->feature_model->getArt($data['fid'], $data['cid'], $data['id']);
			$this->load->view('admin/feature/art_edit', $data);
 		}

 		public function artUpdate() 
 		{
 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));
			$id = intval($this->uri->segment(6));
			/*-- video --*/
			$savePath = 'KFTVresource/Feature/video/';
			$data = $this->feature_model->video($savePath);

			$file_name = $data['upload_data']['file_name'];
			$hasVideo = $data?1:0;
			$video = $data?$savePath . $file_name:"";

			$title = $this->input->post('title', true);
			$content = $this->input->post('content', true);
			$time = $this->input->post('pubTime') . " " . date("h:i:s");

			$old = $this->feature_model->getArt($fid, $cid, $id);
			if ($data) {
				$path = BASEPATH . "../" . $old->video;
				$result = @unlink ($path); 
			}
			$this->feature_model->artUpdate($title, $content, $time, $video, $id);
			
			redirect('feature/feature/artManageV/' . $fid . '/' . $cid);
 		}

 		public function artDel() 
 		{
 			$fid = intval($this->uri->segment(4));
			$cid = intval($this->uri->segment(5));

			$check = $this->input->post('check');
			foreach ($check as $id) {
				$id = intval($id);
				$old = $this->feature_model->getArt($fid, $cid, $id);
				$path = BASEPATH . "../" . $old->video;
				$result = @unlink ($path);
				
				$this->feature_model->artDel($fid, $cid, $id);
			}
			redirect('feature/feature/artManageV/' . $fid . '/' . $cid);
 		}

		
	}

