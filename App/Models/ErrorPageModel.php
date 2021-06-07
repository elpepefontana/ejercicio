<?php 

namespace App\Models;

defined('BASEPATH') or exit('No se permite acceso directo');

use App\Models\Entities as Entity;
use App\Models\Mappers as Mapper;
use System\Core\Session as Session;
use System\Core\QueryBuilder as QueryBuilder;

class ErrorPageModel extends \System\Core\AbstractModel
{
    protected $session;

    public function __construct(
        Entity\ErrorPageEntity $entity,
        Mapper\ErrorPageMapper $mapper,
        Session $session,
        QueryBuilder $queryBuilder
    ) {
        parent::__construct($entity, $mapper, $session, $queryBuilder);
    }
}
