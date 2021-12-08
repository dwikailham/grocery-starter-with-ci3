<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examples extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->model('Model_rutin');
		$this->load->model('M_example');

		$this->load->library('grocery_CRUD');
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',(array)$output);
	}

	public function offices()
	{
		$output = $this->grocery_crud->render();

		$this->_example_output($output);
	}

	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	public function offices_management()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('offices');
			$crud->set_subject('Office');
			$crud->required_fields('city');
			$crud->columns('city','country','phone','addressLine1','postalCode');

			$output = $crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function employees_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('employees');
			$crud->set_relation('officeCode','offices','city');
			$crud->display_as('officeCode','Office City');
			$crud->set_subject('Employee');

			$crud->required_fields('lastName');

			$crud->set_field_upload('file_url','assets/uploads/files');

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function customers_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_table('customers');
			$crud->columns('customerName','contactLastName','phone','city','country','salesRepEmployeeNumber','creditLimit');
			$crud->display_as('salesRepEmployeeNumber','from Employeer');
			$crud->display_as('customerName','Name');
			$crud->display_as('contactLastName','Last Name');
			$crud->set_subject('Customer');
			$crud->set_relation('salesRepEmployeeNumber','employees','lastName');

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function orders_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_relation('customerNumber','customers','{contactLastName} {contactFirstName}');
			$crud->display_as('customerNumber','Customer');
			$crud->set_table('orders');
			$crud->set_subject('Order');
			$crud->unset_add();
			$crud->unset_delete();

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function products_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_table('products');
			$crud->set_subject('Product');
			$crud->unset_columns('productDescription');
			$crud->callback_column('buyPrice',array($this,'valueToEuro'));

			$output = $crud->render();

			$this->_example_output($output);
	}

	public function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}

	public function film_management()
	{
		$crud = new grocery_CRUD();

		$crud->set_table('film');
		$crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
		$crud->set_relation_n_n('category', 'film_category', 'category', 'film_id', 'category_id', 'name');
		$crud->unset_columns('special_features','description','actors');

		$crud->fields('title', 'description', 'actors' ,  'category' ,'release_year', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features');

		$output = $crud->render();

		$this->_example_output($output);
	}

	public function film_management_twitter_bootstrap()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('twitter-bootstrap');
			$crud->set_table('film');
			$crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
			$crud->set_relation_n_n('category', 'film_category', 'category', 'film_id', 'category_id', 'name');
			$crud->unset_columns('special_features','description','actors');

			$crud->fields('title', 'description', 'actors' ,  'category' ,'release_year', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features');

			$output = $crud->render();
			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function multigrids()
	{
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);

		$output1 = $this->offices_management2();

		$output2 = $this->employees_management2();

		$output3 = $this->customers_management2();

		$js_files = $output1->js_files + $output2->js_files + $output3->js_files;
		$css_files = $output1->css_files + $output2->css_files + $output3->css_files;
		$output = "<h1>List 1</h1>".$output1->output."<h1>List 2</h1>".$output2->output."<h1>List 3</h1>".$output3->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

	public function offices_management2()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('offices');
		$crud->set_subject('Office');

		$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/multigrids")));

		$output = $crud->render();

		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}

	public function employees_management2()
	{
		$crud = new grocery_CRUD();

		$crud->set_theme('datatables');
		$crud->set_table('employees');
		$crud->set_relation('officeCode','offices','city');
		$crud->display_as('officeCode','Office City');
		$crud->set_subject('Employee');

		$crud->required_fields('lastName');

		$crud->set_field_upload('file_url','assets/uploads/files');

		$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/multigrids")));

		$output = $crud->render();

		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}

	public function customers_management2()
	{
		$crud = new grocery_CRUD();

		$crud->set_table('customers');
		$crud->columns('customerName','contactLastName','phone','city','country','salesRepEmployeeNumber','creditLimit');
		$crud->display_as('salesRepEmployeeNumber','from Employeer');
		$crud->display_as('customerName','Name');
		$crud->display_as('contactLastName','Last Name');
		$crud->set_subject('Customer');
		$crud->set_relation('salesRepEmployeeNumber','employees','lastName');

		$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/multigrids")));

		$output = $crud->render();

		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}

	public function guru_management()
	{
		$crud = new Grocery_CRUD();

		$crud->set_table('guru');
		$crud->columns('nip_guru','nama_guru','alamat_guru','agama_guru','no_telp_guru','jk_guru','tmp_lahir_guru', 'tgl_lahir_guru');
		$crud->display_as('nip_guru','NIP');
		$crud->display_as('nama_guru','Nama');
		$crud->display_as('alamat_guru','Alamat');
		$crud->display_as('no_telp_guru','Nomor Telpom');
		$crud->display_as('jk_guru','Jenis Kelamin');
		$crud->display_as('tmp_lahir_guru','Tempat Lahir');
		$crud->display_as('tgl_lahir_guru','Tanggal Lahir');
		$crud->set_subject('Guru');

		$crud->callback_add_field('id_guru', array($this, 'c_id_guru'));
		$crud->callback_edit_field('id_guru', array($this, 'c_edit_id_guru'));

		$output = $crud->render();

		$this->_example_output($output);

	}

	public function c_id_guru($primary_key, $row){
		$id = $this->Model_rutin->inc_id('guru', 'id_guru', '');

		return "<input type='hidden' id='id_guru' name='id_guru' value='.$id.'>$id";
	}

	public function c_edit_id_guru($value, $row){
		return "<input type='hidden' id='id_guru' name='id_guru' value='.$value.'>$value";
	}

	public function kelas_management()
	{
		$crud = new Grocery_CRUD();

		$crud->set_table('kelas');
		$crud->columns('nama_kelas', 'jenis_kelas','tahun_ajaran', 'semester');
		$crud->display_as('nama_kelas', 'Nama Kelas');
		$crud->display_as('jenis_kelas', 'Jenis Kelas');
		$crud->display_as('tahun_ajaran', 'Tahun Ajaran');

		$crud->set_subject('Kelas');

		$crud->callback_add_field('id_kelas', array($this, 'c_id_kelas'));
		$crud->callback_edit_field('id_kelas', array($this, 'c_edit_id_kelas'));

		$output = $crud->render();

		$this->_example_output($output);

	}

	public function c_id_kelas($primary_key, $row){
		$id = $this->Model_rutin->inc_id('kelas', 'id_kelas', '');

		return "<input type='hidden' id='id_kelas' name='id_kelas' value='.$id.'>$id";
	}

	public function c_edit_id_kelas($value, $row){
		return "<input type='hidden' id='id_kelas' name='id_kelas' value='.$value.'>$value";
	}

	public function mapel_management()
	{
		$crud = new Grocery_CRUD();

		$crud->set_table('mapel');
		$crud->columns('nama_mapel', 'sub_mapel', 'singkatan_mapel', 'kkm' , 'sedang');
		$crud->display_as('nama_mapel', 'Mata Pelajaran');
		$crud->display_as('sub_mapel', 'Sub Mapel');
		$crud->display_as('singkatan_mapel', 'Singkatan');

		$crud->set_subject('Mata Pelajaran');

		$crud->callback_add_field('id_mapel', array($this, 'c_id_mapel'));
		$crud->callback_edit_field('id_mapel', array($this, 'c_edit_id_mapel'));

		

		$output = $crud->render();

		$this->_example_output($output);

	}

	public function c_id_mapel($primary_key, $row){
		$id = $this->Model_rutin->inc_id('mapel', 'id_mapel', '');

		return "<input type='hidden' id='id_mapel' name='id_mapel' value='.$id.' >$id";
	}

	public function c_edit_id_mapel($value, $row){
		return "<input type='hidden' id='id_mapel' name='id_mapel' value='.$value.'>$value";
	}

	public function mengajar_management()
	{
		$crud = new Grocery_CRUD();

		$crud->set_table('mengajar');
		$crud->display_as('tahun_ajaran', 'Tahun Ajaran');
		$crud->set_subject('Menagajar');

		//CALLBACK ADD MENGAJAR
		$crud->callback_add_field('id_mengajar', array($this, 'c_id_mengajar'));
		$crud->callback_add_field('id_mapel', array($this, 'c_col_id_mapel'));
		$crud->callback_add_field('id_guru', array($this, 'c_col_id_guru'));
		$crud->callback_add_field('id_kelas', array($this, 'c_col_id_kelas'));
		
		//CALLBACK EDIT MENGAJAR
		$crud->callback_edit_field('id_mengajar', array($this, 'c_edit_id_mengajar'));
		$crud->callback_edit_field('id_mapel', array($this, 'c_col_id_mapel2'));
		$crud->callback_edit_field('id_guru', array($this, 'c_col_id_guru2'));
		$crud->callback_edit_field('id_kelas', array($this, 'c_col_id_kelas2'));

		//CALLBACK COLUMN MENGAJAR
		$crud->callback_column('id_mapel', array($this, 'col_mapel'));
		$crud->callback_column('id_guru', array($this, 'col_guru'));
		$crud->callback_column('id_kelas', array($this, 'col_kelas'));


		$output = $crud->render();

		$this->_example_output($output);
	}

	public function c_col_id_mapel2($value, $row){
		$id = $this->M_example->col_mapel();
		$dropdown = "<select class='form-control' id='id_mapel' name='id_mapel'>
        <option value=''></option>
       	";
		foreach($id->result() as $i){
			if ($value == $i->id_mapel) {
                $dropdown = $dropdown . "<option value='".$i->id_mapel."' selected>".$i->nama_mapel."</option>";
            } else {
				$dropdown = $dropdown . "<option value='".$i->id_mapel."'>".$i->nama_mapel."</option>";
            }
		}
		$dropdown = $dropdown . "</select>";

		return $dropdown;
	}

	public function c_col_id_guru2($value, $row){
		$id = $this->M_example->col_guru();
		$dropdown = "<select class='form-control' id='id_guru' name='id_guru'>
        <option value=''></option>
       	";
		foreach($id->result() as $i){
			if ($value == $i->id_guru) {
                $dropdown = $dropdown . "<option value='".$i->id_guru."' selected>".$i->nama_guru."</option>";
            } else {
				$dropdown = $dropdown . "<option value='".$i->id_guru."'>".$i->nama_guru."</option>";
            }
		}
		$dropdown = $dropdown . "</select>";

		return $dropdown;
	}

	public function c_col_id_kelas2($value, $row){
		$id = $this->M_example->col_kelas();
		$dropdown = "<select class='form-control' id='id_kelas' name='id_kelas'>
        <option value=''></option>
       	";
		foreach($id->result() as $i){
			if ($value == $i->id_kelas) {
                $dropdown = $dropdown . "<option value='".$i->id_kelas."' selected>".$i->nama_kelas."</option>";
            } else {
				$dropdown = $dropdown . "<option value='".$i->id_kelas."'>".$i->nama_kelas."</option>";
            }
		}
		$dropdown = $dropdown . "</select>";

		return $dropdown;
	}

	public function col_mapel($value, $row){
		$id= $this->M_example->cb_mapel($row->id_mapel);

		return "<input type='hidden' id='id_mapel' name='id_mapel' value='.$id.' >$id";
	}

	public function col_guru($value, $row){
		$id= $this->M_example->cb_guru($row->id_guru);

		return "<input type='hidden' id='id_guru' name='id_guru' value='.$id.' >$id";
	}

	public function col_kelas($value, $row){
		$id= $this->M_example->cb_kelas($row->id_kelas);

		return "<input type='hidden' id='id_kelas' name='id_kelas' value='.$id.' >$id";
	}

	public function c_col_id_mapel($primary_key, $row){
		$id = $this->M_example->col_mapel();
		$dropdown = "<select class='form-control' id='id_mapel' name='id_mapel'>
        <option value=''></option>
       	";
		foreach($id->result() as $i){
			$dropdown = $dropdown . "<option value='" . $i->id_mapel . "'>" . $i->nama_mapel . "</option>";
		}
		$dropdown = $dropdown . "</select>";

		return $dropdown;
	}

	public function c_col_id_guru($primary_key, $row){
		$id = $this->M_example->col_guru();
		$dropdown = "<select class='form-control' id='id_guru' name='id_guru'>
					<option value=''></option>";
		foreach($id->result() as $i){
			$dropdown = $dropdown . "<option value='" .$i->id_guru."'>".$i->nama_guru."</option>";
		}
		$dropdown = $dropdown . "</select>";

		return $dropdown;
	}

	public function c_col_id_kelas($primary_key, $row){
		$id = $this->M_example->col_kelas();
		$dropdown = "<select class='form-control' id='id_kelas' name='id_kelas'>
					<option value=''></option>";

		foreach($id->result() as $i){
			$dropdown = $dropdown . "<option value='" .$i->id_kelas."'>".$i->nama_kelas."</option>";
		}
		$dropdown = $dropdown . "</select>";

		return $dropdown;
	}


	public function c_id_mengajar($primary_key, $row){
		$id = $this->Model_rutin->inc_id('mengajar', 'id_mengajar', '');

		return "<input type='hidden' id='id_mengajar' name='id_mengajar' value='.$id.' >$id";
	}

	public function c_edit_id_mengajar($value, $row){
		return "<input type='hidden' id='id_mengajar' name='id_mengajar' value='.$value.' >$value";
	}

}
