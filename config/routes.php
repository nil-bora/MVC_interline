<?php if(!defined('LIB_DIR')) exit('Access Forbidden');

	$routes = array
	(
		"user/import"=>"import/import_index",
		"user/import/zip"=>"import/extract_zip",
		"user/import/prepare"=>"import/import_prepare",
		"user/import/make"=>"import/import_make",
		"user/import/approve"=>"import/import_approve",
		'user/import/load'=>'import/import_load',
		"__sample"=>"main/sample",
		"prosucts/add"=>"main/addproducts",
		"category/sort"=>"main/categorySort",
		"products/image/update"=>"main/imageUpdate",
		"products/update"=>"main/updateProducts",
		"products/property/update"=>"main/propertyUpdate",
		"products/add/coments"=>"main/addComents",
		
		"comments/add"=>"main/addComment",
		
		"ajax/list"=>"ajax/productListPaginator",
		"ajax/add/item"=>"ajax/addItem",
		"test/action"=>"main/actionTest",
		'add/action/products'=>'ajax/addActionProducts',
		'get/product/service/instructions'=>'ajax/getProductInstructions',
		
		"products/xml/generate"=>"xml/generateXml",
		
		"follow/price"=>"main/followPrice",
		"callback/send"=>"main/sendCallBack",
		
		"cron/follow/start"=>"Cron/cronStart",
		"cron/follow/price/start"=>"Cron/startFollow",
		
		'perenos/property/products'=>"Perenos/propertyProd",
		'contacts/form/question/send'=>"main/questionSend",
		'generate/token'=>"main/generateToken"
	);