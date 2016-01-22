<?php 
	error_reporting(E_ALL ^ E_NOTICE);
	define('ACC',true);
	
	class R extends Ci_Controller {
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
*	Time : 2015.9.13
*	function : 前台控制器
*/
		public function dt() {
			$this->load->model('news_model');
			$this->load->model('radio_model');
			$this->load->model('program_model');

			/*----head----*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$id = $this->uri->segment(4);
			$data['radio'] = $this->radio_model->getRadioById($id);
			$data['time'] = $this->radio_model->getRadioTime($id);

			$this->load->view('news/radio_station', $data);

		}


		
	}