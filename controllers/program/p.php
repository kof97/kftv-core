<?php 
	error_reporting(E_ALL ^ E_NOTICE);
	define('ACC',true);
	
	class P extends Ci_Controller {
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
*	Time : 2015.9.7
*	function : 前台控制器
*/
		public function sp(){
			$this->load->model('news_model');
			$this->load->model('program_model');

			/*----head----*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$data['lanmu'] = $this->program_model->getAllPr();

			
			

			$this->load->view('news/program_list', $data);
		}

		
		public function pc() {
			$this->load->model('news_model');
			$this->load->model('program_model');

			/*----head----*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$pid = $this->uri->segment(4);

			$data['program'] = $this->program_model->getProgramMessage($pid);

/*-----page-----*/
			$page_size = 10;

			$this->db->from('program_video');
			$this->db->where('program_id', $pid);
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('program/p/pc/' . $pid);
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 5;

			$this->pagination->initialize($config);
			$offset = intval($this->uri->segment(5));
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/

			$data['video_list'] = $this->program_model->getProgramVideo($offset, $page_size, $pid, "");

			$this->load->view('news/program_detail', $data);
		}

		public function pv() {
			$this->load->model('news_model');
			$this->load->model('program_model');

			/*----head----*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$pid = $this->uri->segment(4);
			$vid = $this->uri->segment(5);

			$data['program'] = $this->program_model->getProgramMessage($pid);
			$data['video'] = $this->program_model->getProgramVideoDetail($vid);

			$this->load->view('news/program_video', $data);
		}
		
	}