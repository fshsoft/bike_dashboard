<?php

namespace Bike\Dashboard\Controller\Bike;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;
use Bike\Dashboard\Db\Dashboard\AbstractDao;

/**
 * @Route("/bike")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="bike")
     * @Template("BikeDashboardBundle:bike/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $args = $request->query->all();
        $bikeService = $this->get('bike.dashboard.service.bike');
        $page = $request->query->get('p');
        $pageNum = 10;
        //print_r($bikeService->searchBike($args, $page, $pageNum));die;        
        return $bikeService->searchBike($args, $page, $pageNum);
    }

    /**
     * @Route("/new", name="bike_new")
     * @Template("BikeDashboardBundle:bike/index:new.html.twig")
     */
    public function newbikeAction(Request $request)
    {
        $userDao = $this->container->get('bike.dashboard.dao.primary.user');
        $userList = $userDao->findList('*', array(), 0, 0);
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            $bikeService = $this->get('bike.dashboard.service.bike');
            try {
                $bikeService->createBike($data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        }
        return array(
            'userlist' => $userList
        );
    }


}
