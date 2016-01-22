<?php 
	error_reporting(E_ALL ^ E_NOTICE);
	define('ACC',true);
	
	class F extends Ci_Controller {
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
*	Time : 2015.10.16
*	function : 前台控制器
*/
		public function feature() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);
			$data['category'] = $this->feature_model->getCats($data['fid'], 0, 6);
			
			$data['banner'] = $this->feature_model->getBanners($data['fid']);
			$data['art3'] = $this->feature_model->getArt3s($data['fid']);

			$data['cat0'] = $this->feature_model->getArts(0, 5, $data['fid'], $data['category'][0]->cid);
			$data['cat1'] = $this->feature_model->getArts(0, 5, $data['fid'], $data['category'][1]->cid);
			$data['cat2'] = $this->feature_model->getArts(0, 5, $data['fid'], $data['category'][2]->cid);

			$this->load->view('feature/index', $data);
		}

		public function fl() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);
			$data['category'] = $this->feature_model->getCats($data['fid'], 0, 6);
/*-----page-----*/
			$page_size = 20;

			$this->db->from('feature_article');
			$this->db->where('cid', $data['cid']);
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('feature/f/fl/' . $data['fid'] . '/' . $data['cid']);
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 6;

			$this->pagination->initialize($config);
			$offset = intval($this->uri->segment(6));
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/
			
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$data['list'] = $this->feature_model->getArts($offset, $page_size, $data['fid'], $data['cid']);
			$this->load->view('feature/list', $data);
		}

		public function fa() {
			$this->load->model('feature_model');

			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['id'] = intval($this->uri->segment(6));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);
			$data['category'] = $this->feature_model->getCats($data['fid'], 0, 6);

			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$data['content'] = $this->feature_model->getArt($data['fid'], $data['cid'], $data['id']);
			$this->load->view('feature/content', $data);
		}


		
	}