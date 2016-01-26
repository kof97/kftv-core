<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	error_reporting(E_ALL ^ E_NOTICE);
	
	class R extends Ci_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
 		}

/**
 * Edit By: LYJ
 * Time: 2015.9.13
 * Function: front end
 * Review: LYJ . 2016.1.23
 */
		public function dt() 
		{
			$this->load->model('news_model');
			$this->load->model('radio_model');
			$this->load->model('program_model');

			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$id = intval($this->uri->segment(4));
			$data['radio'] = $this->radio_model->getRadioById($id);

			/*-- to determine whether it' s in the play period --*/
			$time = $this->radio_model->getRadioTime($id);
			$flag = 0;
			foreach ($time as $v) {
				list($start1, $start2) = explode(":", $v['start']);
				list($end1, $end2) = explode(":", $v['end']);
				$now = date("H:i:s");
				list($hour, $fen, $second) = explode(":", $now);

				if ($hour >= $start1 && $hour <= $end1) {
					if ($hour == $start1 && $hour == $end1) {
						if ($fen >= $start2 && $fen <= $end2) {
							$flag++; break;
						}
					} else {
						$flag++; break;
					}
				}
			}
			$data['flag'] = $flag;

			$this->load->view('news/radio_station', $data);
		}
		
	}