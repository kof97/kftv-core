<?php 
	if ( ! defined('BASEPATH') ) exit('No direct script access allowed');
	error_reporting(E_ALL ^ E_NOTICE);
	
	class P extends Ci_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
			$this->load->model('news_model');
			$this->load->model('program_model');
 		}

/**
 * PHP version 5
 * Reviewed by LYJ . 2016.1.22
 *
 * @category PHP
 * @author 	 LYJ <1048434786@qq.com>
 * @version  2015.9.7
 * @link 	 https://git.oschina.net/kofyu/KFTV-complete
 */
		public function sp()
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$data['lanmu'] = $this->program_model->getAllPr();

			$this->load->view('news/program_list', $data);
		}
		
		public function pc() 
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$pid = intval($this->uri->segment(4));

			$data['program'] = $this->program_model->getProgramMessage($pid);

			/*-- page --*/
			$pageSize = 10;

			$this->db->from('program_video');
			$this->db->where('program_id', $pid);
			$total = $this->db->count_all_results();
			$pageUri = 'program/p/pc/' . $pid;
			$offset = intval($this->uri->segment(5));

			$data['page'] = $this->program_model->page($pageSize, $offset, $total, $pageUri, 5);
			$data['video_list'] = $this->program_model->getProgramVideos($offset, $pageSize, $pid);

			$this->load->view('news/program_detail', $data);
		}

		public function pv() 
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$pid = intval($this->uri->segment(4));
			$vid = intval($this->uri->segment(5));

			$data['program'] = $this->program_model->getProgramMessage($pid);
			$data['video'] = $this->program_model->getProgramVideoDetail($vid);

			$this->load->view('news/program_video', $data);
		}
		
	}