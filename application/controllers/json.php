<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Json extends CI_Controller 
{function getallalbum()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_album`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";

$elements=array();
$elements[1]=new stdClass();
$elements[1]->field="`school_album`.`name`";
$elements[1]->sort="1";
$elements[1]->header="Name";
$elements[1]->alias="name";

$elements=array();
$elements[2]=new stdClass();
$elements[2]->field="`school_album`.`image`";
$elements[2]->sort="1";
$elements[2]->header="Image";
$elements[2]->alias="image";

$elements=array();
$elements[3]=new stdClass();
$elements[3]->field="`school_album`.`status`";
$elements[3]->sort="1";
$elements[3]->header="Status";
$elements[3]->alias="status";

$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_album`");
$this->load->view("json",$data);
}
public function getsinglealbum()
{
$id=$this->input->get_post("id");
$data["message"]=$this->album_model->getsinglealbum($id);
$this->load->view("json",$data);
}
function getallalbumimage()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_albumimage`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";

$elements=array();
$elements[1]=new stdClass();
$elements[1]->field="`school_albumimage`.`album`";
$elements[1]->sort="1";
$elements[1]->header="Album";
$elements[1]->alias="album";

$elements=array();
$elements[2]=new stdClass();
$elements[2]->field="`school_albumimage`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";

$elements=array();
$elements[3]=new stdClass();
$elements[3]->field="`school_albumimage`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";

$elements=array();
$elements[4]=new stdClass();
$elements[4]->field="`school_albumimage`.`status`";
$elements[4]->sort="1";
$elements[4]->header="Status";
$elements[4]->alias="status";

$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_albumimage`");
$this->load->view("json",$data);
}
public function getsinglealbumimage()
{
$id=$this->input->get_post("id");
$data["message"]=$this->albumimage_model->getsinglealbumimage($id);
$this->load->view("json",$data);
}
function getallnews()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_news`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";

$elements=array();
$elements[1]=new stdClass();
$elements[1]->field="`school_news`.`title`";
$elements[1]->sort="1";
$elements[1]->header="Title";
$elements[1]->alias="title";

$elements=array();
$elements[2]=new stdClass();
$elements[2]->field="`school_news`.`description`";
$elements[2]->sort="1";
$elements[2]->header="Description";
$elements[2]->alias="description";

$elements=array();
$elements[3]=new stdClass();
$elements[3]->field="`school_news`.`startdate`";
$elements[3]->sort="1";
$elements[3]->header="Start Date";
$elements[3]->alias="startdate";

$elements=array();
$elements[4]=new stdClass();
$elements[4]->field="`school_news`.`enddate`";
$elements[4]->sort="1";
$elements[4]->header="End Date";
$elements[4]->alias="enddate";

$elements=array();
$elements[5]=new stdClass();
$elements[5]->field="`school_news`.`status`";
$elements[5]->sort="1";
$elements[5]->header="Status";
$elements[5]->alias="status";

$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_news`");
$this->load->view("json",$data);
}
public function getsinglenews()
{
$id=$this->input->get_post("id");
$data["message"]=$this->news_model->getsinglenews($id);
$this->load->view("json",$data);
}
function getallcontact()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_contact`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";

$elements=array();
$elements[1]=new stdClass();
$elements[1]->field="`school_contact`.`name`";
$elements[1]->sort="1";
$elements[1]->header="Name";
$elements[1]->alias="name";

$elements=array();
$elements[2]=new stdClass();
$elements[2]->field="`school_contact`.`email`";
$elements[2]->sort="1";
$elements[2]->header="Email";
$elements[2]->alias="email";

$elements=array();
$elements[3]=new stdClass();
$elements[3]->field="`school_contact`.`message`";
$elements[3]->sort="1";
$elements[3]->header="Message";
$elements[3]->alias="message";

$elements=array();
$elements[4]=new stdClass();
$elements[4]->field="`school_contact`.`status`";
$elements[4]->sort="1";
$elements[4]->header="Status";
$elements[4]->alias="status";

$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_contact`");
$this->load->view("json",$data);
}
public function getsinglecontact()
{
$id=$this->input->get_post("id");
$data["message"]=$this->contact_model->getsinglecontact($id);
$this->load->view("json",$data);
}
} ?>