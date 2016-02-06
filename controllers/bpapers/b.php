<?php 
	if ( ! defined('BASEPATH') ) exit('No direct script access allowed');
	error_reporting(E_ALL ^ E_NOTICE);
	
	class B extends Ci_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
			$this->load->model('bpapers_model');
 		}

/**
 * PHP version 5
 * Reviewed by LYJ . 2016.1.22
 *
 * @category PHP
 * @author 	 LYJ <1048434786@qq.com>
 * @version  2015.8.14
 * @link 	 https://git.oschina.net/kofyu/KFTV-complete
 */
		public function show()
		{
			$pageSize = 3;

			$this->db->from('bnewspaper');
			$total = $this->db->count_all_results();
			$pageUri = 'bpapers/b/show/';
			$offset = intval($this->uri->segment(4));

			$data['page'] = $this->bpapers_model->page($pageSize, $offset, $total, $pageUri) ;
			$data['bpList'] = $this->bpapers_model->getBpList($offset, $pageSize);

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

		public function showL()
		{
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