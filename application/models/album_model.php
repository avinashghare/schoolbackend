<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class album_model extends CI_Model
{
    public function create($name,$image,$status)
    {
        $data=array("name" => $name,"image" => $image,"status" => $status);
        $query=$this->db->insert( "school_album", $data );
        $id=$this->db->insert_id();
        if(!$query)
        return  0;
        else
        return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("school_album")->row();
        return $query;
    }
    function getsinglealbum($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("school_album")->row();
        return $query;
    }
    public function edit($id,$name,$image,$status)
    {
        $data=array("name" => $name,"image" => $image,"status" => $status);
        $this->db->where( "id", $id );
        $query=$this->db->update( "school_album", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `school_album` WHERE `id`='$id'");
        return $query;
    }
	public function getalbumimagebyid($id)
	{
		$query=$this->db->query("SELECT `image` FROM `school_album` WHERE `id`='$id'")->row();
		return $query;
	}
}
?>
