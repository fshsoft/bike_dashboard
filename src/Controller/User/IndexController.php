<?php

namespace Bike\Dashboard\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;
use Bike\Dashboard\Db\Primary\User as PrimaryUser;

/**
 * @Route("/user")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="user_list")
     * @Template("BikeDashboardBundle:user/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
    	$userService = $this->get('bike.dashboard.service.user');
        $page = $request->query->get('p');
        $pageNum = 10;
        $args = $request->query->all();
        $user = $this->getUser();
        return $userService->searchUser($args, $page, $pageNum);
    }

    /**
     * @Route("/edit/{id}", name="user_edit")
     * @Template("BikeDashboardBundle:user/index:edit.html.twig")
     */
    public function editAction(Request $request,$id)
    {
        $userService = $this->get('bike.dashboard.service.user');
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            try {
                $userService->editUser($id,$data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        } else {
            $user = $userService->getUser($id);
            return ['user'=>$user];
        }
        return array();
    } 


}
