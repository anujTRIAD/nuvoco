<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Project extends CI_Controller
{
	public function __construct()
	{
		parent::__construct(); 
	}

	public function index(){
		loadpage('project/index');
	}

	public function view(){
		loadpage('project/view');
	}

	public function list(){
		loadpage('project/list');
	}

	public function create(){
		loadpage('project/create');
	}
}
