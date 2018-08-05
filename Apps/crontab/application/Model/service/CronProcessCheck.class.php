<?php

/**
 * WEB后台 Controller父类 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: CronProcessCheck.class.php 588 2016-11-10 10:40:36Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace crontab\service;

class CronProcessCheck
{

    /**
     * Worker 进程的名称
     *
     * @var string
     */
    protected $name = '';

    /**
     * 此 Worker 允许的最大活动实例数量
     *
     * @var int
     */
    protected $maxInstances = 1;

    /**
     * Worker 进程的 ID
     *
     * @var int
     */
    protected $pid = null;

    /**
     * Worker 进程 PID 文件名
     *
     * @var string
     */
    protected $pidFile = '';

    function setMaxInstances( $maxInstances )
    {
        $this->maxInstances = $maxInstances;
    }

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {
        
    }

    /**
     * Worker 执行结束
     *
     * @param boolean $unexpect
     *            是否为意料外的退出
     */
    public function terminate( $unexpect = false )
    {
        if ( !$unexpect ) {
            // 删除 PID 文件
            $pid_file = $this->getPidFilename();
            @unlink( $pid_file );
        }
        /**
          // 调用终止回调
          $this->onTerminate();
          // 进程控制日志写入
          $log = array(
          'date' => date( 'Y-m-d H:i:s' ),
          'text' => join( "\n", $this->outputs )
          );
          $this->procControl->writeProcessLog( json_encode( $log ) );
          // 进程控制结束
          $this->procControl->terminate();
          // 退出
          exit( $this->exitcode );
         * 
         */
        exit();
    }

    private function output( $msg )
    {
        if ( empty( $msg ) ) {
            return false;
        }
        echo date( 'Y-m-d H:i:s' ) . $msg . "\r\n";
        ob_flush();
    }

    /**
     * Worker 启动
     * 检测进程
     */
    public function bootstrap()
    {
        // 创建进程 PID 文件，如果无法获取到，则说明达到最大进程数了
        $pid_file = $this->getPidFilename();
        if ( $pid_file === false ) {            
            //如果是达到了最大进程数，则退出
            $this->output( sprintf( '当前系统中，已经有不止一个 %s 正在运行，退出...', $this->name ) );
            $this->terminate( true );
            return false;
        }
        // 将自己的 PID 写入 PID 文件
        file_put_contents( $pid_file, $this->pid );
        /*
         * 可以用来做redis后台控制
          // 进程获取到暂停命令，则退出
          $command = $this->procControl->getCommand();
          if ( 'pause' == $command ) {
          $this->verbose( "接收到停止命令，退出..." );
          //写入日志
          $this->terminate( true );
          }
          // 启动回调
          $this->onBootstrap();
         * 
         */
        return true;
    }

    /**
     * 获取 PID 文件名
     *
     * @return string|false 获取到的 PID 文件名。如果返回 false 代表达到此 Worker 进程的最大活动实例数了
     */
    protected function getPidFilename()
    {
        // 获取进程 pid
        if ( $this->pid === null ) {

            if ( function_exists( 'posix_getpid' ) ) {
                $this->pid = posix_getpid();
            } else {
                $this->pid = getmypid();
            }

            // 获取当前工作进程的名称
            $worker_name = $this->getWorkerName();

            // 获取系统临时目录，PID 文件存储在此处
            // 文件名类似 /tmp/abc.1.pid
            $sys_tmpdir = sys_get_temp_dir();
            $filename_tpl = "{$sys_tmpdir}/{$worker_name}.%d.pid";

            // 找到可用文件名
            $max = $this->maxInstances;
            for ( $i = 0; $i < $max; $i ++ ) {
                $filename = sprintf( $filename_tpl, $i );

                // 如果文件存在，则需要判断对应的进程是否还存在
                if ( file_exists( $filename ) ) {
                    // 进程还存在，则继续
                    $pid = file_get_contents( $filename );
                    if ( !empty( $pid ) && file_exists( "/proc/{$pid}" ) ) {
                        continue;
                    } else {
                        $this->pidFile = $filename;
                        break;
                    }
                } else {
                    // 不存在则直接使用此名
                    $this->pidFile = $filename;
                    break;
                }
            }

            if ( empty( $this->pidFile ) ) {
                $this->pidFile = false;
            }
        }

        return $this->pidFile;
    }

    /**
     * 获取当前工作进程名称
     *
     * @return string 工作进程名称
     */
    public function getWorkerName()
    {
        // 如果 Worker 没有命名，则使用类型名命名
        if ( empty( $this->name ) ) {
            $this->name = $_GET[ 'TMAC_CONTROLLER' ] . '.' . $_GET[ 'TMAC_ACTION' ];
        }

        return $this->name;
    }

}
