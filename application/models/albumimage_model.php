<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class albumimage_model extends CI_Model
{
public function create($album,$name,$image,$status)
{
$data=array("album" => $album,"name" => $name,"image" => $image,"status" => $status);
$query=$this->db->insert( "school_albumimage", $data );
$id=$this->db->insert_id();
if(!$query)
return  0;
else
return  $id;
}
public function beforeedit($id)
{
$this->db->where("id",$id);
$query=$this->db->get("school_albumimage")->row();
return $query;
}
function getsinglealbumimage($id){
$this->db->where("id",$id);
$query=$this->db->get("school_albumimage")->row();
return $query;
}
public function edit($id,$album,$name,$image,$status)
{
$data=array("album" => $album,"name" => $name,"image" => $image,"status" => $status);
$this->db->where( "id", $id );
$query=$this->db->update( "school_albumimage", $data );
return 1;
}
public function delete($id)
{
$query=$this->db->query("DELETE FROM `school_albumimage` WHERE `id`='$id'");
return $query;
}
}
?>
