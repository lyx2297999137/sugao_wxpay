<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__."/extra/Autoload.php";
require_once __DIR__.'/extra/common.php';
define('LOAD_FILEPATH',__DIR__);
/**
 * 项目入口文件
 */
class LoadVendor{
	protected static $apppath = null;
	private static function init(){
//		self::$apppath = realpath(dirname("./"));
                self::$apppath =__DIR__;
//                dump(self::$apppath);die;
		
		$Autoload = new \Autoload();
		$Autoload->addNamespace("sugao_wxpay", self::$apppath.DIRECTORY_SEPARATOR."sugao_wxpay".DIRECTORY_SEPARATOR);
		$Autoload->register();
	}

	public static function run(){
		self::init();
              
//                $a=new sugao_wxpay\Test\A();
//                $a->b();
	}
}

LoadVendor::run();
//\sugao_wxpay\Phplog\Testlog::go();