<?php 

	define('ACC',true);
	
	class Bpapers extends Ci_Controller {
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
*	Time : 2015.8.13
*	function : 后台控制器-报刊管理
*/
		public function bpapersManage() {
			$this->load->model('bpapers_model');

/*-----page-----*/
			$page_size = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('bnewspaper');
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('bpapers/bpapers/bpapersManage');
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

			//$data['usingCat'] = $this->news_model->getUsingCat();
			$data['bpList'] = $this->bpapers_model->getAllbp($offset, $page_size);
			$this->load->view('admin/bpapers/bpapers_list', $data);
		}

		public function bpAddView() {
			$this->load->model('bpapers_model');
			$this->load->view('admin/bpapers/add_bp');
		}

		public function bpAdd() {
			$this->load->model('bpapers_model');
/*----图片上传-----*/
			$save_path = 'KFTVresource/Bpaper/';

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
			$picture = $up?$save_path . $file_name:"";

			$nowIssue = $this->input->post('nowIssue');
			$totalIssue = $this->input->post('totalIssue');
			$pubTime = $this->input->post('pubTime');
			$pubTime = $pubTime . " " . date("h:i:s");
			$time = date("Y-m-d h:i:s");
			
			$this->bpapers_model->bpAdd($nowIssue, $totalIssue, $picture, $time, $pubTime);
			redirect('bpapers/bpapers/bpapersManage');
		}

		public function bpEdit() {
			$this->load->model('bpapers_model');

			$bid = $this->uri->segment(4);
			$data['bpDetail'] = $this->bpapers_model->getBpById($bid);
			$this->load->view('admin/bpapers/bp_edit', $data);
		}

		public function bpUpdate() {
			$this->load->model('bpapers_model');
/*----图片上传-----*/
			$save_path = 'KFTVresource/Bpaper/';

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
			$picture = $up?$save_path . $file_name:"";

			$nowIssue = $this->input->post('nowIssue');
			$totalIssue = $this->input->post('totalIssue');
			$pubTime = $this->input->post('pubTime');
			$pubTime = $pubTime . " " . date("h:i:s");
			$time = date("Y-m-d h:i:s");
			$bid = $this->uri->segment(4);

			$oldPic = $this->bpapers_model->getPicUrl($bid);
			if ($up) {
				$path = BASEPATH . "../" . $oldPic->picture;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}

			$this->bpapers_model->bpUpdate($nowIssue, $totalIssue, $picture, $time, $pubTime, $bid);
			redirect('bpapers/bpapers/bpapersManage');
		}

		public function bpDel() {
			$this->load->model('bpapers_model');

			$check = $this->input->post('check');
			foreach ($check as $bid) {
				$oldPic = $this->bpapers_model->getPicUrl($bid);
				$path = BASEPATH . "../" . $oldPic->picture;
				$result = @unlink ($path); 

				$this->bpapers_model->bpDel($bid);
				$this->bpapers_model->bpArtDelById($bid);
			}
			redirect('bpapers/bpapers/bpapersManage');
		}

	/*--------------排序-----------------------*/
		public function orderBp() {
			$this->load->model('bpapers_model');
			$order = $this->uri->segment(4);
/*-----page-----*/
			$page_size = 20;
			$offset = intval($this->uri->segment(5));

			$this->db->from('bnewspaper');
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('bpapers/bpapers/orderNews/' . $order);
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 5;

			$this->pagination->initialize($config);
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/
			$data['total'] = $total;

			$data['bpList'] = $this->bpapers_model->getOrderbp($offset, $page_size, $order);
			$this->load->view('admin/bpapers/bpapers_list', $data);
		}

/*
*	Edit By : 阿诺
*	Time : 2015.8.13
*	function : 后台控制器-报刊文章管理
*/
		public function bpArtAddView() {
			$this->load->model('bpapers_model');
			$data['paperid'] = $this->uri->segment(4);
			$data['pubUser'] = self::$uName;

			$data['bpDetail'] = $this->bpapers_model->getBpById($data['paperid']);
			$this->load->view('admin/bpapers/bpart_add', $data);
		}

		public function bpArtAdd() {
			$this->load->model('bpapers_model');

			$paperid = $this->uri->segment(4);
			$title = $this->input->post('title');
			$content = $this->input->post('artContent');
			$pub_user = $this->input->post('pubUser');
			$checked = $this->input->post('checked')?1:0;
			$time = $this->input->post('pubTime');
			$source = $this->input->post('source');

			$this->bpapers_model->bpArtAdd($paperid, $title, $content, $pub_user, $checked, $time, $source);
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}

		public function bpArtList() {
			$this->load->model('bpapers_model');

/*-----page-----*/
			$page_size = 20;
			$offset = intval($this->uri->segment(5));

			$paperid = $this->uri->segment(4);

			$this->db->from('bnpcontent');
			if ($paperid != 0) {
				$this->db->where('paperid', $paperid);
			}
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('bpapers/bpapers/bpArtList/' . $paperid);
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 5;

			$this->pagination->initialize($config);
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/
			$data['total'] = $total;

			$data['bpDetail'] = $this->bpapers_model->getBpById($paperid);
			$data['bpArtList'] = $this->bpapers_model->getBpArt($offset, $page_size, $paperid);
			$this->load->view('admin/bpapers/bpart_list', $data);
		}

		public function ischecked() {
			$this->load->model('bpapers_model');

			$paperid = $this->uri->segment(4);
			$bpArtId = $this->uri->segment(5);
			$ischecked = $this->uri->segment(6);

			$this->bpapers_model->ischecked($bpArtId, $ischecked);
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}

		public function bpArtDel() {
			$this->load->model('bpapers_model');

			$paperid = $this->input->post('paperid');
			$check = $this->input->post('check');
			foreach ($check as $bpArtId) {
				$this->bpapers_model->bpArtDel($bpArtId);
			}
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}

		public function bpArtEdit() {
			$this->load->model('bpapers_model');

			$paperid = $this->uri->segment(4);
			$bpArtId = $this->uri->segment(5);

			$data['pubUser'] = self::$uName;
			$data['bpDetail'] = $this->bpapers_model->getBpById($paperid);
			$data['bpArtDetail'] = $this->bpapers_model->getBpArtDetail($bpArtId);

			$this->load->view('admin/bpapers/bpart_edit', $data);
		}

		public function bpArtUpdate() {
			$this->load->model('bpapers_model');

			$paperid = $this->uri->segment(4);
			$bpArtId = $this->uri->segment(5);
			$title = $this->input->post('title');
			$content = $this->input->post('artContent');
			$pub_user = $this->input->post('pubUser');
			$checked = $this->input->post('checked')?1:0;
			$time = $this->input->post('pubTime');
			$source = $this->input->post('source');

			$this->bpapers_model->bpArtUpdate($bpArtId, $title, $content, $pub_user, $checked, $time, $source);
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}
/*
*	Edit By : 阿诺
*	Time : 2015.9.7
*	function : 后台控制器-首页图片
*/	
		public function picManage() {
			$this->load->model('bpapers_model');

			$data['pic1'] = $this->bpapers_model->getPic(1);
			$data['pic2'] = $this->bpapers_model->getPic(2);
			$data['pic3'] = $this->bpapers_model->getPic(3);

			$this->load->view('admin/bpapers/pic_view', $data);
		}

		public function picEditView() {
			$this->load->model('bpapers_model');

			$picId = $this->uri->segment(4);
			$data['pic'] = $this->bpapers_model->getPic($picId);

			$this->load->view('admin/bpapers/pic_edit', $data);
		}

		public function picEdit() {
			$this->load->model('bpapers_model');

/*----图片上传-----*/
			$save_path = 'KFTVresource/Bpaper/pic/';

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

			$picId = $this->uri->segment(4);
			$url = "http://" . $this->input->post('link');

			$oldPicUrl = $this->bpapers_model->getP($picId);

			if ($up) {
				$path = BASEPATH . "../" . $oldPicUrl->pic;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}
			$this->bpapers_model->picUpdate($picId, $pic, $url);
			redirect('bpapers/bpapers/picManage');
		}

	}