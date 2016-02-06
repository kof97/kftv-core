<?php 
	if ( ! defined('BASEPATH') ) exit('No direct script access allowed');
	error_reporting(E_ALL ^ E_NOTICE);
	
	class F extends Ci_Controller 
	{

		public function __construct() 
		{
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
			$this->load->model('feature_model');
 		}

/**
 * PHP version 5
 * Reviewed by LYJ . 2016.1.25
 *
 * @category PHP
 * @author 	 LYJ <1048434786@qq.com>
 * @version  2015.10.16
 * @link 	 https://git.oschina.net/kofyu/KFTV-complete
 */
		public function feature() 
		{
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

		public function fl() 
		{
			$data['fid'] = intval($this->uri->segment(4));
			$data['cid'] = intval($this->uri->segment(5));
			$data['feature'] = $this->feature_model->getFeature($data['fid']);
			$data['logo'] = $this->feature_model->getLogo($data['fid'], "logo" . $data['fid']);
			$data['category'] = $this->feature_model->getCats($data['fid'], 0, 6);
			/*-- page --*/
			$pageSize = 2;
			$offset = intval($this->uri->segment(6));

			$this->db->from('feature_article');
			$this->db->where('cid', $data['cid']);
			$total = $this->db->count_all_results();

			$pageUri = 'feature/f/fl/' . $data['fid'] . '/' . $data['cid'];
			$data['page'] = $this->feature_model->page($pageSize, $offset, $total, $pageUri, 6);
			
			$data['cat'] = $this->feature_model->getCat($data['fid'], $data['cid']);
			$data['list'] = $this->feature_model->getArts($offset, $pageSize, $data['fid'], $data['cid']);
			$this->load->view('feature/list', $data);
		}

		public function fa() 
		{
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