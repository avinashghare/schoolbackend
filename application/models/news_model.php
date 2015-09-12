<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class news_model extends CI_Model
{
    public function create($title,$description,$startdate,$enddate,$status)
    {
        $data=array("title" => $title,"description" => $description,"startdate" => $startdate,"enddate" => $enddate,"status" => $status);
        $query=$this->db->insert( "school_news", $data );
        $id=$this->db->insert_id();
        if(!$query)
        return  0;
        else
        return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("school_news")->row();
        return $query;
    }
    function getsinglenews($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("school_news")->row();
        return $query;
    }
    public function edit($id,$title,$description,$startdate,$enddate,$status)
    {
        $data=array("title" => $title,"description" => $description,"startdate" => $startdate,"enddate" => $enddate,"status" => $status);
        $this->db->where( "id", $id );
        $query=$this->db->update( "school_news", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `school_news` WHERE `id`='$id'");
        return $query;
    }
}
?>
