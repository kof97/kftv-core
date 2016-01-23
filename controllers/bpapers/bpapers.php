<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Bpapers extends Ci_Controller 
	{
		public static $uName;

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
			$this->load->model('bpapers_model');
			
			self::$uName = $_SESSION['username'];
			self::$uName = self::$uName;
 		}
/*
 * Edit By : LYJ
 * Time : 2015.8.13
 * Function : newspapers
 * Review : LYJ . 2016.1.22
 */
		public function bpapersManage() 
		{
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('bnewspaper');
			$total = $this->db->count_all_results();
			$pageUri = 'bpapers/bpapers/bpapersManage';
			
			$data['page'] = $this->bpapers_model->page($pageSize, $offset, $total, $pageUri) ;
			$data['total'] = $total;

			$data['bpList'] = $this->bpapers_model->getAllbp($offset, $pageSize);
			$this->load->view('admin/bpapers/bpapers_list', $data);
		}

		public function bpAddView() 
		{
			$this->load->view('admin/bpapers/add_bp');
		}

		public function bpAdd() 
		{
			/*-- picture --*/
			$savePath = 'KFTVresource/Bpaper/';
			$data = $this->bpapers_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$picture = $data?$savePath . $file_name:"";

			$nowIssue = $this->input->post('nowIssue', true);
			$totalIssue = $this->input->post('totalIssue', true);
			$pubTime = $this->input->post('pubTime');
			$pubTime = $pubTime . " " . date("h:i:s");
			$time = date("Y-m-d h:i:s");
			
			$this->bpapers_model->bpAdd($nowIssue, $totalIssue, $picture, $time, $pubTime);
			redirect('bpapers/bpapers/bpapersManage');
		}

		public function bpEdit() 
		{
			$bid = intval($this->uri->segment(4));
			$data['bpDetail'] = $this->bpapers_model->getBpById($bid);
			$this->load->view('admin/bpapers/bp_edit', $data);
		}

		public function bpUpdate() 
		{
			/*-- picture --*/
			$savePath = 'KFTVresource/Bpaper/';
			$data = $this->bpapers_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$picture = $data?$savePath . $file_name:"";

			$nowIssue = $this->input->post('nowIssue', true);
			$totalIssue = $this->input->post('totalIssue', true);
			$pubTime = $this->input->post('pubTime');
			$pubTime = $pubTime . " " . date("h:i:s");
			$time = date("Y-m-d h:i:s");
			$bid = intval($this->uri->segment(4));

			$oldPic = $this->bpapers_model->getPicUrl($bid);
			if ($data) {
				$path = BASEPATH . "../" . $oldPic->picture;
				$result = @unlink ($path); 
			}
			$this->bpapers_model->bpUpdate($nowIssue, $totalIssue, $picture, $time, $pubTime, $bid);
			redirect('bpapers/bpapers/bpapersManage');
		}

		public function bpDel() 
		{
			$check = $this->input->post('check');
			foreach ($check as $bid) {
				$bid = intval($bid);
				$oldPic = $this->bpapers_model->getPicUrl($bid);
				$path = BASEPATH . "../" . $oldPic->picture;
				$result = @unlink ($path); 

				$this->bpapers_model->bpDel($bid);
			}
			redirect('bpapers/bpapers/bpapersManage');
		}

		/*-- order --*/
		public function orderBp() 
		{
			$order = intval($this->uri->segment(4));
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(5));

			$this->db->from('bnewspaper');
			$total = $this->db->count_all_results();
			$pageUri = 'bpapers/bpapers/orderNews/' . $order;

			$data['page'] = $this->bpapers_model->page($pageSize, $offset, $total, $pageUri, 5);
			$data['total'] = $total;

			$data['bpList'] = $this->bpapers_model->getOrderbp($offset, $pageSize, $order);
			$this->load->view('admin/bpapers/bpapers_list', $data);
		}

/*
 * Edit By : LYJ
 * Time : 2015.8.13
 * Function : articles
 * Review : LYJ . 2016.1.22
 */
		public function bpArtAddView() 
		{
			$data['paperid'] = intval($this->uri->segment(4));
			$data['pubUser'] = self::$uName;

			$data['bpDetail'] = $this->bpapers_model->getBpById($data['paperid']);
			$this->load->view('admin/bpapers/bpart_add', $data);
		}

		public function bpArtAdd() 
		{
			$paperid = intval($this->uri->segment(4));
			$title = $this->input->post('title', true);
			$content = $this->input->post('artContent', true);
			$pubUser = $this->input->post('pubUser');
			$checked = $this->input->post('checked')?1:0;
			$time = $this->input->post('pubTime');
			$source = $this->input->post('source', true);

			$this->bpapers_model->bpArtAdd($paperid, $title, $content, $pubUser, $checked, $time, $source);
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}

		public function bpArtList() 
		{
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(5));
			$paperid = intval($this->uri->segment(4));

			$this->db->from('bnpcontent');
			if ($paperid != 0) {
				$this->db->where('paperid', $paperid);
			}
			$total = $this->db->count_all_results();
			$pageUri = 'bpapers/bpapers/bpArtList/' . $paperid;

			$data['page'] = $this->bpapers_model->page($pageSize, $offset, $total, $pageUri, 5);
			$data['total'] = $total;

			$data['bpDetail'] = $this->bpapers_model->getBpById($paperid);
			$data['bpArtList'] = $this->bpapers_model->getBpArt($offset, $pageSize, $paperid);
			$this->load->view('admin/bpapers/bpart_list', $data);
		}

		public function ischecked() 
		{
			$paperid = intval($this->uri->segment(4));
			$bpArtId = intval($this->uri->segment(5));
			$ischecked = intval($this->uri->segment(6));
			if ($ischecked != 1 && $ischecked != 0) {
				exit("error in variable ischecked");
			}
			$this->bpapers_model->ischecked($bpArtId, $ischecked);
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}

		public function bpArtDel() 
		{
			$paperid = intval($this->uri->segment(4));
			$check = $this->input->post('check');
			foreach ($check as $bpArtId) {
				$bpArtId = intval($bpArtId);
				$this->bpapers_model->bpArtDel($bpArtId);
			}
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}

		public function bpArtEdit() 
		{
			$paperid = intval($this->uri->segment(4));
			$bpArtId = intval($this->uri->segment(5));

			$data['pubUser'] = self::$uName;
			$data['bpDetail'] = $this->bpapers_model->getBpById($paperid);
			$data['bpArtDetail'] = $this->bpapers_model->getBpArtDetail($bpArtId);

			$this->load->view('admin/bpapers/bpart_edit', $data);
		}

		public function bpArtUpdate() 
		{
			$paperid = intval($this->uri->segment(4));
			$bpArtId = intval($this->uri->segment(5));
			$title = $this->input->post('title', true);
			$content = $this->input->post('artContent', true);
			$pubUser = $this->input->post('pubUser');
			$checked = $this->input->post('checked')?1:0;
			$time = $this->input->post('pubTime');
			$source = $this->input->post('source', true);

			$this->bpapers_model->bpArtUpdate($bpArtId, $title, $content, $pubUser, $checked, $time, $source);
			redirect('bpapers/bpapers/bpArtList/' . $paperid);
		}
/*
 * Edit By : LYJ
 * Time : 2015.9.7
 * Function : picture
 * Review : LYJ . 2016.1.22
 */	
		public function picManage() 
		{
			$data['pic1'] = $this->bpapers_model->getPic(1);
			$data['pic2'] = $this->bpapers_model->getPic(2);
			$data['pic3'] = $this->bpapers_model->getPic(3);

			$this->load->view('admin/bpapers/pic_view', $data);
		}

		public function picEditView() 
		{
			$picId = intval($this->uri->segment(4));
			$data['pic'] = $this->bpapers_model->getPic($picId);

			$this->load->view('admin/bpapers/pic_edit', $data);
		}

		public function picEdit() 
		{
			/*-- picture --*/
			$savePath = 'KFTVresource/Bpaper/pic/';
			$data = $this->bpapers_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$pic = $data?$savePath . $file_name:"";

			$picId = intval($this->uri->segment(4));
			$url = "http://" . $this->input->post('link', true);

			$oldPicUrl = $this->bpapers_model->getP($picId);
			if ($data) {
				$path = BASEPATH . "../" . $oldPicUrl->pic;
				$result = @unlink ($path); 
			}
			$this->bpapers_model->picUpdate($picId, $pic, $url);
			redirect('bpapers/bpapers/picManage');
		}

	}