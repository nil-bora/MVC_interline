<?php if(!defined('LIB_DIR')) exit('Access Forbidden'); 
	
	class ImportController extends Controller
	{
		var $common;

		public function __construct()
		{
		    parent::__construct();
		}
		public function test()
		{
			echo 11;
		}
		function delete_directory($dirname)
		{
			$dir_handle=false;

			if (is_dir($dirname))
				$dir_handle = opendir($dirname);

			if (!$dir_handle)
				return false;

			while($file = readdir($dir_handle)) {
				if ($file != "." && $file != "..") {
					if (!is_dir($dirname."/".$file))
						@unlink($dirname."/".$file);
					else
						$this->delete_directory($dirname.'/'.$file);    
				}
			}

			closedir($dir_handle);
			rmdir($dirname);

			return true;
		}
		
		 public function removeDirectory($dir) {
		    if ($objs = glob($dir."/*")) {
		       foreach($objs as $obj) {
		         is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
		       }
		    }
		    rmdir($dir);
		  }

		
		
		public function extract_zip($archive=null, $return=false)
		{
			$post=$this->request->post();
			$import_dir=ROOT_PATH."test.interline.ua/storage/import";
			
			if ($archive) {
				$info=pathinfo($archive);
				$foldername=$info["dirname"]."/".$info["filename"];

				$zip = new ZipArchive;
				$res = $zip->open(ROOT_PATH."test.interline.ua/storage".$archive);
				
				if($res === TRUE)
				{
					if(is_dir(ROOT_PATH."weba/storage".$foldername))
						$this->delete_directory(ROOT_PATH."test.interline.ua/storage".$foldername);

					$zip->extractTo(ROOT_PATH."test.interline.ua/storage".$foldername);
					
					$zip->close();
					
					$list = scandir(ROOT_PATH."test.interline.ua/storage".$foldername);
					
					$file = $list[2];
					
					@unlink(ROOT_PATH."test.interline.ua/storage".$archive);
					
					return $this->import_prepare($foldername.'/'.$file, true);
				}
				else {
					unlink(ROOT_PATH."test.interline.ua/storage".$archive);
					return false;
				}

			}

			if(isset($post["zip"]))
			{
				if(substr($post["zip"], 0, strlen($import_dir))!=$import_dir)
					jsonout(array("error"=>"Ошибка с папкой импорта"));

				$info=pathinfo($post["zip"]);
				$foldername=$info["dirname"]."/".$info["filename"];

				$zip = new ZipArchive;
				$res = $zip->open($post["zip"]);

				if($res === TRUE)
				{
					if(is_dir($foldername))
						$this->delete_directory($foldername);

					$zip->extractTo($foldername);
					$zip->close();

					$out=array("ok"=>"1");
				}
				else
					$out=array("error"=>"Не могу прочитать файл архива");
			}
			else
				$out=array("error"=>"Файл архива не указан");

			jsonout($out);
		}

		function dir_tree($dir)
		{

			$path = '';
			$stack[] = $dir;
			$allowed=array("xml", "zip", "csv", "DBF", "dbf");
	
			while ($stack)
			{
				$thisdir = array_pop($stack);
				//xprintr($thisdir);
				if ($dircont = scandir($thisdir))
				{
					$i=0;
					while (isset($dircont[$i]))
					{
						if ($dircont[$i] !== '.' && $dircont[$i] !== '..')
						{
							$current_file = "{$thisdir}/{$dircont[$i]}";

							if (is_file($current_file) && in_array(substr($current_file, -3), $allowed))
							{
								$path[] = "/{$thisdir}/{$dircont[$i]}";
							}
							elseif (is_dir($current_file))
							{
								$path[] = "/{$thisdir}/{$dircont[$i]}";
								$stack[] = $current_file;
							}
						}

						$i++;
					}
				}
			}
			return $path;
		}

		function format_bytes($size)
		{
			$units = array(' B', ' KB', ' MB', ' GB', ' TB');

			for ($i = 0; $size >= 1024 && $i < 4; $i++)
				$size /= 1024;

			return str_replace(",", ".", round($size, 2)).$units[$i];
		}

		public function import_index()
		{
			if($this->modules->users->is("dev_mode"))
			{
				$data_dwoo=new Dwoo_Data();

				$import_dir=ROOT_PATH."test.interline.ua/storage/import";
				//xprintr($import_dir);
					
				$files=array();
				$allowed=array("xml", "zip", "csv", "DBF", "dbf");
	
				$dir = $this->dir_tree($import_dir);
				
				if ($dir) {
					foreach($dir as $one)
					{
						if(in_array(substr($one, -3), $allowed)){
							$tmpKeyDate = nice_date(filemtime($one));
							$files[$tmpKeyDate["UNIX"]]=array("file"=>$one, "date"=>nice_date(filemtime($one)), "size"=>$this->format_bytes(filesize($one)));
						}
					}
				} else {
					$files = array();
				}
				
				ksort($files);
								
				$data_dwoo->assign("data", $files);
	
				jsonout(array("ok"=>$this->dwoo->get("cms/import-index.php", $data_dwoo)));
			}
			else
				jsonout(Array("error"=>"У вас недостаточно прав для доступа к импорту"));
		}
		
		public function import_load() {
			$file = $this->request->post('filename');
			
			if (substr($file, -3) == 'zip') {
				//if ($this->import_prepare($file, true))
					jsonout(array("ok"=>$this->extract_zip($file, true)));
				//else
					//jsonout(array("error"=>'Некорректный файл импорта'));
			}
			
			if (substr($file, -3) == 'xml') {
				if ($this->import_prepare($file, true))
					jsonout(array("ok"=>$this->import_prepare($file, true)));
				else
					jsonout(array("error"=>'Некорректный файл импорта'));
			}
		}
				
		public function import_prepare($xml= NULL, $return=false)
		{
			ini_set('memory_limit', '1024M');
			error_reporting(0);
			
			if($this->modules->users->is("dev_mode"))
			{
			
				ini_set('memory_limit', '1024M');
				ini_set("max_execution_time", 0);
				//set_time_limit(0);
				libxml_use_internal_errors(true);
				
				if (!$xml)
					$xml = $this->request->post('xml');
				else
					$xml = WWW_DIR."storage".$xml;
					//$xml = ROOT_PATH."test.interline.ua/storage".$xml;
			
				$row = 1; 
				$dbf = dbase_open($xml,0);
				$row = dbase_numrecords($dbf);	

				$data_dwoo = new Dwoo_Data();
				
				$data_dwoo->assign("products_count", $row);
				
				$data_dwoo->assign("name", $xml);
				if (!$return)
				//echo $this->dwoo->get("cms/import-prepare.php", $data_dwoo);
				jsonout(array("ok"=>$this->dwoo->get("cms/import-prepare.php", $data_dwoo)));
				else
					return $this->dwoo->get("cms/import-prepare.php", $data_dwoo);
				
			}
		}
		
		public function import_make() {
			if($this->modules->users->is("dev_mode"))
			{
				//$this->removeDirectory(ROOT_PATH."test.interline.ua/thumbs/");
				$this->database->query("DROP TABLE products_import_new");
				$this->database->query("CREATE TABLE products_import_new LIKE products");
				
				$this->database->query("DROP TABLE products_import_new_tmp");
				$this->database->query("CREATE TABLE products_import_new_tmp LIKE products");
				
				//$this->database->query('TRUNCATE TABLE products_import');
				$this->database->query("INSERT INTO products_import_new SELECT * FROM products");
				
				$this->database->query("INSERT INTO products_import_new_tmp SELECT * FROM products");
				
				//$this->database->query("TRUNCATE TABLE products_import");
				
				ini_set('memory_limit', '1024M');
				ini_set("max_execution_time", 0);
				
				$xml = $this->request->post('xml');
				$dbf = dbase_open( $xml, 0 );

		        $records_count = dbase_numrecords($dbf);
		        $actual_products = array();
		        $list['records_count']  = $records_count;
		        $list['unrecognized']  = 0;
		        $list['products'] = array();
		        $list['added'] = 0;
		        $list['updated'] = 0;
		        $list['down'] = 0;
		        $list['deactual'] = 0;
		
		        for ($i = 1; $i <= $records_count; $i++) {
		            $r = dbase_get_record_with_names($dbf, $i);
		
		            $article = iconv('cp866', 'utf-8', trim($r['CODE']));
		            $type 	 = iconv('cp866', 'utf-8', trim($r['TYPE']));
		            $brand 	 = iconv('cp866', 'utf-8', trim($r['FIRM']));
		            $mark 	 = iconv('cp866', 'utf-8', trim($r['DESCR'])); // bad or discounted
		            $name 	 = iconv('cp866', 'utf-8', trim($r['NAME']));
		            $name   .= (empty($mark)) ? '' : '-markdown';
		            $price	 = trim($r['PRICE']);
		            $sk0	 = trim($r['SK']) != '' ? 1 : 0;
		            $sk1	 = trim($r['SK1']) != '' ? 1 : 0;
		            $sk2	 = trim($r['SK2']) != '' ? 1 : 0;
		            $actual  = ($sk0 || $sk1 || $sk2) ? 1 : -1;
		            $is_down  = ($mark) ? "!= ''" : "= ''";
		
		            if (!$type || $brand != 'INTERLINE') {
		                continue;
		            }
		
		            if ($mark) {
		                $list['down']++;
		            }
		
		            if (!$actual) {
		                $list['deactual']++;
		            }
					
		            $cat_id = (empty($mark)) ? $this->get_category_by_type($type) : 8;
					
		            $list['unrecognized'] += ($cat_id) ? 0 : 1;
		
		            $isset_product = $this->database->query("SELECT id
		                                               FROM products_import_new_tmp
		                                               WHERE articul = '".trim($article)."'
		                                                   AND mark $is_down")->one();
		
		
		            if (empty($isset_product)) {
		                $this->database->query("INSERT INTO products_import_new_tmp
		                                  SET
		                                      1Cname = '".addslashes(trim($type))."',
		                                      name_rus = '".addslashes(trim($name))."',
		                                      href = '".safe_upload_name($name)."',
		                                      articul = '".addslashes(trim($article))."',
		                                      price = '".(int)$price."',
		                                      mark = '".addslashes(trim($mark))."',
		                                      product_instock = '".addslashes(trim($actual))."',
		                                      active = '".addslashes(trim($actual))."',
		                                  ");
		
		                $list['added']++;
		            } else {
		                $this->database->query("UPDATE products_import_new_tmp
		                                  SET
		                                      1Cname = '".addslashes(trim($type))."',
		                                      price = '".(int)$price."',
		                                      mark = '".addslashes(trim($mark))."',
		                                      articul = '".addslashes(trim($article))."',
		                                      product_instock = '".$actual."',
		                                      active = '".$actual."'
		                                  WHERE id = '".$isset_product."'");
		
		                $list['updated']++;
		            }
		
		            $list['products'][] = array(
		                'article'  => $article,
		                'name'     => $name,
		                'actual'   => $actual,
		                'new'      => (empty($isset_product)) ? 1 : 0,
		            );
		
		            $actual_products[] = $isset_product;
		        }
		
		        $actual_products = implode(",", $actual_products);
		
		        $deactual_products = $this->database->query("SELECT
		                                                   id,
		                                                   article,
		                                                   name_rus
		                                               FROM products_import_new_tmp
		                                               WHERE
		                                                   id NOT IN (".$actual_products.")
		                                                   AND product_instock = '1' AND active = '1'
		                                               ")->resultArray();
		
		        $this->database->query("UPDATE products_import_new_tmp
		                          SET product_instock = '-1', active = '-1'
		                          WHERE id NOT IN (".$actual_products.")
		                          ");
		
		        if ($deactual_products) {
		            foreach($deactual_products as $item) {
		                $list['products'][] = array(
		                    'article'  => $item['articul'],
		                    'name'     => $item['name_rus'],
		                    'actual'   => 0,
		                    'new'      => 0,
		                );
		
		                $list['deactual']++;
		            }        
		        }
		        
		
		        dbase_close($dbf);
				
				
				
				//$this->model->catalog()->treeRefresh();
				

				//$this->rrmcache(ROOT_PATH . 'test.interline.ua/storage/460x460');
				$this->session->set('interline_import', true);
				
				$this->database->query('TRUNCATE TABLE products');
				$this->database->query('INSERT INTO products SELECT * FROM products_import_new_tmp');
				
				jsonout(array("ok"=>$this->dwoo->get("cms/import-ok.php")));
			}
			
		}
		
		public function get_category_by_type($type) 
		{
	        $cat = $this->database->query("SELECT
	                                      id,
	                                      name
	                                  FROM relation_name_1c
	                                  WHERE name = '".strip_tags(trim($type))."'")->one();

	        if (empty($cat)) {
	            $this->database->query("INSERT INTO relation_name_1c
	                              SET name = '".strip_tags(trim($type))."'");
	
	            return 0;
	        }
	
	        return $cat['id'];
	    }
			
		function import_approve()
		{
			if($this->request->post('operation'))
			{
				if ($this->request->post('operation') == 'approve')
				{
				
					
				
					// $this->database->query('TRUNCATE TABLE catalog');
					// $this->database->query('INSERT INTO catalog SELECT * FROM catalog_import');
					// $this->database->query('TRUNCATE TABLE products_working');
					// $this->database->query('INSERT INTO products_working SELECT * FROM products_import');
					
					$this->session->delete('interline_import');
					
					jsonout(array('ok'=>true));					
				}
				
				if ($this->request->post('operation') == 'cancel')
				{
					$this->database->query('TRUNCATE TABLE products');
					$this->database->query('INSERT INTO products SELECT * FROM products_import_new');
					$this->session->delete('interline_import');
					jsonout(array('ok'=>true));
				}
			}
			else
				jsonout(array('ok'=>false));	
		}
		
		
		private function rrmcache($path)
		{
			$out=array();
			$thumbs_dir=$_SERVER['DOCUMENT_ROOT']._S_."thumbs"._S_;

			if($handle = opendir($thumbs_dir))
			{
				while(false !== ($file = readdir($handle)))
				{
					if($file != "." && $file != "..")
					{
						if(is_file($thumbs_dir.$file.$path))
							$out[]=$thumbs_dir.$file.$path;
					}
				}

				closedir($handle);
			}

			foreach($out as $one)
				@unlink($one);
		}

		public function import_xml($xml=null)
		{
			ini_set('memory_limit', '1024M');

			if($this->modules->users->is("dev_mode"))
			{
			}
			else
				jsonout(Array("error"=>"У вас недостаточно прав для доступа к импорту"));
		}

		
	}