<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organizations extends CI_Controller {

	private $BASE_PATH_IMAGES = 'assets/images/organizations/';
	
	public function index() 
	{
		$data['title'] = 'My organizations';
		$data['organizations'] = $this->organizations_model->getAll();
		$this->load->view('templates/header');
		$this->load->view('organizations/index', $data);
		$this->load->view('templates/footer');
	}

	public function create() 
	{
		$data['title'] = 'New organization';
		$this->load->view('templates/header');
		$this->load->view('organizations/create', $data);
		$this->load->view('templates/footer');
	}

	public function entry($org_id = NULL) 
	{
		if (!$org_id || !is_numeric($org_id)) {
			show_404();
		}
		
		$data['organization'] = $this->organizations_model->getOrganization($org_id);
		if (!$data['organization']) {
			show_404();
		}

		$this->session->set_userdata('org_id', $data['organization']->id);
		$this->session->set_userdata('org_name', $data['organization']->name);
		
		redirect('bulletins/index');
	}

	public function exit_org()
	{
		$this->session->unset_userdata('org_id');
		$this->session->unset_userdata('org_name');
		$this->index();
	}

	public function save() 
	{
		$this->form_validation->set_rules('name', 'Organization name', 'required|min_length[3]');
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
				$this->organizations_model->save($this->getOrganizationFromInput($image_path));
			} else {
				$image_path = base_url() . $this->BASE_PATH_IMAGES . 'noimage.png';
				$this->organizations_model->save($this->getOrganizationFromInput($image_path));				
			}
			redirect('organizations/index');
		}
	}

	private function getOrganizationFromInput($image_path) {
		$organization = new stdClass();
		$organization->id = null;
		$organization->name = $this->input->post('name');
		$organization->description = $this->input->post('description');
		$organization->last_updated = (new DateTime)->format('Y-m-d H:i:s');
		$organization->image = $image_path;
		return $organization;
	}

	
}
