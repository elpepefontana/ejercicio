<?php 
namespace App\Models\Mappers;

use System\Storage\StorageInterface as StorageInterface;
use System\Core\Session as Session;
use System\Core\QueryBuilder as QueryBuilder;

class LogMapper extends \App\Models\Mappers\Base\LogBaseMapper
{
    protected $session;

    public function __construct(StorageInterface $storage, Session $session, QueryBuilder $queryBuilder)
    {
        parent::__construct($storage, $session, $queryBuilder);
        $this->session = $session;
    }

}
