<?php

namespace Forum\Classes\Soap;

class Server
{
    public function __construct()
    {
        //dd('what the hell yall');
    }
    public function getDate()
    {
        //return {200,'nothing to show'};//['status'=>200];
        return date('Y-m-d');
    }
    public function update_user()
    {
        return ['item'=>'good'];
    }
}
