<?php if(!defined('LIB_DIR')) exit('Access Forbidden'); 

	class interlineModule extends Modules
	{
		var $is_public=true;

		var $vars;
		var $common;
		var $course;
		var $breadcrumb;
		var $subcategory;
		var $product;
		var $href;
		function __construct()
		{	
			parent::__construct(__FILE__);
			$course = $this->database->query("SELECT name_rus FROM course WHERE active='1' ORDER BY pos LIMIT 1")->one();
			$this->course = (float)$course;
		}
		
		
		
		public function config()
		{
			$this->common=Array(
				"param"=>Array("param name", $this->getModelsList())
			);

			$this->vars_info=Array(
				"param"=>Array("param name", Array("custom_func"), $this->getPagesList()),
			);

			$this->func_info=Array(
				"import_confirm"=>"Подтверждения импорта",
				"contacts"=>"Контакты",
				"services"=>"Сервисы",
				"catalog_menu"=>"Меню каталога",
				"catalog_list"=>"Каталог лист",
				"index"=>"Главная страница",
				"one_product"=>"Один продукт",
				"search"=>"Поиск",
				"action_list"=>"Список акций",
				"one_action"=>"Одна акция",
				"breadcrumbs"=>"Хлебные крошки",
				"mainmenu"=>"Главное меню",
				"archive"=>"Архив каталогов",
				"serch_instruction"=>"Поиск инструкций",
				"footer"=>"Footer"
			);
		}
		
		public function footer()
		{
			$data=new Dwoo_Data();

			$catalog = $this->model->archive()->order("idate DESC")->limit(1)->getOne();
			
			$data->assign("catalog", $catalog);
			return $this->dwoo->get("footer.php", $data);
		}
		
		public function serch_instruction()
		{
			$data=new Dwoo_Data();

			$catalog = $this->model->catalog()->order("tree_path ASC")->getByActive(1);
			$documents = array();
			$flag = false;
			if($this->request->post("product_search"))
			{
				$id = $this->request->post("product_search");
				$product = $this->model->products()->linked(true)->where('id=',$id)->and_where('active=',1)->getOne();
				if(isset($product['documents']) && !empty($product['documents']))
				{
					foreach($product['documents'] as $one)
						$documents[] = $one;
				}
				$flag = true;
				
				$data->assign("product", $product);
				$data->assign("documents", $documents);
			}
			
			$data->assign("post", $flag);
			$data->assign("catalog", $catalog);
			return $this->dwoo->get("serch_instruction.php", $data);
		}
		
		public function archive()
		{
			$data=new Dwoo_Data();
			$archive = $this->model->archive()->order("pos ASC")->getByActive(1);
			
			$data->assign("archive", $archive);
			return $this->dwoo->get("archive.php", $data);
		}
		public function mainmenu()
		{
			$data=new Dwoo_Data();
			$mainmenu = $this->model->page()->where("inmenu=",1)->and_where("tree_id=",1)->get();

			$cart = $this->checkCart();
			$data->assign("cart", $cart);
			$data->assign("mainmenu", $mainmenu);
			
			return $this->dwoo->get("_mainmenu.php", $data);
		}
		
		public function index()
		{
			$data=new Dwoo_Data();
			
			$catalog = $this->model->catalog()->where("image!=", "")->and_where("tree_id=",0)->getByActive(1);
			$main_slider = $this->model->slider()->order("pos ASC")->getByActive(1);
			$slide_bottom = $this->model->slider_bottom()->order("pos ASC")->getByActive(1);
			$action = $this->model->action()->where("active=",1)->and_where("in_main=",1)->order("pos DESC")->getOne();
			
			//$action = $this->getActionImage($action);
			
			$data->assign("action", $action);
			$data->assign("slide_bottom", $slide_bottom);
			$data->assign("main_slider", $main_slider);
			$data->assign("catalog", $catalog);
			return $this->dwoo->get("index.php", $data);
		}
		
		public function catalog_menu()
		{
			$data=new Dwoo_Data();
			$catalog = $this->model->catalog()->where("tree_id=",0)->getByActive(1);
			$subgroup = false;
			$href = false;
			if(isset($this->router->vars["subgroup"]))
			{
				$subgroup = $this->router->vars["subgroup"];
			}
			if(isset($this->router->vars["href"]))
			{
				$href = $this->href;
			}
			$data->assign("href", $href);
			$data->assign("subgroup", $subgroup);
			$data->assign("catalog", $catalog);
			return $this->dwoo->get("catalog_menu.php", $data);
		}
		
		public function catalog_list($group=false, $subgroup=false, $first=false, $filters=false, $isAjax=false)
		{
		
			$data=new Dwoo_Data();
			if(!empty($filters) && is_array($filters))
			{
				foreach($filters as $k=>$v)
				{
					$_REQUEST[$k] = $v;
				}
			}

			$last = 9;
			if(!empty($first))
				$first = $first;
			else
				$first = 0;

			if(!empty($group))
				$this->router->vars["group"] = $group;
			if(!empty($subgroup))
				$this->router->vars["subgroup"] = $subgroup;
			
			if(isset($this->router->vars["group"]))
			{
				if(isset($this->router->vars["subgroup"]))
					$group = $this->router->vars["subgroup"];
				else 
					$group = $this->router->vars["group"];
				$catalog = $this->model->catalog()->where("active=", 1)->treeChildrenOnlyByHref($group);
				
				$catalogActive = $this->model->catalog()->where("active=", 1)->where("href=",$group)->getOne();
				
				
				
				$this->breadcrumb['menuname'] = $catalogActive['name'];
				$this->breadcrumb['href'] = "category/".$catalogActive['href'];
				
				
				$data->assign("catalogActive", $catalogActive);
				
				if($catalog)
				{
					
					/* START SEO */
					$for_seo = array();
					if(!empty($catalogActive['cms_seo_title']))
					$for_seo['cms_seo_title'] = $catalogActive['cms_seo_title'];
					if(!empty($catalogActive['cms_seo_description']))
						$for_seo['cms_seo_description'] = $catalogActive['cms_seo_description'];
					if(!empty($catalogActive['cms_seo_keywords']))
						$for_seo['cms_seo_keywords'] = $catalogActive['cms_seo_keywords'];
					
					$for_seo['name'] = $catalogActive['name'];
					if(!$isAjax)
						$this->modules->page->parse_seo($for_seo);
					/* END SEO */		
					
					$data->assign("group", $group);
					$data->assign("catalog", $catalog);
					return $this->dwoo->get("catalog_list.php", $data);
				} else {
					
					
					$idCartProd = $this->getIdCartProd();
					$data->assign("idCartProd", $idCartProd);					
					
					$select_catalog = $this->model->catalog()->where("href=", $group)->getOne();
					
					$treeNode = $this->model->catalog()->treeNodeRootByHref($group);
					
					if($treeNode['id']!=$catalogActive['id'])
					{
						$this->subcategory['menuname'] = $treeNode['name'];
						$this->subcategory['href'] = "category/".$treeNode['href'];
					}

					
					/* START SEO */
					$for_seo = array();
					if(!empty($catalogActive['cms_seo_title']))
					$for_seo['cms_seo_title'] = $catalogActive['cms_seo_title'];
					if(!empty($catalogActive['cms_seo_description']))
						$for_seo['cms_seo_description'] = $catalogActive['cms_seo_description'];
					if(!empty($catalogActive['cms_seo_keywords']))
						$for_seo['cms_seo_keywords'] = $catalogActive['cms_seo_keywords'];
					
					$for_seo['name'] = $catalogActive['name'];
					$for_seo['catalog'] = $catalogActive['accusative_name'];
					if(!$isAjax)
						$this->modules->page->parse_seo($for_seo);
					/* END SEO */
					

					
					
					$min = $this->database->query("SELECT MIN(price) FROM products WHERE active='1' AND price>0 AND catalog=".$select_catalog['id'])->one();
					$max = $this->database->query("SELECT MAX(price) FROM products WHERE active='1' AND catalog=".$select_catalog['id'])->one();
					
					$min_price = $min;
					$max_price = $max;
					if(isset($_REQUEST['min_price']) && isset($_REQUEST['max_price']))
					{
						
						$min_price = (int)$_REQUEST['min_price'];
						$max_price = (int)$_REQUEST['max_price'];
						if($max_price < $min_price)
							$max_price = $min_price;
						
						$min_price = floor($min_price/$this->course);
						$max_price = floor($max_price/$this->course);
					}
					

					$productsFilter = $this->model->products()->linked(true)->where("catalog=",$select_catalog['id'])
								->and_where("active=",1)
								->and_where('price>=', $min_price)
								->and_where('price<=', $max_price)->order("price ASC")->get();

					$idFilter = array();
					$where_arr = array();

					$fields = array_keys($this->database->query("SELECT sys_name FROM property WHERE active='1'")->resultArray("sys_name"));
				
					if(isset($_REQUEST))
					{
						$where_arr = $_REQUEST;
						foreach($where_arr as $k=>$v)
						{
							if(!in_array($k, $fields))
								unset($where_arr[$k]);
						}
						
					}
					
					if(is_array($where_arr) && !empty($where_arr))
						$flag = true;
					else $flag = false;
					
					$prop_value = $this->model->property_values()->joined(true)->where("page_id", array_keys($productsFilter))->get();
					$prop_names = $this->model->property()->where("active=",1)->and_where("in_filter=",1)->order("pos ASC")->get();
					
					$mas_filter = array();

					foreach($prop_names as $key=>$value)
					{
						foreach($prop_value as $k=>$v)
						{
							
							if(!empty($v['value']) && $v['value']!='xx' && $v['name']['in_filter']=='1' && $v['name']['name']==$value['name']) 
							{
								$prop_names[$key]['values'][] = $v['value'];
							}
								
							
						}
					}
					
					foreach($prop_names as $key=>$value)
					{
						if(isset($value['values']) && is_array($value['values']))
						{
							$prop_names[$key]['values'] = array_unique($value['values']);	
						}
						else
							unset($prop_names[$key]);
					}
					
					
					
					foreach($where_arr as $k=>$v)
						if(empty($v))
							unset($where_arr[$k]);

					
					$idPrFilter = array();
					foreach($productsFilter as $one)
					{
						$idPrFilter[] = $one['id'];
					}

					$selectProp = array();
					$idSelectProd = array();
					$idGood = array();

					if(isset($where_arr) && !empty($where_arr))
					{
						foreach($prop_names as $one)
						{
							if(in_array($one['sys_name'], array_keys($where_arr)))
							{
								$tmps = $this->model->property_values()->where("value=", urldecode($where_arr[$one['sys_name']]))->and_where("name=",$one['id'])->getByActive(1);
								//printr($where_arr[$one['sys_name']]);
								if(!empty($tmps))
									$selectProp = array_merge($selectProp, $tmps);
							}
						}
						if(!empty($selectProp))
						{
							foreach($selectProp as $one)
							{
								$idSelectProd[] = $one['page_id'];
							}
							$idSelectProd = array_count_values($idSelectProd);
							foreach($idSelectProd as $key=>$item)
							{
								if($item != sizeof($where_arr))
									unset($idSelectProd[$key]);
							}
							$idSelectProd = array_keys($idSelectProd);
							
							foreach($selectProp as $one)
							{
								if(in_array($one['page_id'], $idPrFilter))
									$idGood[] = $one['page_id'];
							}
						}

						
					}
					

					if($flag)
					{
						$products = $this->model->products()->linked(true)
									->where("catalog=",$select_catalog['id'])
									->and_where("active=",1)
									->and_where('price>=', $min_price)
									->and_where(array('id'=>$idSelectProd))
									->and_where('price<=', $max_price)->limit($first, $last)->order("price ASC")->get();
						
						$allProd = $this->model->products()->select("COUNT(id) as count")
									->where("catalog=",$select_catalog['id'])
									->and_where("active=",1)
									->and_where('price>=', $min_price)
									->and_where(array('id'=>$idSelectProd))
									->and_where('price<=', $max_price)->getOne();
					}
					else {
						$products = $this->model->products()->linked(true)
									->where("catalog=",$select_catalog['id'])
									->and_where("active=",1)
									->and_where('price>=', $min_price)
									->and_where('price<=', $max_price)->limit($first, $last)->order("price ASC")->get();
						
						$allProd = $this->model->products()->select("COUNT(id) as count")
									->where("catalog=",$select_catalog['id'])
									->and_where("active=",1)
									->and_where('price>=', $min_price)
									->and_where('price<=', $max_price)->getOne();
					}

					//$allProd = $this->model->products()->select("COUNT(id) as count")->where("catalog=",$select_catalog['id'])->and_where("active=",1)->getOne();
					
					//printr(sizeof($products));
					//printr($allProd['count']);
					
					if($allProd['count']<=$first+$last)
						$data->assign("hb", 1);
					else $data->assign("hb", 0);

					
					$products = $this->setAction($products);
					
					$products = $this->convertPrice($products);
					
					$data->assign("where_arr", array_values($where_arr));
					$data->assign("prop_names", $prop_names);
					$data->assign("treeNode", $treeNode);
					
					$data->assign("isAjax", $isAjax);
					$data->assign("first", $first);
					$data->assign("last", $last);
					
					$data->assign("min_price_filter", ceil($min_price*$this->course));
					$data->assign("max_price_filter", ceil($max_price*$this->course));
					
					$data->assign("select_catalog",$select_catalog);
					$data->assign("products", $products);
					return $this->dwoo->get("product_list.php", $data);
				}
			}
				
		}
		
		public function one_product()
		{
			$data=new Dwoo_Data();
			
			if(isset($this->router->vars["href"]))
			{
				$href = $this->router->vars["href"];
				$product = $this->model->products()->joined(true)->linked(true)->where("href=", $href)->and_where("active=",1)->getOne();	
				$this->product['menuname'] = $product['name'];
				$this->product['href'] = "product/".$product['href'];
				
				$this->breadcrumb['menuname'] = $product['catalog']['name'];
				$this->breadcrumb['href'] = "category/".$product['catalog']['href'];
				
				$treeNode = $this->model->catalog()->treeNodeRootById($product['catalog']['id']);
				
				
				$this->href = $treeNode['href'];
				if($treeNode['id']!=$product['catalog']['id'])
				{
					$this->breadcrumb['menuname'] = $product['catalog']['name'];
					$this->breadcrumb['href'] = "category/".$treeNode['href']."/".$product['catalog']['href'];
					$this->subcategory['menuname'] = $treeNode['name'];
					$this->subcategory['href'] = "category/".$treeNode['href'];
				}
				if(is_array($product['propertys']))
				{
					$property_all = $this->model->property()->getByActive(1);
					
					foreach($product['propertys'] as $key=>$item)
					{
						
						foreach($property_all as $one)
						{
							//printr($item);
							if($one['id']==$item['name'])
							{
								$product['propertys'][$key]['name_property'] = $one['name'];
								$product['propertys'][$key]['sys_property'] = $one['sys_name'];
								$product['propertys'][$key]['unit_property'] = $one['unit'];
							}
						}	
						
					}
				
				}
				
				$coments = $this->model->coments()->where("active=",1)->and_where("product_id=", $product['id'])->get();
				$mean = 0;
				$maxMean = 0;
				$cnt = 0;
				if($coments)
				{
					foreach($coments as $one)
					{
						$cnt++;
						$mean+=$one['mark'];
						if($one['mark']>$maxMean)
							$maxMean = $one['mark'];
					}

				}
				$endWord = $this->getNumEnding($cnt, array("отзыв", "отзыва", "отзывов"));
				//printr($product);
				
				
				$product = $this->convertPrice($product, true);
				


				//$data->assign("property", $property);
				
				$cart = $this->checkCart();
				
				$order = false;
				if($this->session->get("products"))
				{
					$basket = $this->session->get("products");
					
					foreach($basket as $v)
						if($v['id'] == $product['id'])
							$order = true;
				}
				
				
				$for_seo = array();
				
				if(!empty($product['cms_seo_title']))
					$for_seo['cms_seo_title'] = $product['cms_seo_title'];
				if(!empty($product['cms_seo_description']))
					$for_seo['cms_seo_description'] = $product['cms_seo_description'];
				if(!empty($product['cms_seo_keywords']))
					$for_seo['cms_seo_keywords'] = $product['cms_seo_keywords'];
				
				$for_seo['name'] = $product['name'];
				$for_seo['category'] = $product['catalog']['name'];
				$this->modules->page->parse_seo($for_seo);
				
				
				$actionsList = $this->getAction();
				$actionId = array();
				foreach($actionsList as $one)
				{
					if(in_array($product['id'], $one['products']))
					{
						$actionId[] = $one['id'];
					}
				}
				$actions = array();
				$action = array();
				if(!empty($actionId))
				{
					$actions = $this->model->action()->where("id", $actionId)->get();
					if($actions)
					{
						/*
foreach($actions as $key=>$action)
						{
							$actions[$key] = $this->getActionImage($action);
						}
*/
						$actions_tmp = $actions;
						$action = array_shift($actions_tmp);

						
					}
					
					//$action = $this->getActionImage($action);
				}
				
				$data->assign("action", $action);
				$data->assign("actions", $actions);
				$data->assign("order", $order);
				$data->assign("cnt", $cnt);
				$data->assign("endWord", $endWord);
				$data->assign("cart", $cart);
				$data->assign("mean", $mean);
				$data->assign("coments", $coments);
				$data->assign("product", $product);
			}
				
			
			
			return $this->dwoo->get("one_product.php", $data);
		}

		public function checkCart()
		{
			if($this->session->get("products"))
			{
				$products = $this->session->get("products");
				$count = 0;
				$data = array();
				$data['count'] = 0;
				$data['totalPrice'] = 0;
				$id = array();
				foreach($products as $one)
				{
					$data['count']+=$one['count'];
					$id[] = $one['id'];
				}
				
				$array = $this->model->products()->where("id",$id)->get();
				$array = $this->convertPrice($array);
				
				foreach($products as $one)
				{
					foreach($array as $two)
					{
						if($one['id'] == $two['id'])
							$data['totalPrice']+=$two['price'];
					}
				}
				
				return $data;
			}
		}
		
		public function import_confirm() {
			if ($this->session->get('interline_import')) 
				return $this->dwoo->get("import-confirm.php");
		}
		
		public function contacts()
		{
			$data=new Dwoo_Data();
			
			$contacts = $this->model->contacts()->order("pos ASC")->getByActive(1);
			if($this->session->get("thank_kontacts") && $this->session->get("thank_kontacts") == 1)
			{
				$data->assign("thanks", 1);
				$this->session->delete("thank_kontacts");
			}
			$data->assign("contacts", $contacts);
			return $this->dwoo->get("contacts.php", $data);

		}
		
		public function services()
		{
			$data=new Dwoo_Data();
			$array = $this->model->page()->where("inmenu=",1)->and_where("tree_id=",5)->get();
			$data->assign("array", $array);
			return $this->dwoo->get("services_list.php", $data);
		}
		
		public function convertPrice($array, $no_array=false)
		{
			if($no_array)
			{
				if(isset($array['price']) && !empty($array['price']) && isset($this->course) && !empty($this->course))
				{
					$array['price'] = ceil($array['price'] * $this->course);	
					return $array;
				}
			} elseif(is_array($array) && !empty($array) && isset($this->course) && !empty($this->course))
			{
				foreach($array as $key=>$item)
				{
					if(isset($item['price']))
					{
						$array[$key]['price'] = ceil($item['price']*$this->course);
					}
				}
				return $array;
			}	
		}
		
		public function search()
		{
			$data=new Dwoo_Data();
			

			if($this->request->post("search"))
			{
				$search = strip_tags(trim($this->request->post("search")));
				//$search = str_replace("/", " ", $search);
				//$searchMas = explode(" ", $search);

				/*
				$sql = "SELECT id FROM products WHERE articul LIKE '%{$search}%' 
						OR name_rus LIKE '%{$search}%' 
						OR body_rus LIKE '%{$search}%'
						OR body_rus LIKE '%{$search}%'
						OR 1Cname  LIKE '%{$search}%'
						OR description_addition LIKE '%{$search}%'
						";
				 */
				$searchNew = str_replace("/", " ", $search);
			 	$sql = "SELECT id FROM products WHERE 
			 			articul LIKE '%{$search}%'
			 			OR REPLACE( name_rus,  '/',  ' ' ) LIKE '%$searchNew%'
			 			OR REPLACE( body_rus,  '/',  ' ' ) LIKE '%$searchNew%'
			 			OR REPLACE( 1Cname,  '/',  ' ' ) LIKE '%$searchNew%'
			 			OR REPLACE( description_addition,  '/',  ' ' ) LIKE '%$searchNew%'";

				//$query = "SELECT id FROM products WHERE MATCH (name_rus,body_rus) AGAINST ('+".implode("', '+", $searchMas)."' IN BOOLEAN MODE)";
				
				//$query2 = "SELECT id FROM products WHERE MATCH (name_rus) AGAINST ('WH')";
				
				//$query3 = "ALTER TABLE products ADD FULLTEXT(articul, name_rus, body_rus, 1Cname, description_addition)";
				//$sql = "SELECT id FROM products WHERE name_rus IN ('".implode("', '", $searchMas)."')";
				//$id = array_keys($this->database->query($query)->resultArray("id"));
				//printr($this->database->query($query)->resultArray());
			    $id = array_keys($this->database->query($sql)->resultArray("id"));
			    $result = $this->model->products()->where("active=",1)->linked(true)->and_where("id",$id)->get();
			    $result = $this->convertPrice($result);
			    
			    $idCartProd = $this->getIdCartProd();
				$data->assign("idCartProd", $idCartProd);
				
			    $data->assign("search", $search);
			    $data->assign("array", $result);
			   // printr($result);
			}
			//$this->request->post("search");
			//printr();
			

			return $this->dwoo->get("search.php", $data);
		}
		
		public function setAction($array)
		{
			if(is_array($array) && !empty($array))
			{
				$actions = $this->getAction();
				if(is_array($actions) && !empty($actions))
				{
					foreach($array as $key=>$item)
					{
						foreach($actions as $one)
						{
							if(in_array($item['id'], $one['products']))
							{
								$array[$key]['actions']['name'] = $one['name'];
								$array[$key]['actions']['href'] = $one['href'];
							}
						}
					}
				}
			}
			return $array;
		}
		
		public function getAction()
		{
			$actions = $this->model->action()->order("pos ASC")->getByActive(1);
			$catalog1 = array();
			$catalog2 = array();
			$catalog3 = array();
			$catalog4 = array();
			if($actions)
			{
				foreach($actions as $one)
				{
					if($one['product1'])
					{
						$catalog1 = $this->model->catalog()->where("active=", 1)->treeChildrenById($one['product1']);
						if(is_array($catalog1) && !empty($catalog1))
						{
							$catalog1 = array_keys($catalog1);
						}
					}
					if($one['product2'])
					{
						$catalog2 = $this->model->catalog()->where("active=", 1)->treeChildrenById($one['product2']);
						if(is_array($catalog2) && !empty($catalog2))
						{
							$catalog2 = array_keys($catalog2);
						}
					}
					if($one['product3'])
					{
						$catalog3 = $this->model->catalog()->where("active=", 1)->treeChildrenById($one['product3']);
						if(is_array($catalog3) && !empty($catalog3))
						{
							$catalog3 = array_keys($catalog3);
						}
					}
					if($one['product4'])
					{
						$catalog4 = $this->model->catalog()->where("active=", 1)->treeChildrenById($one['product4']);
						if(is_array($catalog4) && !empty($catalog4))
						{
							$catalog4 = array_keys($catalog4);
						}
					}
					
					$arr = array_merge($catalog1, $catalog2, $catalog3, $catalog4);
					$sql = "SELECT id FROM products WHERE active = '1' AND catalog IN (".implode(',', $arr).")";
					$pr = array_keys($this->database->query($sql)->resultArray("id"));
					//$products = $this->model->products()->where('active=',1)->and_where("catalog",$arr)->get();
					$array[] = array('name'=>$one['short_name'], 'href'=>$one['href'], 'products'=>$pr, 'id'=>$one['id']);
					
				}

				return $array;
			}
			
			//printr($catalog);
		}
		
		public function action_list()
		{
			$data=new Dwoo_Data();
			$actions = $this->model->action()->order("pos ASC")->getByActive(1);
			/*
foreach($actions as $key=>$item)
			{
				if($item['product1'])
				{
					$catalog1 =  $this->model->catalog()->treeNodeRoot($item['product1']);
					$actions[$key]['imgCatalog1'] = $catalog1['image'];
				}
				if($item['product2'])
				{
					$catalog1 =  $this->model->catalog()->treeNodeRoot($item['product2']);
					$actions[$key]['imgCatalog2'] = $catalog1['image'];
				}
				if($item['product3'])
				{
					$catalog1 =  $this->model->catalog()->treeNodeRoot($item['product3']);
					$actions[$key]['imgCatalog3'] = $catalog1['image'];
				}
			}
*/
			
			$data->assign("actions", $actions);
			return $this->dwoo->get("action_list.php", $data);
		}
		
		public function one_action()
		{
			$data=new Dwoo_Data();
			if(isset($this->router->vars["href"]))
			{
				$href = $this->router->vars["href"];
				
				$actions = $this->model->action()->where("href=",$href)->and_where("active=",1)->getOne();
				
				$this->breadcrumb['menuname'] = $actions['name'];
				$this->breadcrumb['href'] = $actions['href'];
				
				
				$catalog1 = array();
				$catalog2 = array();
				$catalog3 = array();
				$catalog4 = array();

				if($actions['product1'])
				{
					$catalog1 = $this->model->catalog()->where("active=", 1)->treeChildrenById($actions['product1']);
					$catalog1_node = $this->model->catalog()->treeNodeRoot($actions['product1']);
					
					if(is_array($catalog1) && !empty($catalog1))
					{
						$catalog1 = array_keys($catalog1);
						
					}
				}
				if($actions['product2'])
				{
					$catalog2 = $this->model->catalog()->where("active=", 1)->treeChildrenById($actions['product2']);
					$catalog2_node = $this->model->catalog()->treeNodeRoot($actions['product2']);
					if(is_array($catalog2) && !empty($catalog2))
					{
						$catalog2 = array_keys($catalog2);
					}
				}
				if($actions['product3'])
				{
					$catalog3 = $this->model->catalog()->where("active=", 1)->treeChildrenById($actions['product3']);
					$catalog3_node = $this->model->catalog()->treeNodeRoot($actions['product3']);
					if(is_array($catalog3) && !empty($catalog3))
					{
						$catalog3 = array_keys($catalog3);
					}
				}
				if($actions['product4'])
				{
					$catalog4 = $this->model->catalog()->where("active=", 1)->treeChildrenById($actions['product4']);
					
					if(is_array($catalog4) && !empty($catalog4))
					{
						$catalog4 = array_keys($catalog4);
					}
				}
				
				$arr = array_merge($catalog1, $catalog2, $catalog3, $catalog4);
				//$sql1 = "SELECT id FROM products WHERE active = '1' AND catalog IN (".implode(',', $catalog1).")";
				$sql2 = "SELECT id FROM products WHERE active = '1' AND catalog IN (".implode(',', $catalog2).")";
				$sql3 = "SELECT id FROM products WHERE active = '1' AND catalog IN (".implode(',', $catalog3).")";
				$sql4 = "SELECT id FROM products WHERE active = '1' AND catalog IN (".implode(',', $catalog4).")";
				
				$products1 = $this->model->products()->linked(true)->where('active=',1)->and_where("product_instock=",1)->and_where("catalog",$catalog1)->get();
				$products1 = $this->convertPrice($products1);
				
				$products2 = $this->model->products()->linked(true)->where('active=',1)->and_where("product_instock=",1)->and_where("catalog",$catalog2)->get();
				$products2 = $this->convertPrice($products2);
				
				
				$products3 = $this->model->products()->linked(true)->where('active=',1)->and_where("product_instock=",1)->and_where("catalog",$catalog3)->get();
				$products3 = $this->convertPrice($products3);
			
				if($actions['product3_sale'])
				{
					foreach($products3 as $key=>$item)
					{
						$products3[$key]['price2'] = ceil($item['price']-(int)$actions['product3_sale']*$item['price']/100);
					}
				}
						
				$products4 = $this->model->products()->linked(true)->where('active=',1)->and_where("product_instock=",1)->and_where("catalog",$catalog4)->get();
				$products4 = $this->convertPrice($products4);
					
				if($actions['product4_sale'])
				{
					foreach($products4 as $key=>$item)
					{
						$products4[$key]['price2'] = ceil($item['price']-(int)$actions['product4_sale']*$item['price']/100);
					}
				}
				if(is_array($products3) && is_array($products4))
					$products34 = array_replace($products3, $products4);
				elseif(is_array($products3))
					$products34 = $products3;
				elseif(is_array($products4))
					$products34 = $products4;
				
				uasort($products34, array($this, 'cmp'));

				//$products1 = array_keys($this->database->query($sql1)->resultArray("id"));
				//$products2 = array_keys($this->database->query($sql2)->resultArray("id"));
				
				//printr($pr);
				
				//$array[] = array('name'=>$actions['short_name'], 'href'=>$actions['href'], 'products'=>$pr);
				$catalogPush = array(0=>$catalog1_node, 1=>$catalog2_node, 2=>$catalog3_node);
				
				$cartProdId = array();
				if($this->session->get("products"))
				{
					$cartProducts = $this->session->get("products");
					foreach($cartProducts as $k=>$v)
						$cartProdId[] = $v['id'];
				}
				//printr($this->session->get("products"));
			
				/* START SEO */
				$this->modules->page->parse_seo($actions);
				/* END SEO */	
				
				$data->assign("cartProdId", $cartProdId);
				$data->assign("node1", $catalog1_node);
				$data->assign("node2", $catalog2_node);
				$data->assign("node3", $catalog3_node);
				$data->assign("products1", $products1);
				$data->assign("products2", $products2);
				$data->assign("products3", $products34);
				
				$data->assign("action", $actions);
			}
			
			
			
			return $this->dwoo->get("one_action.php", $data);
		}
		
		public function getActionImage($action)
		{
			if($action)
			{
				if($action['product1'])
				{
					$catalog1 =  $this->model->catalog()->treeNodeRoot($action['product1']);
					$action['imgCatalog1'] = $catalog1['image'];
				}
				if($action['product2'])
				{
					$catalog1 =  $this->model->catalog()->treeNodeRoot($action['product2']);
					$action['imgCatalog2'] = $catalog1['image'];
				}
				if($action['product3'])
				{
					$catalog1 =  $this->model->catalog()->treeNodeRoot($action['product3']);
					$action['imgCatalog3'] = $catalog1['image'];
				}
				
				return $action;
			}
			
		}
		
		public function getIdCartProd()
		{
			$idCartProd = array();
			if($this->session->get("products"))
			{
				$cartProd = $this->session->get("products");
				 
				if($cartProd) 
				{
					foreach($cartProd as $one)
					{
						$idCartProd[] = $one['id'];
					}
						
				}
			}
			return $idCartProd;
		}
		
		public function breadcrumbs()
		{
			$data=new Dwoo_Data();
			$array = $this->modules->page->selectedByLevel;
			if(isset($this->subcategory))
			{
				array_push($array, $this->subcategory);
			}
			if(isset($this->breadcrumb))
			{
				array_push($array, $this->breadcrumb);
			}
			if(isset($this->product))
			{
				array_push($array, $this->product);
			}
			
			$data->assign("array", $array);
			
			return $this->dwoo->get("_breadcrumbs.php", $data);
		}
		

		public function cmp($a, $b)
		{
			 if ($a['price2'] == $b['price2']) {
		        return 0;
		    }
		    return ($a['price2'] < $b['price2']) ? -1 : 1;
		}
		
		public function getNumEnding($number, $endingArray)
		{
		    $number = $number % 100;
		    if ($number>=11 && $number<=19) {
		        $ending=$endingArray[2];
		    }
		    else {
		        $i = $number % 10;
		        switch ($i)
		        {
		            case (1): $ending = $endingArray[0]; break;
		            case (2):
		            case (3):
		            case (4): $ending = $endingArray[1]; break;
		            default: $ending=$endingArray[2];
		        }
		    }
		    return $ending;
		}
		
	}
