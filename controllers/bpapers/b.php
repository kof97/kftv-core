<?php 
	error_reporting(E_ALL ^ E_NOTICE);
	define('ACC',true);
	
	class B extends Ci_Controller {
		public static $uName;

		public function __construct() {
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
 		}

/*
*	Edit By : 阿诺
*	Time : 2015.8.14
*	function : 前台控制器
*/
		public function show(){
			$this->load->model('bpapers_model');
/*-----page-----*/
			$page_size = 3;

			$this->db->from('bnewspaper');
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('bpapers/b/show/');
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 4;

			$this->pagination->initialize($config);
			$offset = intval($this->uri->segment(4));
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/


			$data['bpList'] = $this->bpapers_model->getBpList($offset, $page_size);

			for ($i=0; $i < count($data['bpList']); $i++) { 
				$paperid = $data['bpList'][$i]->id;
				$data["bpArt$i"] = $this->bpapers_model->getBpArt(0, 3, $paperid);
			}
			
			$data['allBp'] = $this->bpapers_model->getBp();

			$data['pic1'] = $this->bpapers_model->getPic(1);
			$data['pic2'] = $this->bpapers_model->getPic(2);
			$data['pic3'] = $this->bpapers_model->getPic(3);

			$this->load->view('bpapers/index', $data);
		}

		public function showL(){
			$this->load->model('bpapers_model');

			$paperid = $this->uri->segment(4);
			$data['bp'] = $this->bpapers_model->getBpById($paperid);

			$paperid = $data['bp']->id;
			$data['allBpArt'] = $this->bpapers_model->getBpArtById($paperid);
			$data['allBp'] = $this->bpapers_model->getBp();

			$bpArtId = intval($this->uri->segment(5));
			$data['bpArt'] = $this->bpapers_model->getBpArtDetail($bpArtId);

			$this->load->view('bpapers/detail', $data);
		}


	}