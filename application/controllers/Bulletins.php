<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bulletins extends CI_Controller {
	
	private $BASE_PATH_IMAGES = 'assets/images/bulletins/';
	
	public function index() 
	{
		// Check organization login
		if(!$this->session->userdata('org_id') || !$this->session->userdata('org_name')){
			redirect('organizations/index');
		}

		$data['title'] = 'Bulletins';
		$data['bulletins'] = $this->bulletins_model->getAll($this->session->userdata('org_id'));
		$this->load->view('templates/header');
		$this->load->view('bulletins/index', $data);
		$this->load->view('templates/footer');		
	}

	public function create() 
	{
		$data['title'] = 'New bulletin';
		
		$this->load->view('templates/header');
		$this->load->view('bulletins/create', $data);
		$this->load->view('templates/footer');
	}

	public function entry($bul_id = NULL) 
	{
		if (!$bul_id || !is_numeric($bul_id)) {
			show_404();
		}
		
		$data['bulletin'] = $this->bulletins_model->getBulletin($bul_id);
		if (!$data['bulletin']) {
			show_404();
		}

		$this->session->set_userdata('bul_id', $data['bulletin']->id);
		$this->session->set_userdata('bul_title', $data['bulletin']->title);
		
		redirect('sections/index');
	}

	public function exit_bul() 
	{
		$this->session->unset_userdata('bul_id');
		$this->session->unset_userdata('bul_title');
		$this->index();
	}

	public function publish($bul_id = null) 
	{
		if (!$bul_id || !is_numeric($bul_id)) {
			show_404();
		}
		
		$data['bulletin'] = $this->bulletins_model->getBulletin($bul_id);
		if (!$data['bulletin']) {
			show_404();
		}

		$data['bulletin']->published = !($data['bulletin']->published);
		$this->bulletins_model->save($data['bulletin']);				
		
		redirect('bulletins/index');
	}

	public function save() 
	{
		$this->form_validation->set_rules('title', 'Bulletin title', 'required|min_length[3]');
		if ($this->form_validation->run() === FALSE) {
			$this->create();
		} else {
			// Upload Image
			$config['upload_path'] = './' . $this->BASE_PATH_IMAGES;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '1024';
			$config['max_width'] = '2000';
			$config['max_height'] = '2000';
			$this->load->library('upload', $config);
			if($this->upload->do_upload('image')){				
				$data = array('upload_data' => $this->upload->data());
				$image_path = base_url() . $this->BASE_PATH_IMAGES . $data['upload_data']['file_name'];
				$this->bulletins_model->save($this->getBulletinFromInput($image_path));				
			} else {
				$image_path = base_url() . $this->BASE_PATH_IMAGES . 'noimage.png';
				$this->bulletins_model->save($this->getBulletinFromInput($image_path));				
			}

			// Update last_update field of organization
			$organization = $this->organizations_model->getOrganization(
				$this->session->userdata('org_id'));
			if (!$organization) {
				show_404();
			}
			
			$organization->last_updated = (new DateTime)->format('Y-m-d H:i:s');
			$this->organizations_model->save($organization);					
			redirect('bulletins/index');			
		}
	}

	private function getBulletinFromInput($image_path) {
		$bulletin = new stdClass();
		$bulletin->id = null;
		$bulletin->title = $this->input->post('title');
		$bulletin->created_at = (new DateTime)->format('Y-m-d H:i:s');
		$bulletin->organization_id = $this->session->userdata('org_id');
		$bulletin->image = $image_path;
		return $bulletin;
	}	
}
