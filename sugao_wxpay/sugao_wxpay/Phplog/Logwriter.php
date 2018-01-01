<?php
namespace sugao_wxpay\Phplog;
use Exception;
/**
 * 系统日志记录执行记录操作的类库，并把他们存储为/YYYY/MM/DD.php格式
 */
class Logwriter {
	const FILE_EXT = '.php';
	//安全信息，用于获取日志时的验证（我这里是“欢迎来到日志的世界”）
	const FILE_SECURITY = '<?php ($_GET[\'p\'] && md5($_GET[\'p\']) == \'8b812956650da4ed2199a4463f55194b\') or die(\'No direct script access.\');';
	/**
	 * @var  string  保存日志的目录
	 */
	protected $_directory;

	/**
	 * 创建一个新的日志写操作实例
	 *
	 *     $writer = new Logwriter($directory);
	 *
	 * @param   string  当前实例存储日志的目录
	 * @return  无
	 */
	public function __construct($directory) {
		if (!is_dir($directory) || !is_writable($directory)) {
			try{
				mkdir($directory,true);
				chmod($directory, 0777);
			}catch(Exception $e){

			}
		}
		// 将保存日志的目录路径放入对象环境中
		$this->_directory = realpath($directory).DIRECTORY_SEPARATOR;
	}

	/**
	 * 将messages数组中的每一组日志信息存储到文件中，格式为/YYYY/MM/DD.php 
	 * example:2014/11/18.php 表示2014年11月18日的日志文件
	 *
	 *     $writer->write($messages);
	 *
	 * @param   array   要保存的日志信息
	 * @return  void
	 */
	public function write(array $messages) {
		// “年”这一级目录
		$directory = $this->_directory.date('Y');
		if ( ! is_dir($directory)) {
			// 如果“年”级目录不存在，创建
			mkdir($directory, 02777);
			// 设置目录权限(must be manually set to fix umask issues)
			chmod($directory, 02777);
		}

		// “月”这一级目录
		$directory .= DIRECTORY_SEPARATOR.date('m');
		if ( ! is_dir($directory)) {
			// 如果“月”级目录不存在，创建
			mkdir($directory, 02777);
			// 设置权限 (must be manually set to fix umask issues)
			chmod($directory, 02777);
		}

		// 要写入的文件
		$filename = $directory.DIRECTORY_SEPARATOR.date('d').self::FILE_EXT;
		if ( ! file_exists($filename)) {
			// 如果不存在日志文件，创建，并在记录日志开始写入安全验证程序
			file_put_contents($filename, self::FILE_SECURITY.' ?>'.PHP_EOL);
			// 设置文件权限为所有用户可读可写
			chmod($filename, 0666);
		}

		foreach ($messages as $message) {
			// 循环日志写信数组，写入每一条日志
			file_put_contents($filename, PHP_EOL.$message['time'].' --- '.$message['level'].': '.$message['body'].'		[at file]:'.$message['file'].'		[at line]:'.$message['line'], FILE_APPEND);
		}
	}

	/** 
	 * 魔术方法，生成对象的唯一标识
	 *
	 * @return void
	 */
	public function __toString() {
		return spl_object_hash($this);
	}
}

