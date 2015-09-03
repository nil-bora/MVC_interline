<?php if(!defined('LIB_DIR')) exit('Access Forbidden'); 
	
	class AjaxController extends Controller
	{
		var $common;

		public function __construct()
		{
		    parent::__construct();
		}
		
		public function getProductInstructions()
		{
			if($this->request->post("id"))
			{
				$id = (int)$this->request->post("id");
				$products = $this->model->products()->where('catalog=',$id)->getByActive(1);
				if($products)
			        	jsonout(array("ok"=>1, "products"=>$products));
			}
		}
		
		public function addActionProducts()
		{
			if(isset($_POST['id1']) && isset($_POST['id2'])  && isset($_POST['id3']) &&
				isset($_POST['price1']) && isset($_POST['price2']) && isset($_POST['price3']))
			{
				$data[$_POST['id1']] = $_POST['price1'];
				$data[$_POST['id2']] = $_POST['price2'];
				$data[$_POST['id3']] = $_POST['price3'];

				if(sizeof($data) == 3)
				{
					foreach($data as $id=>$price)
					{
						if($this->session->get("products"))
						{
							
							$products = $this->session->get("products");
							$flag = false;
							foreach($products as $key=>$item)
							{
								if($item['id'] == $id)
								{
									exit;
									$products[$key]['count']++;
									$flag=true;
								}
							}
							if(!$flag)
							{
								$products[] = array("id"=>$id, "count"=>1, 'priceAction'=>$price);
							}
						}
						else {
							$products[] = array("id"=>$id, "count"=>1, 'priceAction'=>$price);
							
						}
						
						$this->session->set("products", $products);
						
					}
					$data = $this->checkBasket();
					if($data)
			        	jsonout(array("ok"=>1, "price"=>$data['price'], "count"=>$data['count']));
					
					
				}
			}
		}
		
		public function productListPaginator()
		{
			$first = $this->request->post("first");
			$filter = $this->request->post("filter");
			$patch = $this->request->post("patch");
			$patchArray = explode("/",$patch);
			foreach($patchArray as $key=>$item)
				if(empty($item))
					unset($patchArray[$key]);

			if($filter)
			{
				$filter = substr($filter, 1, strlen($filter));
			
				$filterArray = explode("&", $filter);
				$filters = array();
				$tmp = array();
				$tmp2 = array();
				foreach($filterArray as $key=>$item)
				if(empty($item))
					unset($filterArray[$key]);
				foreach($filterArray as $one)
				{
					$item = explode("=", $one);
					
					array_push($tmp, $item[0]);//array($item[0]);
					array_push($tmp2, $item[1]);
					//$tmp2[] = array($item[1]);
					//compact("event", "nothing_here", $location_vars);
					//array_push($filters, $tmp);
					
				}
				$filters = array_combine($tmp, $tmp2);
			}else
				$filters= false;
			


			$group = $patchArray[2];
			$subgroup = (isset($patchArray[3]) ? $patchArray[3] : false);
			$isAjax = true;
			$first = $first + 9;
			$html =  $this->module->catalog_list($group, $subgroup, $first, $filters, $isAjax);
			jsonout(array("ok"=>1, "html"=>$html, "first"=>$first));
			
			exit;
		}

		public function addToCart($id)
		{
			//$this->session->delete("products");
			//exit;
			if($id)
			{
				if($this->session->get("products"))
				{
					
					$products = $this->session->get("products");
					$flag = false;
					foreach($products as $key=>$item)
					{
						if($item['id'] == $id)
						{
							$products[$key]['count']++;
							$flag=true;
						}
					}
					if(!$flag)
					{
						$products[] = array("id"=>$id, "count"=>1);
					}
				}
				else {
					$products[] = array("id"=>$id, "count"=>1);
					
				}
				
				$this->session->set("products", $products);
				
				return $products;
			}
			//printr($this->session->get("products"));
		}
		
		public function addItem()
		{
			if($this->request->post("id"))
			{
				$id = (int)$this->request->post("id");
				$this->addToCart($id);
				$data = $this->checkBasket();
				$countWord = $this->module->getNumEnding($data['count'], array("товар", "товара", "товаров"));
				if($data)
			        jsonout(array("ok"=>1, "price"=>$data['price'], "count"=>$data['count'], "countWord"=>$countWord));
			}
		}
		
		public function checkBasket($course=false)
		{
		    if($this->session->get("products"))
			 {
			 	 if(!$course)
				 	 $course = $this->database->query("SELECT name_rus FROM course WHERE active='1' ORDER BY pos LIMIT 1")->one();
				
				 $basket = $this->session->get("products");
				
				 $data['price'] = 0;
				 $data['count'] = 0;
				 
				
				 if(is_array($basket))
				 {
					 foreach($basket as $one){
						 $products = $this->model->products()->where("active=",1)->where("id=",$one['id'])->and_where('price>',0)->getOne(); 
						 $data['price']+= preg_replace('~[^.0-9]+~', '', $products['price'])*$one['count']*$course;
						 $data['count']+=$one['count'];
					 }
					 return $data;
				 }
			 }
		}
		
	}