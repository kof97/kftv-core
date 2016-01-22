<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Edit by : LYJ
 * Time : 2015.7.14
 * Review : LYJ . 2016.1.20
 */
class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper("url");
		$this->load->database();
		session_start();
	}
	
	function index()
	{
 		$ok = $_SESSION['admin'];
 		if (!isset($ok) || $ok != 'kftv') {
 			redirect("admin/login/denglu");
 		} else {
			$this->load->view("admin/index");
 		}
	}

	function check() 
	{
		$ok = $_SESSION['admin'];
		if (!isset($ok) || $ok != 'kftv') {
 			redirect("admin/login/denglu");
 		}
	}
	
	function check_login()
	{
		if ( !isset($_SESSION) || !isset($_SESSION['authcode']) ) {	
			redirect("admin/login/denglu");
			return 0;
		}

		// first captcha check
		$true_code = $_SESSION['authcode'];   
		$captcha = $this->input->post("check");
		unset($_SESSION['authcode']);
		if (strlen($true_code) != 5 || strlen($captcha) != 5) {
			redirect("admin/login/denglu/2");
			return 0;
		}

		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$password = md5(md5($password) . '107');
		if (strlen($username) > 20) {
			// user message error
			redirect("admin/login/denglu/1");
			return 0;
		}

		$this->load->model("admin_model");
		$user = $this->admin_model->check($username, $password);

		if ( $true_code != $captcha ) {
			// captcha
			redirect("admin/login/denglu/2");
			return 0;
		} else if ($user) {
			$_SESSION["id"] = $user['id'];
			$_SESSION["username"] = $user['user_name'];
			$_SESSION["admin"] = 'kftv';
			$_SESSION['authcode'] = "";

			redirect("admin/login/index");
		} else {
			// user message error
			redirect("admin/login/denglu/1");
			return 0;
		}
	}
	
	function denglu()
	{
		$this->load->view("admin/login");
	}
	
	function modify()
	{
		$name = $_SESSION["username"];
		$this->load->model("admin_model");
		$data["message"] = $this->admin_model->get_admin_message($name);

		$this->load->view("admin/change_info", $data);
	}
	
	function check_modify()
	{
		$id = $this->input->post("id");
		$name = $this->input->post("username");
		$this->load->model("admin_model");
		$data["message"] = $this->admin_model->get_admin_message($name);
		$result = $this->admin_model->updata_message($id);
		if($result == true){
			redirect("admin/login/modify/1");
		}else{
			redirect("admin/login/modify/2");
		}
	}
	
	function quit()
	{
		session_unset();
		session_destroy();  
		$this->load->view("admin/login");
	}
	
	function top()
	{
 		self::check();
		$data['name'] = $_SESSION['username'];
		$this->load->view("admin/top", $data);
	}
	
	function left()
	{
 		self::check();
		$this->load->view("admin/left");
	}
	
	function main()
	{
 		self::check();
		$this->load->view("admin/main");
	}

}