<?php
namespace Tshmop;

use yii\base\Exception;

class Tshmop{

    protected $id;

    protected $shmId;

    protected $dataSize;

    protected $permission = 0755;

    protected $config = [];

    /**
     * load config  merge with this config
     * Tshmop constructor.
     * @param array $config
     */
    public function __construct($config = []){
        if($config){
            $this->config = array_merge($this->config, $config);
        }

        $this->init();
    }

    /**
     * init of shmop
     * check used with id
     */
    public function init(){
        if(isset($this->config['id'])){
            $this->id = $this->config['id'];
        }else{
            $this->generateId();
        }

        if(isset($this->config['permission'])){
            $this->permission = $this->config['permission'];
        }

        // check  this id used
        $this->checkShmopArea();
    }

    /**
     * $this->shmId
     * if used  shmId != false
     */
    public function checkShmopArea(){
        $this->shmId = @shmop_open($this->id, 'w', 0, 0);
    }

    /**
     * write date to shmop
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function put($data){
        if(!$data){
            throw new \Exception('put data must has something!');
        }

        if(is_array($data)){
            $data = json_encode($data);
        }

        $dataSize = mb_strlen($data, 'utf-8');

        if($this->shmId){
            // this memory area used
            $this->clean();
            $this->close();
        }

        $this->shmId = shmop_open($this->id, "c", $this->permission, $dataSize);

        shmop_write($this->shmId, $data, 0);

        return true;
    }

    /**
     * get data
     * @return string
     * @throws \Exception
     */
    public function get(){
        if(!$this->shmId){
            throw new \Exception('connot get data!');
        }

        $size = shmop_size($this->shmId);

        $data = shmop_read($this->shmId, 0, $size);

        return $data;
    }

    /**
     * clean data
     */
    public function clean(){
        shmop_delete($this->shmId);
    }

    /**
     * close shmop
     */
    public function close(){
        shmop_close($this->shmId);
    }

    /**
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * use ftok get a number
     */
    protected function generateId(){
        $id = ftok(__FILE__, 'b');

        $this->id = $id;
    }

    public function __destruct(){
        // TODO...
    }
}