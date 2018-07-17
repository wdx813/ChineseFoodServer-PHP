<?php
/**
 * Created by Lance.
 * Date: 2018/07/04 15:15
 */

class User extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getUserByOpenId($openId) {
        $query = $this->db->get_where('t_user', array('openId' => $openId));
        return $query->first_row();
    }

    public function save($openId, $nickname, $gender, $avatar) {
        $data = array(
            'openId' => $openId,
            'nickname' => $nickname,
            'gender' => $gender,
            'avatar' => $avatar,
            'taste_num' => 0,
            'create_time' => date('Y-m-d H:i:s')
        );

        $this->db->trans_start();
        $this->db->insert('t_user', $data);
        $this->db->trans_complete();
    }

    public function updateUser($user) {
        $this->db->trans_start();
        $this->db->update('t_user', $user, array('id' => $user->id));
        $this->db->trans_complete();
    }

    public function getUserCount() {
        return $this->db->count_all('t_user');
    }

    public function getSurpassCount($tasteNum) {
        $this->db->where('taste_num <', $tasteNum);
        return $this->db->count_all_results('t_user');
    }

}