<?php

namespace App\Shared\Interfaces;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;

interface IRepositoryFilter
{
    public function findByFilter($dto);
}
