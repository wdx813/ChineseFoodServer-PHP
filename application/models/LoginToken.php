<?php
/**
 * Created by Lance.
 * Date: 2018/07/05 11:54
 */

class LoginToken extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function checkToken($openId) {
        $query = $this->db->get_where('t_login_token', array('openId' => $openId));
        return $query->first_row();
    }

    public function saveToken($openId, $token) {
        $data = array(
            'openId' => $openId,
            'token' => $token
        );
        $this->db->trans_start();
        $this->db->insert('t_login_token', $data);
        $this->db->trans_complete();
    }
}