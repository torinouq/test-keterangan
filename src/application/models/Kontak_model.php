<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kontak_model extends CI_Model

{

    public function get_all() {
        return $this->db->get('telepon')->result();
    }
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('telepon')->row();
    }
    public function insert($data) {
        // return $this->db->insert('telepon', $data);
        // Cek jika 'id' sudah ada di database
        $query = $this->db->get_where('telepon', ['id' => $data['id']]);
        
        // Jika 'id' sudah ada, jangan lakukan insert dan kembalikan nilai false atau pesan kesalahan
        if ($query->num_rows() > 0) {
            // 'id' sudah ada, handle sesuai kebutuhan Anda
            return false; // atau bisa juga melempar exception atau mengembalikan pesan kesalahan
        }
        
        // Jika 'id' belum ada, lakukan insert
        return $this->db->insert('telepon', $data);
    }
    public function update($id, $data) {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('telepon', $data, ['id' => $id]);
    }
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('telepon');
    }
}