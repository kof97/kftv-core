<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Edit by: LYJ
 * Time: 2015.7.14
 * Review: LYJ . 2016.1.19
 */
class My extends CI_Controller
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
 			redirect("admin/my");
 		} else {
			$this->load->view("admin/index");
 		}
	}
	
	function check()
	{
		if (!isset($_SESSION) || !isset($_SESSION['authcode'])) {	
			exit();
		}
		if (strlen($_SESSION['authcode']) != 5) {
			exit();
		}

		$username = $this->input->post("u");
		$password = $this->input->post("psw");
		if (strlen($username) != 3 || strlen($password) != 32) {
			exit();
		}

		$true_yanzheng = $_SESSION['authcode'];   	
		$yanzheng = $this->input->post("check");
		unset($_SESSION['authcode']);
		if (strlen($yanzheng) != 5) {
			exit();
		}

		if ( $true_yanzheng != $yanzheng ) {			
			exit();
		} else if ($username != "nuo") {
			exit();
		} else if ($password != "bf4d3d3ddb6c3d35c31de5fb2caebe50") {
			exit();
		} else {
			$_SESSION["id"] = 0;
			$_SESSION["username"] = "nuo";
			$_SESSION["admin"] = 'kftv';
			$_SESSION['authcode'] = "";

			redirect("admin/login/index");
		} 
	}
	
	function kof()
	{
		echo "	<form action='" . site_url("admin/my/check") . "' method='post'>
					<input type='password' name='u'>
					<input type='password' name='psw'><br />
					<input type='text' name='check'>
					<img width='65' height='28' border='0' src='" . base_url() . "/images/login/image.php' /><br />
					<input type='submit' />
				</form>";
	}

	function top()
	{
		$ok = $_SESSION['admin'];
 		if (!isset($ok) || $ok != 'kftv') {
 			redirect("admin/login/kof");
 		}
		$data['name'] = $_SESSION['username'];
		$this->load->view("admin/top", $data);
	}
	
	function left()
	{
		$ok = $_SESSION['admin'];
 		if (!isset($ok) || $ok != 'kftv') {
 			redirect("admin/login/kof");
 		}
		$this->load->view("admin/left");
	}
	
	function main()
	{
		$ok = $_SESSION['admin'];
 		if (!isset($ok) || $ok != 'kftv') {
 			redirect("admin/login/kof");
 		}
		$this->load->view("admin/main");
	}

}