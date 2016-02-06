<?php
if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Admin_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'pagination'));
		$this->load->helper('url');
		$this->load->database();
	}
	
/**
 * PHP version 5
 * Reviewed by LYJ . 2016.1.21
 *
 * @category PHP
 * @author 	 LYJ <1048434786@qq.com>
 * @version  2015.7.14
 * @link 	 https://git.oschina.net/kofyu/KFTV-complete
 */
	function check($un, $pwd)
	{
		$stmt = $this->db->conn_id->stmt_init();
		$sql = "select id, user_name from admin_user where user_name = ? and password = ?";
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param("ss", $un, $pwd);
		$stmt->bind_result($id, $name);
		$stmt->execute();
		while ($stmt->fetch()) {
			$res = array('id' => $id, 'user_name' => $name);
		}
		$stmt->free_result();
		$stmt->close();

		return $res;
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