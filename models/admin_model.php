<?php
class Admin_model extends CI_Model{
	public static $pdo;

	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();

		$database = $this->db->database;
		$dbuser = $this->db->username;
        $dbpassword = $this->db->password;
		$conn = "mysql:host=localhost;dbname=$database;charset=utf8";
		self::$pdo = new PDO($conn, $dbuser, $dbpassword);
		self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);	//禁用模拟预处理语句,保证sql语句不被php解析
		self::$pdo->exec("set names utf8");	
	}
	
/*
 * Edit By : LYJ
 * Time : 2015.7.14
 * Review : LYJ . 2016.1.21
 */
	function check($un, $pwd)
	{
		$pdo = self::$pdo;
		$sql = "select * from admin_user where user_name = ? and password = ?";
		$stmt = $pdo->prepare($sql);
		$exec = $stmt->execute(array($un, $pwd));
		if ($exec) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		return $row;
	}
	
	function get_admin_message($username) 
	{
		$query = $this->db->query("select * from admin_user where user_name = '$username'");
		return $query->row();
	}
	
	function updata_message($id)
	{
		$password = $this->input->post("password");
		$password = md5(md5($password) . '107');
		$data = array( 'password'=>$password );
		$this->db->where("id", $id);
		if ( $this->db->update("admin_user", $data) ) {
			$flag = true;
		} else {
			$flag = false;
		}
		return $flag;
	}


}






