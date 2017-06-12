<?php

namespace Bike\Dashboard\Service;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Exception\Logic\LogicException;
use Bike\Dashboard\Service\AbstractService;
use Bike\Dashboard\Util\ArgUtil;
use Bike\Dashboard\Db\Primary\Bike as PrimaryBike;
use Bike\Dashboard\Db\Partner\Bike as PartnerBike;
use Bike\Dashboard\Db\Primary\BikeIdGenrator;
use Bike\Dashboard\Db\Primary\Admin;

class BikeService extends AbstractService
{
    public function createBike(array $data)
    {

        $primaryBikeDao = $this->getPrimaryBikeDao();
        $primaryBikeConn = $primaryBikeDao->getConn();
        $partnerBikeDao = $this->getPartnerBikeDao();
        $partnerBikeConn = $partnerBikeDao->getConn();
        $bikeIdGeneratorDao = $this->getBikeIdGeneratorDao();
        $bikeIdGeneratorConn = $bikeIdGeneratorDao->getConn();
        $primaryBikeConn->beginTransaction();
        $partnerBikeConn->beginTransaction();
        $bikeIdGeneratorConn->beginTransaction();
        try {
            $id = rand(10000000,99999999);
            $primaryBike = new PrimaryBike();
            $time = time();
            $primaryBike
                ->setId($id)
                ->setUser_id($data['user_id'])
                ->setElock_id('1')
                ->setLng($data['lng'])
                ->setLat($data['lat'])
                ->setCreate_time($time);
            $primaryBikeDao->create($primaryBike);
            $partnerBike = new PartnerBike();
            $partnerBike
                ->setId($id)
                ->setCreate_ime($time);
            $partnerBikeDao->create($partnerBike);

            $primaryBikeConn->commit();
            $partnerBikeConn->commit();
            $bikeIdGeneratorConn->commit();
        } catch (\Exception $e) {
            $primaryBikeConn->rollBack();
            $partnerBikeConn->rollBack();
            $bikeIdGeneratorConn->rollBack();
            throw $e;
        }
    }

    public function searchBike(array $args, $page, $pageNum)
    {
        $page = intval($page);
        $pageNum = intval($pageNum);
        if ($page < 1) {
            $page = 1;
        }
        if ($pageNum < 1) {
            $pageNum = 1;
        }
        $offset = ($page - 1) * $pageNum;
        $bikeDao = $this->getPrimaryBikeDao();
        $bikeList = $bikeDao->findList('*', $args, $offset, $pageNum, array(
            'id' => 'desc',
        ));
        $total = $bikeDao->findNum($args);
        if ($total) {
            $totalPage = ceil($total / $pageNum);
            if ($page > $totalPage) {
                $page = $totalPage;
            }
        } else {
            $totalPage = 1;
            $page = 1;
        }
        return array(
            'page' => $page,
            'totalPage' => $totalPage,
            'pageNum' => $pageNum,
            'total' => $total,
            'list' => array(
                'bike' => $bikeList,
            ),
        );
    }

    public function getBikeById($id)
    {
        $key = 'bike.id.' . $id;
        $bike = $this->getRequestCache($key);
        if (!$bike) {
            $bikeDao = $this->getPrimaryBikeDao();
            $bike = $bikeDao->find($id);
            if ($bike) {
                $this->setRequestCache($key, $bike);
            }
        }
        return $bike;
    }

    protected function generateBikeId()
    {
        $bikeIdGeneratorDao = $this->getBikeIdGeneratorDao();
        return $bikeIdGeneratorDao->save(array(), true);
    }

    protected function getPartnerBikeDao()
    {
        return $this->container->get('bike.dashboard.dao.partner.bike');
    }

    protected function getPrimaryBikeDao()
    {
        return $this->container->get('bike.dashboard.dao.primary.bike');
    }

    protected function getBikeIdGeneratorDao()
    {
        return $this->container->get('bike.dashboard.dao.primary.bikeidgenerator');
    }

}
 