<?php

namespace SsGroup\MkCrud\Repository;


use App\User;

class UserRepository extends Repository
{
    function getModel()
    {
        User::class;
    }
}