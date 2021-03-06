<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wall_model extends CI_Model {
	public function login($info){
		//passed in $info from controller
		$this->load->helper('security');
		$email = $info['email'];
		$record = $this->retrieve_record($email);
		if ($record['password'] == $info['password'] ) {
			$this->session->set_userdata('user_data',['id' => $record['id']]);
			return $record;
		}
		else{
			return false;
		}
	}
	public function retrieve_record($email){
		$query = "SELECT * FROM users WHERE email=?";
		return $this->db->query($query, array($email))->row_array();
	}

	public function register(){
		$this->load->helper('security');
		$email = $info['email'];
		$record = $this->retrieve_record($email);
		$query = "INSERT INTO users (first_name, last_name,
							email, password, created_date, modified_date) VALUES (?, ?, ?, ?, NOW(), NOW())";
		$values = [$info['first_name'],$info['last_name'],
							$email,$info['password']];
		return $this->db->query($query,$values);
	}
	public function get_messages(){
		$query = "SELECT messages.id AS message_id, first_name, last_name, message, messages.created_on, messages.users_id AS messages_users_id FROM messages
	  LEFT JOIN users
	  ON users.id = messages.users_id";
		return $this->db->query($query);

	}

	public function get_comments(){
		$query = "SELECT comments.id AS 	comment_id,first_name, last_name, comment, comments.created_on, messages_users_id, messages_id  FROM comments   LEFT JOIN users   ON users.id = comments.users_id";
		return $this->db->query($query);
	}



	public function add_message($message,$current_user){
		$query = "INSERT INTO messages (message, created_on, modified_on, users_id) VALUES (?, NOW(), NOW(), ?)";
		$values = [$message,$current_user];
		return $this->db->query($query);
	}

	public function add_comment($message_id, $comment, $messages_users_id){
		$query = "INSERT INTO comments (comment, created_on, modifed_on, users_id, messages_id, messages_users_id) VALUES ('".$comment."', NOW(), NOW(), '".$_SESSION['currentUser']."','".$message_id."','".$messages_users_id."')";

	  run_mysql_query($query);

	  get_posts_and_comments();
	}
}
