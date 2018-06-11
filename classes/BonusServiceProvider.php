<?php

namespace Ecjia\App\Bonus;

use Royalcms\Component\App\AppServiceProvider;

class BonusServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-bonus');
    }
    
    public function register()
    {
        
    }
    
    
    
}