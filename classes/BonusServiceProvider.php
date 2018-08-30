<?php

namespace Ecjia\App\Bonus;

use Royalcms\Component\App\AppParentServiceProvider;

class BonusServiceProvider extends  AppParentServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-bonus', null, dirname(__DIR__));
    }
    
    public function register()
    {
        
    }
    
    
    
}