<?php
class MCache
{
    protected $_memcache;
    
    public function __construct($servers)
    {
        $this->_memcache = new Memcache;
        foreach($servers as $server)
        {
            $this->_memcache->addServer($server['host'], $server['port'], 1, $server['weight']);
        }
    }

    static public function &instance($servers=NULL)
    {
    	is_null($servers) && $servers = Config::getConfig( 'mc_conf' );
    	
        static $mc = NULL;
        if(!$mc)
        {
            $mc = new MCache($servers);
        }
        return $mc;
    }

    public function get($key)
    {
        return $this->_memcache->get($key);
    }
    
    public function add($key, $value, $expire=0)
    {
        return $this->_memcache->add($key, $value, 0, $expire);
    }
    
    public function set($key, $value, $expire=0)
    {
        return $this->_memcache->set($key, $value, 0, $expire);
    }
    
    public function replace($key, $value, $expire=0)
    {
        return $this->_memcache->replace($key, $value, 0, $expire);
    }
    
    public function delete($key)
    {
        return $this->_memcache->delete($key);
    }
    
    public function flush()
    {
        return $this->_memcache->flush();
    }
    
    public function close()
    {
        return $this->_memcache->close();
    }
    
    public function increment($key, $value=1)
    {
        return $this->_memcache->increment($key, $value);
    }
}
