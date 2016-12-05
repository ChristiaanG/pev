<?php

class User extends CI_Model{
    function __construct() {
        $this->tableName = 'users';
        $this->primaryKey = 'id';
        $this->tableName2 = "games";
    }
    public function checkUser($data = array(), $gdata = array()){
        $this->db->select($this->primaryKey);
        $this->db->from($this->tableName);
        $this->db->where(array('oauth_provider'=>$data['oauth_provider'],'oauth_uid'=>$data['oauth_uid']));
        $prevQuery = $this->db->get();
        $prevCheck = $prevQuery->num_rows();

        //print_r($data);

        if($prevCheck > 0){
            $prevResult = $prevQuery->row_array();
            $data['modified'] = date("d-m-Y H:i:s");
            $update = $this->db->update($this->tableName,$data,array('id'=>$prevResult['id']));
            $userID = $prevResult['id'];
        }else{
           // $data['created'] = date("d-m-Y H:i:s");
            $data['modified'] = date("d-m-Y H:i:s");
            $insert = $this->db->insert($this->tableName,$data);
            $userID = $this->db->insert_id();
        }

        return $userID?$userID:FALSE;
    }
}
?> 