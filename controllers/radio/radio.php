<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Radio extends Ci_Controller 
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
			$this->load->model('radio_model');
 		}

/*
 * Edit By : LYJ
 * Time : 2015.9.13
 * Function : radio
 * Review : LYJ . 2016.1.23
 */
		public function getRadioById() 
		{
			$id = intval($this->uri->segment(4));
			$data['radio'] = $this->radio_model->getRadioById($id);
			$data['time'] = $this->radio_model->getRadioTime($id);

			$this->load->view('admin/radio/radio_edit', $data);
		}
       	
		public function radioUpdate() 
		{
			$id = intval($this->uri->segment(4));

			$mms = $this->input->post('mms', true);
			$info = $this->input->post('info', true);

			$start1 = $this->input->post('start1');
			$start2 = $this->input->post('start2');
			$end1 = $this->input->post('end1');
			$end2 = $this->input->post('end2');
			$iid = $this->input->post('id');

			for ($i = 0; $i < count($start1); $i++) { 
				$start = intval($start1[$i]) . ":" . intval($start2[$i]);
				$end = intval($end1[$i]) . ":" . intval($end2[$i]);

				$this->radio_model->radioTimeUpdate($start, $end, $iid[$i]);
			}
			$this->radio_model->radioUpdate($mms, $info, $id);

			echo "修改成功";
		}

		public function radioAdd() 
		{
			$id = intval($this->uri->segment(4));
			$this->radio_model->radioAdd($id);

			redirect("radio/radio/getRadioById/" . $id);
		}

		public function radioDel() 
		{
			$id = intval($this->uri->segment(4));
			$bid = intval($this->uri->segment(5));
			$this->radio_model->radioDel($id);

			redirect("radio/radio/getRadioById/" . $bid);
		}

	}