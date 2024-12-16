<?php

use Illuminate\Support\Facades\Route;

function getCurrentRouteGroup()
{
    $routeName = Route::current()->getName();
    $arr = explode('.', $routeName);
    unset($arr[count($arr) - 1]);
    unset($arr[count($arr) - 1]);
    return implode('.', $arr);
}

function getParentName(){
    $sidebar = collect(config('sidebar'));
    $menu = $sidebar->where('g-name', getCurrentRouteGroup());
    return $menu ? $menu->pluck('name')[0] ?? '' : '';
}

function getParentIcon(){
    $sidebar = collect(config('sidebar'));
    $menu = $sidebar->where('g-name', getCurrentRouteGroup());
    return $menu ? $menu->pluck('icon')[0] ?? '' : '';
}


function getSubMenuName(){
    $sidebar = collect(config('sidebar'));
    $menu = $sidebar->where('sub-menu','!=', null)->firstWhere('g-name', getCurrentRouteGroup());
    if($menu && isset($menu['sub-menu'])){
        $menu = collect($menu['sub-menu'])->firstWhere('r-name', Route::currentRouteName());
        if($menu){
            return $menu['name'] ?? '';
        }
    }
    return '';
}

function getSubMenuItemsFromSidebarConfig(){
    $sidebar = collect(config('sidebar'));
    $menu = $sidebar->where('sub-menu','!=', null)->firstWhere('g-name', getCurrentRouteGroup());
    return $menu['sub-menu'] ?? [];
}
