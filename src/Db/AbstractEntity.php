<?php

namespace Bike\Dashboard\Db;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Util\NamingUtil;

/**
 * 暂时先试用魔术方法来实现getter和setter，后续使用代码生成器
 */
abstract class AbstractEntity implements EntityInterface
{
    protected $data = array();

    /**
     * 主键
     *
     * 最贱实践中，主键只能是单字段+数字类型
     * 约定优于配置
     */
    protected static $pk;

    /**
     * 必须把所有表字段都写在这里，修改表结构，也请更新此变量
     *
     * @var array
     *
     * 格式
     * column_name => default_value
     *
     * 如果使用数据库表中该字段设置的默认值，请把default_value设为null
     *
     * 确保字段中没有mysql保留字，因为dao层中没有做反引号操作
     * 约定优于配置
     *
     */
    protected static $cols = array();

    /**
     * 数据库字段别名，映射关系如下
     *
     * alias => real
     */
    protected static $colAliasMap = array();

    public function __construct(array $data = array())
    {
        if ($data) {
            $this->fromArray($data);
        }
    }

    public function clear()
    {
        $this->data = array();
    }

    protected function getCol($name)
    {
        $name = self::getColAlias($name);

        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    protected function setCol($name, $value)
    {
        $name = static::getColAlias($name);

        // 不能用isset，因为值可能是null
        if (array_key_exists($name, static::$cols)) {
            $this->data[$name] = $value;
        }

        return $this;
    }

    /**
     * 不存在alias，就返回自己
     */
    protected static function getColAlias($name)
    {
        // 不能用isset，先检查alias
        if (isset(static::$colAliasMap[$name])) {
            $name = static::$colAliasMap[$name];
        }

        return $name;
    }
    
    public function fromArray(array $data)
    {
        $this->clear();

        foreach ($data as $k => $v) {
            $this->setCol($k, $v);
        }
    }

    public function toArray()
    {
        return $this->data;
    }

    public function toArrayForInsert()
    {
        $result = array();
        foreach (static::$cols as $k => $v) {
            if (isset($this->data[$k])) { // 优先data中的非null值
                $result[$k] = $this->data[$k];
            } elseif ($v !== null) { // cols中的非null值，程序默认值
                $result[$k] = $v;
            }
        }

        return $result;
    }

    public function toArrayForUpdate()
    {
        $result = array();
        foreach ($this->data as $k => $v) {
            if ($v !== null) { // 只取data中的非null值
                $result[$k] = $v;
            }
        }

        return $result;
    }

    public static function getPrimaryKey()
    {
        return static::$pk;
    }

    public function getPrimaryValue()
    {
        return $this->getCol(static::$pk);
    }

    /**
     * @todo 方法和字段的映射缓存
     */
    public function __call($method, $args)
    {
        if (strpos($method, 'get') !== 0 && strpos($method, 'set') !== 0) {
            throw new DebugException(sprintf('不支持%s方法,只支持get和set方法', $method));
        }

        $func = substr($method, 0, 3) . 'Col';
        $col = NamingUtil::studlyCaseToUnderscore(substr($method, 3));

        if (!is_array($args)) {
            $args = array();
        }
        array_unshift($args, $col);

        return call_user_func_array(array($this, $func), $args);
    }
}
