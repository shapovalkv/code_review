<?php
$dataUser = Auth::user();
$menus = [
];
$categories = [];


// Modules
$custom_modules = \Modules\ServiceProvider::getModules();
if(!empty($custom_modules)){
    foreach($custom_modules as $module){
        $moduleClass = "\\Modules\\".ucfirst($module)."\\ModuleProvider";
        if(class_exists($moduleClass))
        {
            $menuConfig = call_user_func([$moduleClass,'getUserMenu']);
            if(!empty($menuConfig)){
                $menus = array_merge($menus,$menuConfig);
            }
            $menuSubMenu = call_user_func([$moduleClass,'getUserSubMenu']);
            if(!empty($menuSubMenu)){
                foreach($menuSubMenu as $k=>$submenu){
                    $submenu['id'] = $submenu['id'] ?? '_'.$k;
                    if(!empty($submenu['parent']) and isset($menus[$submenu['parent']])){
                        $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                        $menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                            return $value['position'] ?? 100;
                        }));
                    }
                }
            }
            $cats = call_user_func([$moduleClass,'getUserMenuCategory']);
            if(!empty($cats))
            $categories = array_merge($categories,$cats);
        }
    }
}

// Plugins Menu
$plugins_modules = \Plugins\ServiceProvider::getModules();
if(!empty($plugins_modules)){
    foreach($plugins_modules as $module){
        $moduleClass = "\\Plugins\\".ucfirst($module)."\\ModuleProvider";
        if(class_exists($moduleClass))
        {
            $menuConfig = call_user_func([$moduleClass,'getUserMenu']);
            if(!empty($menuConfig)){
                $menus = array_merge($menus,$menuConfig);
            }
            $menuSubMenu = call_user_func([$moduleClass,'getUserSubMenu']);
            if(!empty($menuSubMenu)){
                foreach($menuSubMenu as $k=>$submenu){
                    $submenu['id'] = $submenu['id'] ?? '_'.$k;
                    if(!empty($submenu['parent']) and isset($menus[$submenu['parent']])){
                        $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                        $menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                            return $value['position'] ?? 100;
                        }));
                    }
                }
            }
        }
    }
}

// Custom Menu
$custom_modules = \Custom\ServiceProvider::getModules();
if(!empty($custom_modules)){
    foreach($custom_modules as $module){
        $moduleClass = "\\Custom\\".ucfirst($module)."\\ModuleProvider";
        if(class_exists($moduleClass))
        {
            $menuConfig = call_user_func([$moduleClass,'getUserMenu']);
            if(!empty($menuConfig)){
                $menus = array_merge($menus,$menuConfig);
            }
            $menuSubMenu = call_user_func([$moduleClass,'getUserSubMenu']);
            if(!empty($menuSubMenu)){
                foreach($menuSubMenu as $k=>$submenu){
                    $submenu['id'] = $submenu['id'] ?? '_'.$k;
                    if(!empty($submenu['parent']) and isset($menus[$submenu['parent']])){
                        $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                        $menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                            return $value['position'] ?? 100;
                        }));
                    }
                }
            }
        }
    }
}

$currentUrl = url(Illuminate\Support\Facades\Route::current()->uri());
if (!empty($menus))
{
    $menus = \Illuminate\Support\Arr::sort($menus, function ($value) {
        return $value['position'] ?? 100;
    });
}
foreach ($menus as $k => $menuItem) {
    if (!empty($menuItem['permission']) and !Auth::user()->hasPermission($menuItem['permission'])) {
        unset($menus[$k]);
        continue;
    }
    $menus[$k]['class'] = ((isset($menu_active) and $menu_active == $k) or ($currentUrl == url($menuItem['url']))) ? 'active' :  '';
    if (!empty($menuItem['children'])) {
        $menus[$k]['class'] .= ' has-children';
        foreach ($menuItem['children'] as $k2 => $menuItem2) {
            if (!empty($menuItem2['permission']) and !Auth::user()->hasPermission($menuItem2['permission'])) {
                unset($menus[$k]['children'][$k2]);
                continue;
            }
            $menus[$k]['children'][$k2]['class'] = $currentUrl == url($menuItem2['url']) ? 'active active_child' : '';
        }
    }
}
?>

<div class="user-sidebar">
    <div class="sidebar-inner">
        <ul class="navigation">
            @foreach($menus as $key => $val)
                <li class="{{ $val['class'] }}">
                    <a href="{{ url($val['url']) }}" class="d-flex justify-content-between align-items-center">
                        <span class="d-flex align-items-center"><i class="{{ $val['icon'] }}"></i> {!! $val['title'] !!}</span>
                        @if(!empty($val['children']))
                        <i class="la la-angle-down mr-0" style="font-size: 18px"></i>
                        @endif
                    </a>
                    @if(!empty($val['children']))
                        <ul class="children pl-4" @if(strpos($val['class'],'active') === false) style="display: none" @endif>
                            @foreach($val['children'] as $menuItem2)
                                <li class="{{$menuItem2['class']}}"><a href="{{ url($menuItem2['url']) }}">
                                        <i class="la la-dot"></i>
                                        {!! clean($menuItem2['title']) !!}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="la la-sign-out"></i>{{__('Logout')}}</a>
            </li>
        </ul>
    </div>
</div>
@push('js')
    <script>
        $('.has-children >a').click(function (){
            $(this).parent().find('>.children').slideToggle('fast');
        })
    </script>
@endpush
