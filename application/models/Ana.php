<?php
/**
 * Created by Lance.
 * Date: 2018/07/06 10:00
 */

class Ana extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getRandomAna() {
        $sql = 'SELECT * FROM t_ana WHERE id >= ((SELECT MAX(id) FROM t_ana)-(SELECT MIN(id) FROM t_ana)) * RAND() + (SELECT MIN(id) FROM t_ana)  LIMIT 1';
        $query = $this->db->query($sql);
        return $query->first_row();
    }
}