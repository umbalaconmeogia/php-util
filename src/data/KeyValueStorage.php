<?php
namespace umbalaconmeogia\phputil\data;

/**
 * Use this class to store data by key, and retrieve it then.
 *
 * This class will do something like this.
 * ```php
 * private $pool = [];
 * function getValue($key)
 * {
 *     if (isset($this->pool[$key])) {
 *         $this->pool[$key] = <Generate the value coresponding to the key>;
 *     }
 *     return $this->pool[$key];
 * }
 * ```
 * But it can work with object, array, and has flexibity with key and value to be stored.
 */
class KeyValueStorage
{
    private $objectPool;

    public function __construct()
    {
        $this->objectPool = [];
    }

    /**
     * Process on a $param object to parse key or value.
     *
     * If $kv is not specified, then the $param is used as $key or $value.
     *
     * If $kv is a Closure (or an array that is considered as Closure), then it is called with parameter is $param.
     *
     * If $param is object then $kv is considered as its attribute, and return that attribute's value.
     *
     * If $param is array, then $kv is $consider as its key, and return that attribute's value.
     * @param mixed $param.
     * @param mixed $kv
     * @return mixed
     */
    private function processKVParam($param, $kv)
    {
        if ($kv === NULL) {
            return $param;
        }

        if ($kv instanceof \Closure) {
            return $kv($param);
        }

        if (is_array($kv)) {
            return call_user_func($kv, $param);
        }

        if (is_object($param)) {
            return $param->$kv;
        }

        if (is_array($param) && array_key_exists($kv, $param)) {
            return $param[$kv];
        }

        return $kv;
    }

    /**
     * @param mixed $param
     * @param \Closure|array|mixed|null $key Specify how to create a key.
     *                         If null, then $param is used as key.
     *                         If is Closure or array, then it is called with $param as parameter.
     *                         If is string, and $param is an array or object, then it is used as attribute to get value from $param.
     * @param \Closure|array|mixed|null $value Specify how to create value to store if it does not exist.
     *                         If null, then $param is used as value.
     *                         If is Closure or array, then it is called with $param as parameter.
     *                         If is string, and $param is an array or object, then it is used as attribute to get value from $param.
     */
    public function getValue($param, $key = NULL, $value = NULL)
    {
        $key = $this->processKVParam($param, $key);

        if (!isset($this->objectPool[$key])) {
            $value = $this->processKVParam($param, $value);
            $this->storeValue($key, $value);
        }
        return $this->objectPool[$key];
    }

    /**
     * Store a $value as $key.
     * @param $key
     * @param $value
     */
    public function storeValue($key, $value)
    {
        $this->objectPool[$key] = $value;
    }

    /**
     * Return all stored objects as an array.
     * The array key is the object identify key.
     * @return array
     */
    public function getAllValues()
    {
        return $this->objectPool;
    }

    public function getKeys()
    {
        return array_keys($this->objectPool);
    }

    public function sortKey()
    {
        ksort($this->objectPool);
    }
}