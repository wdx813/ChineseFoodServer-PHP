<?php
/**
 * Created by Lance.
 * Date: 2018/07/05 16:29
 */

class Food extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getFoodsByPage($provinceId, $pageIndex) {
        $offset = ($pageIndex - 1) * PAGE_SIZE;
        $query = $this->db->get_where('t_food', array('province_id' => $provinceId), PAGE_SIZE, $offset);
        return $query->result();
    }

    public function getFoodById($foodId) {
        $query = $this->db->get_where('t_food', array('id' => $foodId));
        return $query->first_row();
    }

    public function updateFood($food) {
        $this->db->trans_start();
        $this->db->update('t_food', $food, array('id' => $food->id));
        $this->db->trans_complete();
    }
}