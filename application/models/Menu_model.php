<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        $query = "  SELECT user_sub_menu.*, user_menu.menu 
                    FROM user_sub_menu JOIN user_menu 
                    ON user_sub_menu.menu_id = user_menu.Id";
        return $this->db->query($query)->result_array();
    }

    public function addSubmenu()
    {
        $title = $this->input->post('title');
        $menu_id = $this->input->post('menu_id');
        $url = $this->input->post('url');
        $icon = $this->input->post('icon');
        $active = $this->input->post('is_active');

        $data = [
            'menu_id' => $menu_id,
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'is_active' => $active
        ];
        $this->db->insert('user_sub_menu',$data);
    }

    public function editSub()
    {
        $id = $this->input->post('Id');
        $title = $this->input->post('title');
        $menu_id = $this->input->post('menu_id');
        $url = $this->input->post('url');
        $icon = $this->input->post('icon');
        $active = $this->input->post('is_active');

        $data = [
            'menu_id' => $menu_id,
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'is_active' => $active
        ];

        $this->db->where('Id',$id);
        $this->db->update('user_sub_menu',$data);
    }
}