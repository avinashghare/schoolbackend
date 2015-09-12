<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class contact_model extends CI_Model
{
public function create($name,$email,$message,$status)
{
$data=array("name" => $name,"email" => $email,"message" => $message,"status" => $status);
$query=$this->db->insert( "school_contact", $data );
$id=$this->db->insert_id();
if(!$query)
return  0;
else
return  $id;
}
public function beforeedit($id)
{
$this->db->where("id",$id);
$query=$this->db->get("school_contact")->row();
return $query;
}
function getsinglecontact($id){
$this->db->where("id",$id);
$query=$this->db->get("school_contact")->row();
return $query;
}
public function edit($id,$name,$email,$message,$status)
{
$data=array("name" => $name,"email" => $email,"message" => $message,"status" => $status);
$this->db->where( "id", $id );
$query=$this->db->update( "school_contact", $data );
return 1;
}
public function delete($id)
{
$query=$this->db->query("DELETE FROM `school_contact` WHERE `id`='$id'");
return $query;
}
}
?>
