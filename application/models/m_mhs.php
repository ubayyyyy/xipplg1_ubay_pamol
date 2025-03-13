<?php

class M_mhs extends CI_model {
    public function get_data ()
    {
      return $this->db->get('tb_mhs')->result_array();
    }
}