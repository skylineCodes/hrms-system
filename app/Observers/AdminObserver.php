<?php

namespace App\Observers;

use App\Model\Admin\Admin;

class AdminObserver
{
    /**
     * Handle the Admin "creating" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function creating(Admin $admin)
    {
        if (isset($admin->region))
        {
            $admin->employee_code = $this->generatePIN('AC', $admin->region);
        }
    }

    /**
     * Handle the Admin "updating" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function updating(Admin $admin)
    {
        //
    }

    /**
     * Handle the Admin "deleting" event.
     *
     * @param  \App\Admin  $admin
     * @return void
     */
    public function deleting(Admin $admin)
    {
        //
    }

    public function generatePIN($prefix, $region, $digits = 4){
        $i = 0;
        $pin = "";
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin = $prefix.$region.mt_rand(1000,9999);
            $i++;
        }
        return $pin;
    }

}
