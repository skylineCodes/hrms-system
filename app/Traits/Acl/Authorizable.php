<?php

namespace App\Traits\Acl;

trait Authorizable
{
    private $abilities = [
        'index' => 'read',
        'show' => 'read',
        'update' => 'update',
        'store' => 'create',
        'destroy' => 'delete'
    ];

    /**
     * Override of callAction to perform the authorization before
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        if( $ability = $this->getAbility($method) ) {
            $this->authorize($ability);
        }

        return parent::callAction($method, $parameters);
    }

    
    public function getAbility($method)
    {
        $routeName = explode('.', \Request::route()->getName());
        $action = array_get($this->getAbilities(), $method);

        return $action ? $action . '_' . $routeName[0] : null;
    }

    
    private function getAbilities()
    {
        return $this->abilities;
    }

    public function setAbilities($abilities)
    {
        $this->abilities = $abilities;
    }
}