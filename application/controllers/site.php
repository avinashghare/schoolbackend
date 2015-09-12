<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site extends CI_Controller 
{
	public function __construct( )
	{
		parent::__construct();
		
		$this->is_logged_in();
	}
	function is_logged_in( )
	{
		$is_logged_in = $this->session->userdata( 'logged_in' );
		if ( $is_logged_in !== 'true' || !isset( $is_logged_in ) ) {
			redirect( base_url() . 'index.php/login', 'refresh' );
		} //$is_logged_in !== 'true' || !isset( $is_logged_in )
	}
	function checkaccess($access)
	{
		$accesslevel=$this->session->userdata('accesslevel');
		if(!in_array($accesslevel,$access))
			redirect( base_url() . 'index.php/site?alerterror=You do not have access to this page. ', 'refresh' );
	}
	public function index()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$data[ 'page' ] = 'dashboard';
		$data[ 'title' ] = 'Welcome';
		$this->load->view( 'template', $data );	
	}
	public function createuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
//        $data['category']=$this->category_model->getcategorydropdown();
		$data[ 'page' ] = 'createuser';
		$data[ 'title' ] = 'Create User';
		$this->load->view( 'template', $data );	
	}
	function createusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|required|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
            $data['category']=$this->category_model->getcategorydropdown();
            $data[ 'page' ] = 'createuser';
            $data[ 'title' ] = 'Create User';
            $this->load->view( 'template', $data );	
		}
		else
		{
            $name=$this->input->post('name');
            $email=$this->input->post('email');
            $password=$this->input->post('password');
            $accesslevel=$this->input->post('accesslevel');
            $status=$this->input->post('status');
            $socialid=$this->input->post('socialid');
            $logintype=$this->input->post('logintype');
            $json=$this->input->post('json');
//            $category=$this->input->post('category');
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
			if($this->user_model->create($name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json)==0)
			$data['alerterror']="New user could not be created.";
			else
			$data['alertsuccess']="User created Successfully.";
			$data['redirect']="site/viewusers";
			$this->load->view("redirect",$data);
		}
	}
    function viewusers()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewusers';
        $data['base_url'] = site_url("site/viewusersjson");
        
		$data['title']='View Users';
		$this->load->view('template',$data);
	} 
    function viewusersjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`user`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        
        $elements[1]=new stdClass();
        $elements[1]->field="`user`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`user`.`email`";
        $elements[2]->sort="1";
        $elements[2]->header="Email";
        $elements[2]->alias="email";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`user`.`socialid`";
        $elements[3]->sort="1";
        $elements[3]->header="SocialId";
        $elements[3]->alias="socialid";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`logintype`.`name`";
        $elements[4]->sort="1";
        $elements[4]->header="Logintype";
        $elements[4]->alias="logintype";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`user`.`json`";
        $elements[5]->sort="1";
        $elements[5]->header="Json";
        $elements[5]->alias="json";
       
        $elements[6]=new stdClass();
        $elements[6]->field="`accesslevel`.`name`";
        $elements[6]->sort="1";
        $elements[6]->header="Accesslevel";
        $elements[6]->alias="accesslevelname";
       
        $elements[7]=new stdClass();
        $elements[7]->field="`statuses`.`name`";
        $elements[7]->sort="1";
        $elements[7]->header="Status";
        $elements[7]->alias="status";
       
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `user` LEFT OUTER JOIN `logintype` ON `logintype`.`id`=`user`.`logintype` LEFT OUTER JOIN `accesslevel` ON `accesslevel`.`id`=`user`.`accesslevel` LEFT OUTER JOIN `statuses` ON `statuses`.`id`=`user`.`status`");
        
		$this->load->view("json",$data);
	} 
    
    
	function edituser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
		$data['page']='edituser';
		$data['page2']='block/userblock';
		$data['title']='Edit User';
		$this->load->view('template',$data);
	}
	function editusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
			$data['before']=$this->user_model->beforeedit($this->input->post('id'));
			$data['page']='edituser';
//			$data['page2']='block/userblock';
			$data['title']='Edit User';
			$this->load->view('template',$data);
		}
		else
		{
            
            $id=$this->input->get_post('id');
            $name=$this->input->get_post('name');
            $email=$this->input->get_post('email');
            $password=$this->input->get_post('password');
            $accesslevel=$this->input->get_post('accesslevel');
            $status=$this->input->get_post('status');
            $socialid=$this->input->get_post('socialid');
            $logintype=$this->input->get_post('logintype');
            $json=$this->input->get_post('json');
//            $category=$this->input->get_post('category');
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
            if($image=="")
            {
            $image=$this->user_model->getuserimagebyid($id);
               // print_r($image);
                $image=$image->image;
            }
            
			if($this->user_model->edit($id,$name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json)==0)
			$data['alerterror']="User Editing was unsuccesful";
			else
			$data['alertsuccess']="User edited Successfully.";
			
			$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
	
	function deleteuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->deleteuser($this->input->get('id'));
//		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="User Deleted Successfully";
		$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
		$this->load->view("redirect",$data);
	}
	function changeuserstatus()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->changestatus($this->input->get('id'));
		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="Status Changed Successfully";
		$data['redirect']="site/viewusers";
        $data['other']="template=$template";
        $this->load->view("redirect",$data);
	}
    
    
    
    public function viewalbum()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewalbum";
$data["base_url"]=site_url("site/viewalbumjson");
$data["title"]="View album";
$this->load->view("template",$data);
}
function viewalbumjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_album`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`school_album`.`name`";
$elements[1]->sort="1";
$elements[1]->header="Name";
$elements[1]->alias="name";
$elements[2]=new stdClass();
$elements[2]->field="`school_album`.`image`";
$elements[2]->sort="1";
$elements[2]->header="Image";
$elements[2]->alias="image";
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
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_album`");
$this->load->view("json",$data);
}

public function createalbum()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createalbum";
$data["title"]="Create album";
$this->load->view("template",$data);
}
public function createalbumsubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createalbum";
$data["title"]="Create album";
$this->load->view("template",$data);
}
else
{
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$status=$this->input->get_post("status");
if($this->album_model->create($name,$image,$status)==0)
$data["alerterror"]="New album could not be created.";
else
$data["alertsuccess"]="album created Successfully.";
$data["redirect"]="site/viewalbum";
$this->load->view("redirect",$data);
}
}
public function editalbum()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editalbum";
$data["title"]="Edit album";
$data["before"]=$this->album_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editalbumsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editalbum";
$data["title"]="Edit album";
$data["before"]=$this->album_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$status=$this->input->get_post("status");
if($this->album_model->edit($id,$name,$image,$status)==0)
$data["alerterror"]="New album could not be Updated.";
else
$data["alertsuccess"]="album Updated Successfully.";
$data["redirect"]="site/viewalbum";
$this->load->view("redirect",$data);
}
}
public function deletealbum()
{
$access=array("1");
$this->checkaccess($access);
$this->album_model->delete($this->input->get("id"));
$data["redirect"]="site/viewalbum";
$this->load->view("redirect",$data);
}
public function viewalbumimage()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewalbumimage";
$data["base_url"]=site_url("site/viewalbumimagejson");
$data["title"]="View albumimage";
$this->load->view("template",$data);
}
function viewalbumimagejson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_albumimage`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`school_albumimage`.`album`";
$elements[1]->sort="1";
$elements[1]->header="Album";
$elements[1]->alias="album";
$elements[2]=new stdClass();
$elements[2]->field="`school_albumimage`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`school_albumimage`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
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
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_albumimage`");
$this->load->view("json",$data);
}

public function createalbumimage()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createalbumimage";
$data["title"]="Create albumimage";
$this->load->view("template",$data);
}
public function createalbumimagesubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("album","Album","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createalbumimage";
$data["title"]="Create albumimage";
$this->load->view("template",$data);
}
else
{
$album=$this->input->get_post("album");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$status=$this->input->get_post("status");
if($this->albumimage_model->create($album,$name,$image,$status)==0)
$data["alerterror"]="New albumimage could not be created.";
else
$data["alertsuccess"]="albumimage created Successfully.";
$data["redirect"]="site/viewalbumimage";
$this->load->view("redirect",$data);
}
}
public function editalbumimage()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editalbumimage";
$data["title"]="Edit albumimage";
$data["before"]=$this->albumimage_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editalbumimagesubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("album","Album","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editalbumimage";
$data["title"]="Edit albumimage";
$data["before"]=$this->albumimage_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$album=$this->input->get_post("album");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$status=$this->input->get_post("status");
if($this->albumimage_model->edit($id,$album,$name,$image,$status)==0)
$data["alerterror"]="New albumimage could not be Updated.";
else
$data["alertsuccess"]="albumimage Updated Successfully.";
$data["redirect"]="site/viewalbumimage";
$this->load->view("redirect",$data);
}
}
public function deletealbumimage()
{
$access=array("1");
$this->checkaccess($access);
$this->albumimage_model->delete($this->input->get("id"));
$data["redirect"]="site/viewalbumimage";
$this->load->view("redirect",$data);
}
public function viewnews()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewnews";
$data["base_url"]=site_url("site/viewnewsjson");
$data["title"]="View news";
$this->load->view("template",$data);
}
function viewnewsjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_news`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`school_news`.`title`";
$elements[1]->sort="1";
$elements[1]->header="Title";
$elements[1]->alias="title";
$elements[2]=new stdClass();
$elements[2]->field="`school_news`.`description`";
$elements[2]->sort="1";
$elements[2]->header="Description";
$elements[2]->alias="description";
$elements[3]=new stdClass();
$elements[3]->field="`school_news`.`startdate`";
$elements[3]->sort="1";
$elements[3]->header="Start Date";
$elements[3]->alias="startdate";
$elements[4]=new stdClass();
$elements[4]->field="`school_news`.`enddate`";
$elements[4]->sort="1";
$elements[4]->header="End Date";
$elements[4]->alias="enddate";
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
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_news`");
$this->load->view("json",$data);
}

public function createnews()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createnews";
$data["title"]="Create news";
$this->load->view("template",$data);
}
public function createnewssubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("title","Title","trim");
$this->form_validation->set_rules("description","Description","trim");
$this->form_validation->set_rules("startdate","Start Date","trim");
$this->form_validation->set_rules("enddate","End Date","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createnews";
$data["title"]="Create news";
$this->load->view("template",$data);
}
else
{
$title=$this->input->get_post("title");
$description=$this->input->get_post("description");
$startdate=$this->input->get_post("startdate");
$enddate=$this->input->get_post("enddate");
$status=$this->input->get_post("status");
if($this->news_model->create($title,$description,$startdate,$enddate,$status)==0)
$data["alerterror"]="New news could not be created.";
else
$data["alertsuccess"]="news created Successfully.";
$data["redirect"]="site/viewnews";
$this->load->view("redirect",$data);
}
}
public function editnews()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editnews";
$data["title"]="Edit news";
$data["before"]=$this->news_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editnewssubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("title","Title","trim");
$this->form_validation->set_rules("description","Description","trim");
$this->form_validation->set_rules("startdate","Start Date","trim");
$this->form_validation->set_rules("enddate","End Date","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editnews";
$data["title"]="Edit news";
$data["before"]=$this->news_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$title=$this->input->get_post("title");
$description=$this->input->get_post("description");
$startdate=$this->input->get_post("startdate");
$enddate=$this->input->get_post("enddate");
$status=$this->input->get_post("status");
if($this->news_model->edit($id,$title,$description,$startdate,$enddate,$status)==0)
$data["alerterror"]="New news could not be Updated.";
else
$data["alertsuccess"]="news Updated Successfully.";
$data["redirect"]="site/viewnews";
$this->load->view("redirect",$data);
}
}
public function deletenews()
{
$access=array("1");
$this->checkaccess($access);
$this->news_model->delete($this->input->get("id"));
$data["redirect"]="site/viewnews";
$this->load->view("redirect",$data);
}
public function viewcontact()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewcontact";
$data["base_url"]=site_url("site/viewcontactjson");
$data["title"]="View contact";
$this->load->view("template",$data);
}
function viewcontactjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`school_contact`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`school_contact`.`name`";
$elements[1]->sort="1";
$elements[1]->header="Name";
$elements[1]->alias="name";
$elements[2]=new stdClass();
$elements[2]->field="`school_contact`.`email`";
$elements[2]->sort="1";
$elements[2]->header="Email";
$elements[2]->alias="email";
$elements[3]=new stdClass();
$elements[3]->field="`school_contact`.`message`";
$elements[3]->sort="1";
$elements[3]->header="Message";
$elements[3]->alias="message";
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
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `school_contact`");
$this->load->view("json",$data);
}

public function createcontact()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createcontact";
$data["title"]="Create contact";
$this->load->view("template",$data);
}
public function createcontactsubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("email","Email","trim");
$this->form_validation->set_rules("message","Message","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createcontact";
$data["title"]="Create contact";
$this->load->view("template",$data);
}
else
{
$name=$this->input->get_post("name");
$email=$this->input->get_post("email");
$message=$this->input->get_post("message");
$status=$this->input->get_post("status");
if($this->contact_model->create($name,$email,$message,$status)==0)
$data["alerterror"]="New contact could not be created.";
else
$data["alertsuccess"]="contact created Successfully.";
$data["redirect"]="site/viewcontact";
$this->load->view("redirect",$data);
}
}
public function editcontact()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editcontact";
$data["title"]="Edit contact";
$data["before"]=$this->contact_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editcontactsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("email","Email","trim");
$this->form_validation->set_rules("message","Message","trim");
$this->form_validation->set_rules("status","Status","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editcontact";
$data["title"]="Edit contact";
$data["before"]=$this->contact_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
$email=$this->input->get_post("email");
$message=$this->input->get_post("message");
$status=$this->input->get_post("status");
if($this->contact_model->edit($id,$name,$email,$message,$status)==0)
$data["alerterror"]="New contact could not be Updated.";
else
$data["alertsuccess"]="contact Updated Successfully.";
$data["redirect"]="site/viewcontact";
$this->load->view("redirect",$data);
}
}
public function deletecontact()
{
$access=array("1");
$this->checkaccess($access);
$this->contact_model->delete($this->input->get("id"));
$data["redirect"]="site/viewcontact";
$this->load->view("redirect",$data);
}

}
?>
