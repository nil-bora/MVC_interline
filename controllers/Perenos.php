<?php if(!defined('LIB_DIR')) exit('Access Forbidden'); 
	
	class PerenosController extends Controller
	{
		var $common;

		public function __construct()
		{
		    parent::__construct();
		}
			
		public function propertyProd()
		{
			/*
$property_old = $this->database->query("SELECT * FROM fend_mod_property_test")->resultArray();
			foreach($property_old as $one)
			{
				$this->database->update('property', array('property_id'=>$one['property_id']), array('name_rus'=>$one['property_name']));
			}
*/			
			$property_old = $this->database->query("SELECT * FROM fend_mod_property_product_test")->resultArray();
			$array = array();
			foreach($property_old as $one)
			{
				$idProd = $this->database->query("SELECT id FROM products WHERE product_id = ".$one['product_id'])->one();
				$idProperty = $this->database->query("SELECT id FROM property WHERE property_id = ".$one['property_id'])->one();
				$array[] = array(
									'name'=>$idProperty,
									'active'=>1,
									'table_id'=>52,
									'column'=>'propertys',
									'page_id'=>$idProd,
									'value'=>$one['property_value']);
				//printr($idProd);
				/*
$this->database->insert('property_values', array(
									'name'=>$idProperty,
									'active'=>1,
									'table_id'=>52,
									'column'=>'propertys',
									'page_id'=>$idProd,
									'value'=>$one['property_value']));
*/
			}
			
			/*
$this->database->insert('property_values', array('name' => 17,
												            'active' => 1,
												            'table_id' => 52,
												            'column' => 'propertys',
												            'page_id' => 1,
												            'value' => 'Металл'));
*/
			//printr($array);
			//printr($property_old);
			/*
$this->database->insert('property_values', array(
									'name'=>,
									'active'=>1,
									'table_id'=>52,
									'column'=>'propertys',
									'page_id'=>$idProd,
									'value'=>''
			
			));
*/
			//printr($property_old);
		}
		
	}