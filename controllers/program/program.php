<?php 

	define('ACC',true);
	
	class Program extends Ci_Controller {
		public static $uName;

		public function __construct() {
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));
			session_start();
			self::$uName = $_SESSION['username'];
			self::$uName = addslashes(self::$uName);

			$ok = $_SESSION['admin'];
	 		if(!isset($ok) || $ok != 'kftv'){
	 			redirect("admin/login/denglu");
	 		}
 		}

/*
*	Edit By : 阿诺
*	Time : 2015.8.20
*	function : 后台控制器-电视栏目管理
*/
		public function pAddView() {
			$this->load->view('admin/program/p_add');
		}

		public function pAdd() {
			$this->load->model('program_model');
/*----图片上传-----*/
			$save_path = 'KFTVresource/Program/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
	  		$config['allowed_types'] = 'jpg|jpeg|gif|png';
	  		$config['max_size'] = '2048';
	  		$config['max_width']  = 0;
	  		$config['max_height']  = 0;
	  		$config['file_name'] = date("Ymdhis");
	  
	  		$this->load->library('upload', $config);
	 
/*	 	*/	$up = $this->upload->do_upload('userfile');
/*  		if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
	  		 	exit(var_dump($error));
	  		} else {*/
	 	  		$data = array('upload_data' => $this->upload->data());
/*			}
/*----图片上传-----*/
			$file_name = $data['upload_data']['file_name'];
			$program_logo = $up?$save_path . $file_name:"";
			
			$program_name = addslashes($this->input->post('catName'));
			$program_principal = addslashes($this->input->post('principal'));
			$program_time = addslashes($this->input->post('pTime'));
			$program_info = addslashes($this->input->post('catDetail'));
			$using = $this->input->post('using')?1:0;
			$video = "";

			$this->program_model->pAdd($program_name, $program_principal, $program_time, $program_info, $program_logo, $using, $video);

			redirect('program/program/pManage');		
		}

		public function pManage() {
			$this->load->model('program_model');

/*-----page-----*/
			$page_size = 20;
			$offset = intval($this->uri->segment(4));

			$this->db->from('program');
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('program/program/pManage');
			$config['total_rows'] = $total;
			$config['per_page'] = $page_size;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['prev_link'] = '上一页';
			$config['next_link'] = '下一页';
			$config['uri_segment'] = 4;

			$this->pagination->initialize($config);
			$data['page'] = $this->pagination->create_links();
/*-----page-----*/
			$data['total'] = $total;
			$data['program'] = $this->program_model->getAllP($offset, $page_size);

			$this->load->view('admin/program/p_list', $data);
		}

		public function using() {
			$this->load->model('program_model');

			$pid = $this->uri->segment(4);
			$use = $this->uri->segment(5);
			$offs = $this->uri->segment(6);

			$this->program_model->using($pid, $use);
			redirect('program/program/pManage/' . $offs);
		}

		public function pDel() {
			$this->load->model('program_model');

			$pid = $this->uri->segment(4);

			$oldPic = $this->program_model->getProgramPicUrl($pid);
			$path = BASEPATH . "../" . $oldPic->program_logo;
			$result = @unlink ($path); 

			$this->program_model->pDel($pid);
			redirect('program/program/pManage/');
		}

		public function pEditView() {
			$this->load->model('program_model');

			$pid = $this->uri->segment(4);
			$data['program'] = $this->program_model->getProgramMessage($pid);

			$this->load->view('admin/program/p_edit', $data);
		}

		public function pUpdate() {
			$this->load->model('program_model');
/*----图片上传-----*/
			$save_path = 'KFTVresource/Program/pic/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
	  		$config['allowed_types'] = 'jpg|jpeg|gif|png';
	  		$config['max_size'] = '2048';
	  		$config['max_width']  = 0;
	  		$config['max_height']  = 0;
	  		$config['file_name'] = date("Ymdhis");
	  
	  		$this->load->library('upload', $config);
	 
/*	 	*/	$up = $this->upload->do_upload('userfile');
/*  		if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
	  		 	exit(var_dump($error));
	  		} else {*/
	 	  		$data = array('upload_data' => $this->upload->data());
/*			}
/*----图片上传-----*/
			$file_name = $data['upload_data']['file_name'];
			$program_logo = $up?$save_path . $file_name:"";
			
			$program_name = addslashes($this->input->post('catName'));
			$program_principal = addslashes($this->input->post('principal'));
			$program_time = addslashes($this->input->post('pTime'));
			$program_info = addslashes($this->input->post('catDetail'));
			$using = $this->input->post('using')?1:0;
			$video = "";

			$pid = $this->uri->segment(4);

			$oldPic = $this->program_model->getProgramPicUrl($pid);
			if ($up) {
				$path = BASEPATH . "../" . $oldPic->program_logo;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}

			$this->program_model->pUpdate($program_name, $program_principal, $program_time, $program_info, $program_logo, $using, $video, $pid);
			redirect('program/program/pManage/');
		}

/*
*	Edit By : 阿诺
*	Time : 2015.8.20
*	function : 后台控制器-电视栏目视频管理
*/
		public function pVideoAddView() {
			$this->load->model('program_model');

			$data['pubUser'] = self::$uName;
			$data['usingProgram'] = $this->program_model->getUsingProgram();

			$this->load->view('admin/program/pvideo_add', $data);
		}
	
		public function videoAdd() {
			$this->load->model('program_model');
/*----视频上传-----*/
			$save_path = 'KFTVresource/Program/video/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
	  		$config['allowed_types'] = 'wmv|mp4|mpeg|avi|mov|3gp|flv|f4v|rmvb';
	  		$config['max_size'] = '102400';
	  		$config['max_width']  = 0;
	  		$config['max_height']  = 0;
	  		$config['file_name'] = date("Ymdhis");
	  
	  		$this->load->library('upload', $config);
	 
/*	 	*/	$up = $this->upload->do_upload('userfile');
/*调试开启  if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
	  		 	exit(var_dump($error));
	  		} else {*/
	 	  		$data = array('upload_data' => $this->upload->data());
	/*		}*/
/*----视频上传-----*/
			$file_name = $data['upload_data']['file_name'];
			$video_url = $up?$save_path . $file_name:"";

			$video_pic = "";
			$video_name = addslashes($this->input->post('title'));
			$video_info = $this->input->post('artContent');
			$pub_user = self::$uName;
			$time = $this->input->post('pubTime') . " " . date("h:i:s");
			$ischecked = $this->input->post('ischecked')?1:0;
			$video_long = self::getTime($video_url);
			$video_long = self::fn($video_long);

			$pid = $this->input->post("check");

			foreach ($pid as $program_id) {
				$program_name = $this->program_model->getProgramNameById($program_id);
				$this->program_model->videoAdd($program_id, $program_name, $video_pic, $video_name, $video_info, $video_url, $time, $ischecked, $pub_user, $video_long);
			}
			redirect("program/program/pVideoManage");
		}

		public function pVideoManage() {
			$this->load->model('program_model');

			$pid = $this->input->post('select');
			$pid = $pid?$pid:intval($this->uri->segment(4));
			$searchKey = $this->input->post('key')?addslashes($this->input->post('key')):"";
/*-----page-----*/
			$page_size = 20;

			$this->db->from('program_video');
			$this->db->like('program_name', $searchKey); 

			if ($pid != 0) {
				$this->db->where('program_id', $pid);
			}
			$total = $this->db->count_all_results();

			$config['base_url'] = site_url('program/program/pVideoManage/' . $pid);
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
			$data['total'] = $total;

			$data['pid'] = $pid;
			$data['usingProgram'] = $this->program_model->getUsingProgram();
			$data['pVideoList'] = $this->program_model->getProgramVideo($offset, $page_size, $pid, $searchKey);
			$this->load->view('admin/program/video_list', $data);
		}

		public function ischecked() {
			$this->load->model('program_model');

			$pid = $this->uri->segment(4);
			$vid = $this->uri->segment(5);
			$ischecked = $this->uri->segment(6);
			$offs = $this->uri->segment(7);

			$this->program_model->ischecked($vid, $ischecked);
			redirect('program/program/pVideoManage/' . $pid . '/' . $offs);
		}

		public function pVideoDel() {
			$this->load->model('program_model');

			$check = $this->input->post('check');
			foreach ($check as $vid) {
				$oldVideoUrl = $this->program_model->getVideoUrl($vid);
				$path = BASEPATH . "../" . $oldVideoUrl->video_url;
				$result = @unlink ($path); 
		
				$this->program_model->pVideoDel($vid);
			}

			redirect('program/program/pVideoManage');
		}

		public function pVideoEditView() {
			$this->load->model('program_model');

			$data['pubUser'] = self::$uName;
			$vid = $this->uri->segment(4);

			$data['pVideoDetail'] = $this->program_model->getProgramVideoDetail($vid);
			$this->load->view('admin/program/video_detail', $data);
		}

		public function videoView() {
			$this->load->model('program_model');

			$vid = $this->uri->segment(4);
			$data['artDetail'] = $this->program_model->getVideo($vid);
			$this->load->view('admin/program/video_view', $data);			
		}

		public function videoUpdate() {
			$this->load->model('program_model');
/*----视频上传-----*/
			$save_path = 'KFTVresource/Program/video/';

			$config['overwrite']  = true;
	  		$config['encrypt_name']  = true;

			$config['upload_path'] = './' . $save_path;
	  		$config['allowed_types'] = 'wmv|mp4|mpeg|avi|mov|3gp|flv|f4v|rmvb';
	  		$config['max_size'] = '102400';
	  		$config['max_width']  = 0;
	  		$config['max_height']  = 0;
	  		$config['file_name'] = date("Ymdhis");
	  
	  		$this->load->library('upload', $config);
	 
/*	 	*/	$up = $this->upload->do_upload('userfile');
/*  		if ( ! $up ) {
				$error = array('error' => $this->upload->display_errors());
	  		 	exit(var_dump($error));
	  		} else {*/
	 	  		$data = array('upload_data' => $this->upload->data());
/*			}
/*----视频上传-----*/
			$file_name = $data['upload_data']['file_name'];
			$video_url = $up?$save_path . $file_name:"";

			$video_pic = "";
			$video_name = addslashes($this->input->post('title'));
			$video_info = $this->input->post('artContent');
			$pub_user = self::$uName;
			$time = $this->input->post('pubTime') . " " . date("h:i:s");
			$ischecked = $this->input->post('ischecked')?1:0;
			$video_long = self::getTime($video_url);
			$video_long = self::fn($video_long);

			$vid = $this->uri->segment(4);
			$oldVideoUrl = $this->program_model->getVideoUrl($vid);

			if ($up) {
				$path = BASEPATH . "../" . $oldVideoUrl->video_url;
				$result = @unlink ($path); 
			/*	if($result == true){
					echo  "删除成功";
				}
				else{
					exit("删除失败");
				}*/
			}
			$this->program_model->videoUpdate($vid, $video_pic, $video_name, $video_info, $video_url, $time, $ischecked, $pub_user, $video_long);
			redirect('program/program/pVideoManage');
		}

/*------获取视频长度--------*/
		function BigEndian2Int($byte_word, $signed = false) {
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

//获得视频的数字时间

		function getTime($name){

			if(!file_exists($name)){
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
  		function fn($time){

      		$num = $time;
      		$sec = intval( $num / 1000 );
      		$h = intval( $sec / 3600 );
      		$m = intval( ($sec % 3600) / 60 );
      		$s = intval( ($sec % 60) );
      		$tm = $h . ':' . $m . ':' . $s;
		
      		return $tm;
		}

       	







		
	}

