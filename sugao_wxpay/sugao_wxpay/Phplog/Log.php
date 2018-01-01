<?php
namespace sugao_wxpay\Phplog;
/**
 * 日志写入类
 */
class Log {
	// 日志消息等级
	const EMERGENCY = LOG_EMERG;    // 0
	const ALERT     = LOG_ALERT;    // 1
	const CRITICAL  = LOG_CRIT;     // 2
	const ERROR     = LOG_ERR;      // 3
	const WARNING   = LOG_WARNING;  // 4
	const NOTICE    = LOG_NOTICE;   // 5
	const INFO      = LOG_INFO;     // 6
	const STRACE    = 7;
	const DEBUG     = 8;
	/**
	 * @var  string  日志记录时间的格式
	 */
	public static $timestamp = 'Y-m-d H:i:s';
	/**
	 * @var  boolean  是否在运行过程中马上记录的标志
	 */
	public static $writeOnAdd = false;
	/**
	 * @var  Log 单例模式的容器
	 */
	protected static $_instance = null;
	/**
	 * 获取单例模式，并将Log::write方法加入脚本停止时执行的函数列表
	 *
	 *     $log = Log::instance();
	 *
	 * @return  Log
	 */
	public static function instance() {
		if (Log::$_instance === null) {
			Log::$_instance = new Log;
 			register_shutdown_function(array(Log::$_instance, 'write'));
		}
		return Log::$_instance;
	}

	/**
	 * @var  array  日志消息数组
	 */
	protected $_messages = array();
	
	/**
	 * @var  array  保存写日志(Logwriter)对象的数组
	*/
	protected $_writers = array();

	/**
	 * 添加一个写日志对象到日志对象(Log)中，并设置该写日志对象记录哪些错误等级
	 *
	 *     $log->attach($writer);
	 *
	 * @param   object   写日志对象
	 * @param   mixed    写日志对象要记录的错误的等级数组，或者要记录等最大等级
	 * @param   integer  如果前面的$levels不是数组，这个参数有效，表示最小的记录等级
	 * @return  Log
	*/
	public function attach(Logwriter $writer, $levels = array(), $min_level = 0) {
		if ( ! is_array($levels)) {
			$levels = range($min_level, $levels);
		}
		//将写日志对象和该对象要记录的等级存入日志对象中
		$this->_writers["{$writer}"] = array (
				'object' => $writer,
				'levels' => $levels
		);
		return $this;
	}

	/**
	 * 从日志对象中去除一个写日志对象. The same writer object must be used.
	 *
	 *     $log->detach($writer);
	 *
	 * @param   object  写日志(Log_Writer)实例
	 * @return  Log
	 */
	public function detach(Logger $writer) {
		// 移除一个“写日志”对象
		unset($this->_writers["{$writer}"]);
		return $this;
	}

	/**
	 * 添加一组日志信息到日志对象中
	 *
	 *     $log->add(Log::ERROR, 'Could not locate user: :user', array(
	 *         ':user' => $username,
	 *     ));
	 *
	 * @param   string  这组日志对象的错误等级
	 * @param   string  日志消息
	 * @param 	array 	记录错误位置信息
	 * 
	 * 		array('file'=>__FILE__,'line'=>'__LINE__');
	 *
	 * @return  Log
	 */
	public function add($level, $message, array $additional=null) {
		// Create a new message and timestamp it
		$this->_messages[] = array (
				'time'  => date(Log::$timestamp, time()),
				'level' => $level,
				'body'  => $message,
				'file'	=> isset($additional['file']) ? $additional['file'] : NULL,
				'line'	=> isset($additional['line']) ? $additional['line'] : NULL,
		);
		if (Log::$writeOnAdd) {
			$this->write();
		}
		return $this;
	}
	
	/**
	 * 记录并清理所有的日志信息
	 *
	 *     $log->write();
	 *
	 * @return  无
	 */
	public function write() {
		if (empty($this->_messages)) {
			// 无日志消息返回
			return;
		}
		// 将消息保存至私有变量中
		$messages = $this->_messages;
		// 清空消息数组
		$this->_messages = array();
		foreach ($this->_writers as $writer) {
			if (empty($writer['levels'])) {
				// 如果该“写日志”对象的levels数组为空，该“写日志”对象记录所有级别的日志
				$writer['object']->write($messages);
			} else {
				// 对消息进行过滤，记录需要改Logwriter记录的日志信息到数组$filtered
				$filtered = array();
				foreach ($messages as $message) {
					if (in_array($message['level'], $writer['levels'])) {
						$filtered[] = $message;
					}
				}
				// 写入过滤后的日志到该Logwriter对象的目录
				$writer['object']->write($filtered);
			}
		}
	}
        public static function test(){
            echo 'phplog/log.php</br>';
        }
}


