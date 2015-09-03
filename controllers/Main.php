<?php if(!defined('LIB_DIR')) exit('Access Forbidden'); 
	
	class MainController extends Controller
	{
		var $common;

		public function __construct()
		{
		    parent::__construct();
		}
		
		public function generateToken()
		{
			if($this->request->post('form'))
			{
				$form = $this->request->post('form');
				$data = array();
				foreach($form as $one)
					$data[$one['name']] = $one['value'];
				
				unset($data['_token']);
				
				$token = $this->tokenEncode($data);
				
				jsonout(array("token"=>$token));

			}
		}
		
		public function tokenEncode($data)
		{
			if($data)
			{
				$token = "";
				foreach($data as $one)
				{
					$token.=$one.":";	
				}
				$token.=session_id().":".$_SERVER['HTTP_USER_AGENT'].":".date("d-m-Y", time()).":".$_SERVER['REMOTE_ADDR'];
				$token = md5($token);
				
				return $token;
			}
			
		}
		
		public function tokenDecode($data, $token)
		{
			$token_now = $this->tokenEncode($data);
			if($token_now == $token)
				return true;
			else
				return false;
		}
		
		public function questionSend()
		{
			if($this->request->post('email') && $this->request->post('body') && $this->request->post('_token'))
			{
				$email = strip_tags(trim($this->request->post('email')));
				$body  = strip_tags(trim($this->request->post('body')));
				$name  = strip_tags(trim($this->request->post('name')));
				
				$data = array(
					"name"=>$name,
					"email"=>$email,
					"body"=>$body,
				);
				if($this->tokenDecode($data, $this->request->post('_token')))
				{
					if(filter_var($email, FILTER_VALIDATE_EMAIL))
					{
						$insert = $this->database->insert('question_clients', array(
												'idate'=>time(),
												'name'=>$name,
												'email'=>$email,
												'body'=>$body
						));
						if($insert)
						{
							$this->session->set('thank_kontacts', 1);
							redirect('/kontakty/');
							exit;
						}
					}
				}
				else{
					redirect('/kontakty/');
					exit;
				}
					
				
				
			}
		}
		
		public function sendCallBack()
		{
			if($this->request->post('name') && $this->request->post('phone'))
			{
				$name = $this->request->post('name');
				$phone = $this->request->post('phone');
				
				$insert = $this->database->insert("callback", array(
									"idate"=>time(),
									"name_rus"=>$name,
									"phone"=>$phone					
				));
				if($insert)
				{
					$data = new Dwoo_Data();
					$data->assign("date", date("d-m-Y H:s:i",time()));
					$data->assign("name", $name);
					$data->assign("phone", $phone);
					$message = $this->dwoo->get("email/call-me.php", $data);
					$mail = new PHPMailer(true);
					
					$mail->From = "admin@interline.ua";
					$mail->FromName = "Interline";
					$mail->CharSet = "utf-8";
					$mail->Subject = "Обратная связь";
					$mail->IsHTML(true);
					$mail->MsgHTML($message);
					
					$emails = $this->model->cms_interface()->where("label=","email-callme")->getOne();
					$emails = strip_tags(trim($emails['content']));
					$emails = explode(",", $emails);
					foreach($emails as $one)
					{
						if(!empty($one))
						{
							$mail->AddAddress(trim($one));
							$mail->Send();
							$mail->ClearAddresses();
						}
					}	
					jsonout(array("ok"=>1));
				}
					
			}
		}
		
		public function followPrice()
		{
			
			if($this->request->post('id') && $this->request->post('email'))
			{
				$id = (int)$this->request->post('id');
				$email = strtolower(trim($this->request->post('email')));
				if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$product = $this->model->products()->where("id=",$id)->getOne();
					$followPrice = $this->model->follow_price()->where("articult=", $product['articul'])->and_where("email=", $email)->getOne();
					if($followPrice)
					{
						$this->database->update("follow_price", array("old_price"=>$product['price'],"new_price"=>$product['price'], "active"=>1), array("articult"=>$product['articul']));
					} else 
					{
						$this->database->insert("follow_price", array(
												"idate"=>time(),
												"email"=>$email,
												"product"=>$id,
												"old_price"=>$product['price'],
												"new_price"=>$product['price'],
												"articult"=>$product['articul'],
												"procent"=>"0",
												"active"=>1
												
						));
					}
					
					jsonout(array("ok"=>1));
				}
				
				
			}
			
			
		}
		
		public function generateMail()
		{
			$follow = $this->model->products()->getByActive(1);
		}
		
		public function actionTest()
		{
			$actions = $this->model->action()->getByActive(1);
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
					$array[] = array('name'=>$one['short_name'], 'href'=>$one['href'], 'products'=>$pr);
					return $array;
				}
			}
			
		}
		
		public function addComment()
		{
			if($this->request->post("name") && $this->request->post("body") && $this->request->post("mark") && $this->request->post("id"))
			{
				$name = strip_tags(trim($this->request->post("name")));
				$body = strip_tags(trim($this->request->post("body")));
				$mark = (int)$this->request->post("mark");
				$product_id = (int)$this->request->post("id");
				
				$insert = $this->database->insert("coments", array(
					"idate"=>time(),
					"name_rus"=>$name,
					"body_rus"=>$body,
					"mark"=>$mark,
					"product_id"=>$product_id,
					"active"=>-1
					
				));
				if($insert)
					jsonout(array("ok"=>1));
			}
		}
		
		public function propertyUpdate()
		{
			$property = $this->database->query("SELECT * FROM fend_mod_property_test")->resultArray();
			foreach($property as $one)
			{
				 $this->database->insert("property", array(
						"sys_name"=>safe_upload_name($one['property_name']),
						"name_rus"=>$one['property_name'],
						"unit"=>$one['property_unit'],
						"active"=>'1'			
				 ));
			}
		}
		
		public function imageUpdate()
		{
			$images = $this->database->query("SELECT * FROM fend_mod_file_test")->resultArray();
			$dir = "/home/fender/www/test.interline.ua/storage/product/";
			$file_type = array(
					"image/jpeg",
					"image/png",
					"image/gif",
					"image/bmp"
			);
			$images_new = array();
			foreach($images as $key=>$item)
			{
				$dir_file = $dir.$item['file_elem_id']."/".$item['file_name'];
				if(file_exists($dir_file))
				{
					$type = mime_content_type($dir_file);
					if($type == "image/jpeg") {
						$images_new[] = $item;
					}	
				}
				
			}
			
			foreach($images_new as $key=>$item)
			 {
				 if(substr($item['file_name'], -6) == '_s.jpg')
				 {
				 	
				 	$file =substr($item['file_name'], 0, -6).'.jpg';
					$dir_img = "/storage/product/".$item['file_elem_id']."/".$file;
					$idProducts = $this->database->query("SELECT id FROM products WHERE product_id='".$item['file_elem_id']."'")->one();
				 }
			 }
			
		}
		
		public function addproducts()
		{
			$products = $this->database->query("SELECT * FROM fend_mod_product_test")->resultArray();
			foreach($products as $one)
			{
				$this->database->insert("products", array(
					"name_rus"=>$one['product_name'],
					"body_rus"=>$one['product_note'],
					"cms_seo_title_rus"=>$one['product_meta_title'],
					"cms_seo_description_rus"=>$one['product_meta_keywords'],
					"cms_seo_keywords_rus"=>$one['product_meta_description'],
					"product_id"=>$one['product_id'],
					"category_id"=>$one['product_category_id'],
					"1CName"=>$one['product_1Cname'],
					"href"=>$one['product_code'],
					"articul"=>$one['product_article'],
					"price"=>$one['product_price'],
					"price_special"=>$one['product_price_special'],
					"description"=>$one['product_description'],
					"description_addition"=>$one['product_description_addition'],
					"active"=>(($one['product_active'] == 1) ? '1' : '-1')
				));
			}
		}
		
		
		public function updateProducts()
		{
			$products = $this->database->query("SELECT * FROM fend_mod_product_test")->resultArray();
			foreach($products as $one)
			{
				$this->database->update("products", array(
					"mark"=>$one['product_mark']
				), array("product_id"=>$one['product_id']));
			}
		}
	}
