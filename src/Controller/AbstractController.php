<?php

namespace Bike\Dashboard\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bike\Common\Exception\Logic\LogicExceptionInterface;
use Bike\Common\Error\ErrorCode;

abstract class AbstractController extends Controller
{
    protected function jsonSuccess($data = null)
    {
        $result = array(
            'errno' => ErrorCode::SUCCESS,
            'errmsg' => '',
        );
        if ($data !== null) {
            $result['data'] = $data;
        }
        return $this->json($result);
    }

    protected function jsonError($errno, $defaultErrmsg = null, $data = null)
    {
        if ($errno instanceof LogicExceptionInterface) {
            $result = array(
                'errno' => $errno->getCode(),
                'errmsg' => $errno->getMessage(),
            );
        } else {
            $errmsg = '出错了';
            if ($defaultErrmsg) {
                $errmsg = $defaultErrmsg;
            }
            $result = array(
                'errno' => ErrorCode::LOGIC_ERROR,
                'errmsg' => $errmsg,
            );
        }

        if ($data !== null) {
            $result['data'] = $data;
        }

        return $this->json($result);
    }
}
