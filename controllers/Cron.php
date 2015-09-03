<?php if(!defined('LIB_DIR')) exit('Access Forbidden'); 
	
	class CronController extends Controller
	{
		var $common;
		var $course;

		public function __construct()
		{
		    parent::__construct();
			$course = $this->database->query("SELECT name_rus FROM course WHERE active='1' ORDER BY pos LIMIT 1")->one();
			$this->course = (float)$course;
		}
			
		public function cronStart()
		{

			$output = shell_exec('php /home/fender/www/interline/cron/cron.php cron/follow/price/start');

			echo "<pre>".$output."</pre>";
		}
		
		public function startFollow()
		{
			
			$filename=APP_DIR.'cron/block.php';

			$new_array = array();
			$follow_price = $this->model->follow_price()->joined(true)->order("pos ASC")->getByActive(1);

			foreach($follow_price as $key=>$item)
			{
				$email = strtolower(trim($item['email']));
				$new_array[$email][] =  $item;
			}

			foreach($new_array as $key=>$item)
			{
				foreach($item as $k=>$v)
					{	
						$new_array[$key][$k]['new_price'] = $v['product']['price'];
						if($v['old_price']<=$v['product']['price']){
							unset($new_array[$key][$k]);
						}
						else {
							
							$this->database->update('follow_price', array('old_price'=>$v['new_price'], 'procent'=>$v['old_price']-$v['new_price']), array('id'=>$v['id']));	
						}

							
					}
			}
			foreach($new_array as $key=>$item)
			{
				if(isset($item) && is_array($item) && !empty($item))
				{
					$data = new Dwoo_Data();
			
					$data->assign('products', $item);
					$message = $this->dwoo->get("email/follow-mail.php", $data);
					$mail = new PHPMailer(true);
					$mail->From = "admin@interline.ua";
					$mail->FromName = "Interline";
					$mail->CharSet = "utf-8";
					$mail->Subject = "Скидка на товары";
					$mail->IsHTML(true);
					$mail->MsgHTML($message);	
					$mail->AddAddress(trim($key));
					$mail->Send();
					$mail->ClearAddresses();
				}
			}
		}
		
		
		
	}
