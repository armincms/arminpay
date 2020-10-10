<?php
namespace Component\Arminpay;

use Illuminate\Support\ServiceProvider;      

class ArminpayInstaller  
{     
    /**
     * Your component installation.
     * 
     * @return void
     */
    public function install()
    {        
        \Artisan::call('migrate', [
            '--path'    => __DIR__.'/database/migrations',
            '--realpath'=> true,
        ]); 
    } 

    /**
     * Your uninstaller.
     *
     * @return void
     */
    public function uninstall()
    {     
    } 
}
