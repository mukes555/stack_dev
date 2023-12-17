<?php

namespace Razorpay\Api;

class Plan extends Entity
{
    public function create($attributes = array())
    {
        $attributes = json_encode($attributes);

        Request::addHeader('Content-Type', 'application/json');
        
        return parent::create($attributes);
    }

    public function fetch($id)
    {
        return parent::fetch($id);
    }

    public function all($options = array())
    {
        return parent::all($options);
    }
}