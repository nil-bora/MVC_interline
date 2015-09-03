<?php if(!defined('LIB_DIR')) exit('Access Forbidden'); 
	
	class XmlController extends Controller
	{
		var $common;
		var $course;
		var $dir;

		public function __construct()
		{
		    parent::__construct();
			$course = $this->database->query("SELECT name_rus FROM course WHERE active='1' ORDER BY pos LIMIT 1")->one();
			$this->course = (float)$course;
			$this->dir = WWW_DIR."data/price/";
		}
			
		public function generateXml()
		{
			$products = $this->model->products()->where('active=',1)->joined(true)->and_where("product_instock=",1)->get();
			foreach($products as $key=>$item)
				$products[$key]['priceUAH'] = ceil($item['price']*$this->course);
			
			$catalog = $this->model->catalog()->where('active=',1)->order("tree_path ASC")->get();
			

			$hotline = $this->getHotlineTemplate($products, $catalog);
			$metamarket = $this->getMetamarketTemplate($products, $catalog);
			$nadavi = $this->getNadaviTemplate($products, $catalog);
			$priceua = $this->getPriceuaTemplate($products, $catalog);
			$yml = $this->getYmlTemplate($products, $catalog);
			
			if($hotline && $metamarket && $nadavi && $priceua && $yml)
				jsonout(array("ok"=>1));
			else
				jsonout(array("error"=>1));

			//printr($hotline);
		}
		
		public function getHotlineTemplate($products, $catalog=false)
		{
			$data=new Dwoo_Data();
			
			$data->assign("products", $products);
			$data->assign("catalog", $catalog);
			$data->assign("course", $this->course);
			$xml = $this->dwoo->get("xml/hotline.php", $data);
			file_put_contents($this->dir."hotline.xml", $xml);
			return $xml;
		}
		
		public function getMetamarketTemplate($products, $catalog=false)
		{
			$data=new Dwoo_Data();
			
			$data->assign("products", $products);
			$data->assign("catalog", $catalog);
			$data->assign("course", $this->course);
			$xml = $this->dwoo->get("xml/metamarket.php", $data);
			file_put_contents($this->dir."metamarket.xml", $xml);
			return $xml;
		}
		
		public function getNadaviTemplate($products, $catalog=false)
		{
			$data=new Dwoo_Data();
			
			$data->assign("products", $products);
			$data->assign("catalog", $catalog);
			$data->assign("course", $this->course);
			$xml = $this->dwoo->get("xml/nadavi.php", $data);
			file_put_contents($this->dir."nadavi.xml", $xml);
			return $xml;
		}
		
		public function getPriceuaTemplate($products, $catalog=false)
		{
			$data=new Dwoo_Data();
			
			$data->assign("products", $products);
			$data->assign("catalog", $catalog);
			$data->assign("course", $this->course);
			$xml = $this->dwoo->get("xml/priceua.php", $data);
			file_put_contents($this->dir."priceua.xml", $xml);
			return $xml;
		}
		
		public function getYmlTemplate($products, $catalog=false)
		{
			$data=new Dwoo_Data();
			
			$data->assign("products", $products);
			$data->assign("catalog", $catalog);
			$data->assign("course", $this->course);
			$xml = $this->dwoo->get("xml/yml.php", $data);
			file_put_contents($this->dir."yml.xml", $xml);
			return $xml;
		}
		
	}