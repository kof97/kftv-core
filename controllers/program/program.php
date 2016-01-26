<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Program extends Ci_Controller 
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
			$this->load->model('program_model');

			self::$uName = $_SESSION['username'];
			self::$uName = self::$uName;			
 		}

/**
 * Edit By: LYJ
 * Time: 2015.8.20
 * Function: program
 * Review: LYJ . 2016.1.22
 */
		public function pAddView() 
		{
			$this->load->view('admin/program/p_add');
		}

		public function pAdd() 
		{
			/*-- picture --*/
			$savePath = 'KFTVresource/Program/pic/';
			$data = $this->program_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$programLogo = $data?$savePath . $file_name:"";
			
			$program_name = $this->input->post('catName', true);
			$program_principal = $this->input->post('principal', true);
			$programTime = $this->input->post('pTime', true);
			$programInfo = $this->input->post('catDetail', true);
			$using = $this->input->post('using')?1:0;
			$video = "";

			$this->program_model->pAdd($program_name, $program_principal, $programTime, $programInfo, $programLogo, $using, $video);

			redirect('program/program/pManage');		
		}

		public function pManage() 
		{
			/*-- page --*/
			$pageSize = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('program');
			$total = $this->db->count_all_results();
			$pageUri = 'program/program/pManage';

			$data['page'] = $this->program_model->page($pageSize, $offset, $total, $pageUri);
			$data['total'] = $total;
			$data['program'] = $this->program_model->getAllP($offset, $pageSize);

			$this->load->view('admin/program/p_list', $data);
		}

		public function using() 
		{
			$pid = intval($this->input->get("id"));
			$use = intval($this->input->get("using"));
			if ($use != 1 && $use != 0) {
				exit("error in variable use");
			}
			$status = $this->program_model->using($pid, $use);
			echo $status;
		}

		public function pDel() 
		{
			$pid = intval($this->uri->segment(4));

			$oldPic = $this->program_model->getProgramPicUrl($pid);
			$path = BASEPATH . "../" . $oldPic->program_logo;
			$result = @unlink ($path); 

			$this->program_model->pDel($pid);
			redirect('program/program/pManage/');
		}

		public function pEditView() 
		{
			$pid = intval($this->uri->segment(4));
			$data['program'] = $this->program_model->getProgramMessage($pid);

			$this->load->view('admin/program/p_edit', $data);
		}

		public function pUpdate() 
		{
			/*-- picture --*/
			$savePath = 'KFTVresource/Program/pic/';
	 	  	$data = $this->program_model->picture($savePath);

			$file_name = $data['upload_data']['file_name'];
			$programLogo = $data?$savePath . $file_name:"";
			
			$program_name = $this->input->post('catName', true);
			$program_principal = $this->input->post('principal', true);
			$programTime = $this->input->post('pTime', true);
			$programInfo = $this->input->post('catDetail', true);
			$using = $this->input->post('using')?1:0;
			$video = "";
			$pid = intval($this->uri->segment(4));

			$oldPic = $this->program_model->getProgramPicUrl($pid);
			if ($data) {
				$path = BASEPATH . "../" . $oldPic->program_logo;
				$result = @unlink ($path); 
			}
			$this->program_model->pUpdate($program_name, $program_principal, $programTime, $programInfo, $programLogo, $using, $video, $pid);
			redirect('program/program/pManage/');
		}

/**
 * Edit By: LYJ
 * Time: 2015.8.20
 * Function: video
 * Review: LYJ . 2016.1.22
 */
		public function pVideoAddView() 
		{
			$data['pubUser'] = self::$uName;
			$data['usingProgram'] = $this->program_model->getUsingProgram();

			$this->load->view('admin/program/pvideo_add', $data);
		}
	
		public function videoAdd() 
		{
			/*-- video --*/
			$savePath = 'KFTVresource/Program/video/';
			$data = $this->program_model->video($savePath);
			
			$file_name = $data['upload_data']['file_name'];
			$video_url = $data?$savePath . $file_name:"";

			$video_pic = "";
			$video_name = $this->input->post('title', true);
			$video_info = $this->input->post('artContent', true);
			$pub_user = self::$uName;
			$time = $this->input->post('pubTime') . " " . date("h:i:s");
			$ischecked = $this->input->post('ischecked')?1:0;
			$video_long = self::getTime($video_url);
			$video_long = self::fn($video_long);

			$pid = $this->input->post("check");
			$this->db->trans_start();
				foreach ($pid as $program_id) {
					$program_id = intval($program_id);
					$program_name = $this->program_model->getProgramNameById($program_id);
					$this->program_model->videoAdd($program_id, $program_name, $video_pic, $video_name, $video_info, $video_url, $time, $ischecked, $pub_user, $video_long);
				}
			$this->db->trans_complete();

			redirect("program/program/pVideoManage");
		}

		public function pVideoManage() 
		{
			$pid = $this->input->post('select');
			$pid = $pid?$pid:intval($this->uri->segment(4));
			$searchKey = $this->input->post('key')?$this->input->post('key'):"";
			/*-- page --*/
			$pageSize = 20;

			$this->db->from('program_video');
			if ($searchKey != "") {
				$this->db->like('program_name', $searchKey); 
			}
			if ($pid != 0) {
				$this->db->where('program_id', $pid);
			}
			$total = $this->db->count_all_results();
			$pageUri = 'program/program/pVideoManage/' . $pid;
			$offset = intval($this->uri->segment(5));

			$data['page'] = $this->program_model->page($pageSize, $offset, $total, $pageUri, 5);
			$data['total'] = $total;

			$data['pid'] = $pid;
			$data['usingProgram'] = $this->program_model->getUsingProgram();
			$data['pVideoList'] = $this->program_model->getProgramVideo($offset, $pageSize, $pid, $searchKey);
			$this->load->view('admin/program/video_list', $data);
		}

		public function ischecked() 
		{
			$vid = intval($this->input->get("id"));
			$ischecked = intval($this->input->get("check"));
			if ($ischecked != 0 && $ischecked != 1) {
				exit("error in variable ischecked");
			}
			$status = $this->program_model->ischecked($vid, $ischecked);
			echo $status;
		}

		public function pVideoDel() 
		{
			$check = $this->input->post('check');
			foreach ($check as $vid) {
				$vid = intval($vid);
				$oldVideoUrl = $this->program_model->getVideoUrl($vid);
				$path = BASEPATH . "../" . $oldVideoUrl->video_url;
				$result = @unlink ($path); 
		
				$this->program_model->pVideoDel($vid);
			}
			redirect('program/program/pVideoManage');
		}

		public function pVideoEditView() 
		{
			$data['pubUser'] = self::$uName;
			$vid = intval($this->uri->segment(4));

			$data['pVideoDetail'] = $this->program_model->getProgramVideoDetail($vid);
			$this->load->view('admin/program/video_detail', $data);
		}

		public function videoView() 
		{
			$vid = intval($this->uri->segment(4));
			$data['artDetail'] = $this->program_model->getVideo($vid);
			$this->load->view('admin/program/video_view', $data);			
		}

		public function videoUpdate() 
		{
			/*-- video --*/
			$savePath = 'KFTVresource/Program/video/';
			$data = $this->program_model->video($savePath);

			$file_name = $data['upload_data']['file_name'];
			$video_url = $data?$savePath . $file_name:"";

			$video_pic = "";
			$video_name = $this->input->post('title', true);
			$video_info = $this->input->post('artContent', true);
			$pub_user = self::$uName;
			$time = $this->input->post('pubTime') . " " . date("h:i:s");
			$ischecked = $this->input->post('ischecked')?1:0;
			$video_long = self::getTime($video_url);
			$video_long = self::fn($video_long);

			$vid = intval($this->uri->segment(4));
			$oldVideoUrl = $this->program_model->getVideoUrl($vid);
			if ($data) {
				$path = BASEPATH . "../" . $oldVideoUrl->video_url;
				$result = @unlink ($path); 
			}
			$this->program_model->videoUpdate($vid, $video_pic, $video_name, $video_info, $video_url, $time, $ischecked, $pub_user, $video_long);
			redirect('program/program/pVideoManage');
		}

		/*-- get the video's length --*/
		function BigEndian2Int($byte_word, $signed = false) 
		{
			$int_value = 0;
	
			$byte_wordlen = strlen($byte_word);
		
			for ($i = 0; $i < $byte_wordlen; $i++) {
				$int_value += ord($byte_word{$i}) * pow(256, ($byte_wordlen - 1 - $i));
	      	}
		
			if ($signed) {
				$sign_mask_bit = 0x80 << (8 * ($byte_wordlen - 1));

	          	if ($int_value & $sign_mask_bit) {
	              	$int_value = 0 - ($int_value & ($sign_mask_bit - 1));
	          	}
	      	}
	      	return $int_value;
		}

		/*-- translate time into digital --*/
		function getTime($name)
		{

			if ( !file_exists($name) ) {
				return;
			}
  			$flv_data_length=filesize($name);

  			$fp = @fopen($name, 'rb');
  			$flv_header = fread($fp, 5);
  			fseek($fp, 5, SEEK_SET);
  			$frame_size_data_length = self::BigEndian2Int(fread($fp, 4));
			
  			$flv_header_frame_length = 9;
			
  			if ($frame_size_data_length > $flv_header_frame_length) {
				fseek($fp, $frame_size_data_length - $flv_header_frame_length, SEEK_CUR);
  			}
  			$duration = 0;

  			while ((ftell($fp) + 1) < $flv_data_length) {
       			$this_tag_header = fread($fp, 16);
			
       			$data_length = self::BigEndian2Int(substr($this_tag_header, 5, 3));
       			$timestamp = self::BigEndian2Int(substr($this_tag_header, 8, 3));
			
       			$next_offset = ftell($fp) - 1 + $data_length;
			
       			if ($timestamp > $duration) {
       			 	$duration = $timestamp;
       			}
       			fseek($fp, $next_offset, SEEK_SET);
  			}
  			fclose($fp);
  			return $duration;
 	 	}

		//转化为0：03：56的时间格式
  		function fn($time)
  		{
      		$num = $time;
      		$sec = intval( $num / 1000 );
      		$h = intval( $sec / 3600 );
      		$m = intval( ($sec % 3600) / 60 );
      		$s = intval( ($sec % 60) );
      		$tm = $h . ':' . $m . ':' . $s;
		
      		return $tm;
		}

		
	}

