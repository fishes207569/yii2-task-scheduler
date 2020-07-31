<?php

namespace ccheng\task\common\helpers;

use ccheng\task\common\consts\TaskConst;
use common\enums\RedisEnum;
use Yii;

class RedisLock
{
    /**
     * @var string 前缀
     */
    private $prefix = 'cms_redis_lock:';

    /**
     * redis组件
     * @var yii\redis\Connection
     */
    private $redis;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $token;

    /**
     * RedisLock constructor.
     * @param $key
     */
    public function __construct(string $key)
    {
        $this->key = $this->prefix . $key;
        $this->redis = Yii::$app->redis;
        $this->redis->select(RedisEnum::DATABASE_BUSINESS);
        $this->token = uniqid(mt_rand(), true);
    }

    /**
     * 加锁
     * @param int $ttl
     * @return mixed
     */
    public function lock(int $ttl)
    {
        return $this->redis->set($this->key, $this->token, 'nx', 'ex', $ttl);
    }

    /**
     * 加锁（多次重试）
     * @param int $ttl
     * @param int $lock_count
     * @return bool
     */
    public function repeatLock(int $ttl, int $lock_count)
    {
        while ($lock_count) {
            if (!$this->lock($ttl)) {
                $lock_count--;
                sleep(1);
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * 解锁
     * @return mixed
     */
    public function unlock()
    {
        $this->redis->select(RedisEnum::DATABASE_BUSINESS);
        $script = <<<'LUA'
if redis.call("get",KEYS[1]) == ARGV[1] then
    return redis.call("del",KEYS[1])
else
    return 0
end
LUA;
        return $this->redis->eval($script, 1, $this->key, $this->token);
    }
}