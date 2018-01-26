<?php

/**
 *      [CodeZm!] Author CodeZm[codezm@163.com].
 *
 *      Detect.
 *      $Id: detect.php 2018-01-25 10:04:06 codezm $
 */

class Detect {

    // Service Config.
    private $serviceConfig = array(
        'mysql' => array(
            'dbtype' => 'mysql', 
            'dbname' => 'mysql', 
            'host' => 'mysql', 
            'username' => 'root', 
            'password' => '1234', 
            'charset' => 'UTF8', 
        ), 
        'redis' => array(
            'host' => 'redis', 
            'port' => '6379', 
            'db' => 1
        ), 
        'memcached' => array(
            'host' => 'memcached', 
            'port' => '11211', 
            'weight' => 100
        )
    );
    public function __construct() {
        $this->mysql();
        $this->redis();
        $this->memcached();
        $this->curl('http://websocket.local.com');
    }

    public function mysql() {
        echo 'Detect Mysql Connect: ' . PHP_EOL;
        try {
            $config = $this->serviceConfig['mysql'];
            $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
            $database = new PDO($config['dbtype'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password'], $options);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $database->exec('SET CHARACTER SET ' . $config['charset']);
        } catch (PDOException $e) {
            echo 'Mysql Connect Failed.' . PHP_EOL;
            //return [
                //'code' => $e->getCode(), 
                //'message' => $e->getMessage(), 
            //];
            echo '<pre>'; var_dump([
                'code' => $e->getCode(), 
                'message' => $e->getMessage(), 
            ]); echo '</pre>';

            return false;
        }

        echo 'Mysql Connect Successful.' . PHP_EOL;
        echo '<br>';
        //$data = $database->exec('SHOW STATUS;');
        //return $data;
        //$data = $database->exec('');
        //$result = $database->prepare('select user,host from user');
        //$result->execute();
        //$data = $result->fetchAll(PDO::FETCH_ASSOC);
        //$result->closeCursor();

        //echo '<pre>'; var_dump($data); echo '</pre>';;
    }

    public function redis() {
        echo 'Detect Redis Server: ' . PHP_EOL;
        $config = $this->serviceConfig['redis'];
        $redis = new Redis();
        $redis->connect($config['host'], $config['port']);
        $redis->select($config['db']);
        echo 'Server is running: ' . $redis->ping() . PHP_EOL;
        echo '<br>';
    }

    public function memcached() {
        echo 'Detect Memcached Server: ' . PHP_EOL;
        $config = $this->serviceConfig['memcached'];
        $memcached = new Memcached();
        $memcached->addServer($config['host'], $config['port'], $config['weight']);

        echo '[Set key: test, value: Hello world, expiration: 60]' . PHP_EOL;
        $memcached->set('test', 'Hello world', 60);
        echo '[Get test value is: ' . $memcached->get('test') . ']' . PHP_EOL;
        echo '<br>';
    }

    public function curl($url, $arr = array()) {
        echo 'Detect Curl \'' . $url . '\': ' . PHP_EOL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);

        echo 'Curl result is: ' . $result . PHP_EOL;
        echo '<br>';
    }
}
new Detect();
