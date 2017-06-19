<?php

namespace Bike\Dashboard\Service;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractService
{
    use ContainerAwareTrait;

    /**
     * 只能设置非resource类型
     *
     * 对象会做clone处理，避免因引用传递缓存数据被修改，导致一些很难被发现的bug产生
     */
    private $requestCacheList = array();

    /**
     * 取得请求周期内的缓存
     */
    protected function getRequestCache($key)
    {
        if (isset($this->requestCacheList[$key])) {
            $result = $this->requestCacheList[$key];

            if (is_object($result)) {
                $result = clone $result;
            } elseif (is_resource($result)) {
                $result = null;
            }

            return $result;
        }
    }

    /**
     * 设置请求周期内的缓存
     */
    protected function setRequestCache($key, $value)
    {
        if (is_object($value)) {
            $value = clone $value;
        } elseif (is_resource($value)) {
            $value = null;
        }

        $this->requestCacheList[$key] = $value;

        return $this;
    }

    /**
     * unset掉请求周期缓存
     */
    protected function unsetRequestCache($key)
    {
        unset($this->requestCacheList[$key]);

        return $this;
    }

    protected function clearRequestCache()
    {
        $this->requestCacheList = array();
    }

    protected function getRequestCacheKey($prefix, $value = null)
    {
        if ($value === null) {
            return $prefix;
        }
        if (is_array($value)) {
            $value = implode('.', $value);
        }
        return $prefix . '.' . $value;
    }

    protected function parsePages($totalNum, &$page, &$pageNum)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $pageNum = intval($pageNum);
        if ($totalNum) {
            $totalPage = ceil($totalNum / $pageNum);
            if ($page > $totalPage) {
                $page = $totalPage;
            }
        } else {
            $page = 1;
            $totalPage = 1;
        }
        return [
            'page' => $page,
            'pageNum' => $pageNum,
            'totalPage' => $totalPage,
            'totalNum' => $totalNum,
        ];
    }
}
