<?php
namespace sugao_wxpay\Phplog;
/**
 * 使用例子
 //Testlog::go();
//Testlog::$log->add(Log::STRACE,'jjjjyrtj',array('file'=>__FILE__,'line'=>__LINE__));
//echo $b;
 */
class Testlog{
	/**
	 * @var object $log 保存Log对象实例
	 */
	public static $log;

	/**
	 * @desc 测是开始
	 */
	public static function go(){
		/**
		 * @desc 包含日志容器和日志写入类库
		 */
//		("./log.php");
		/** 
		 * @desc 包含異常處理類文件
		 */
//		("./myexception.php");
                $directory="/Runtime/debug";
                if(!is_dir(LOAD_FILEPATH.$directory)){
                    WSTCreateDir($directory);
                }
		self::$log = Log::instance();
		self::$log->attach(new Logwriter(LOAD_FILEPATH.$directory),Log::DEBUG);

		set_exception_handler(array("\\sugao_wxpay\\Phplog\\Myexception","exceptionHandler"));
		set_error_handler(array("\\sugao_wxpay\\Phplog\\Myexception","errorHandler"));
		//设置一个程序异常终止的时候的错误处理函数
		register_shutdown_function(array("\\sugao_wxpay\\Phplog\\Myexception","shutdownHandler"));
	}
}

