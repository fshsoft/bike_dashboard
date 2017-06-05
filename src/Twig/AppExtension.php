<?php

namespace Bike\Dashboard\Twig;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('bike_dashboard_paginate', array($this, 'paginate')),
        );
    }

    public function paginate($currentPage, $totalPage, $middleSize = 5)
    {
        $firstPage = false;
        $prevPage = false;
        if ($currentPage > 1) {
            $firstPage = 1;
            $prevPage = $currentPage - 1;
        } 

        $lastPage = false;
        $nextPage = false;
        if ($currentPage < $totalPage) {
            $lastPage = $totalPage;
            $nextPage = $currentPage + 1;
        }

        $half = floor($middleSize / 2);
        $start = $currentPage - $half;
        $end = $start + $middleSize - 1;
        if ($end > $totalPage) {
            $end = $totalPage;
            $start = $end - $middleSize + 1;
        } elseif ($start < 1) {
            $start = 1;
            $end = $start + $middleSize - 1;
        }

        if ($start < 1) {
            $start = 1;
        }

        if ($end > $totalPage) {
            $end = $totalPage;
        }

        for ($i = $start; $i <= $end; $i++) {
            $middles[] = $i;
        }

        return array(
            'first' => $firstPage,
            'prev' => $prevPage,
            'middles' => $middles,
            'next' => $nextPage,
            'last' => $lastPage, 
        );
    }
}
 
