<?php
namespace Application\Paginator\Adapter;

use Zend\Db\Sql\Where;
use Zend\Db\Sql\Having;
use Zend\Db\TableGateway\TableGateway;

class DbTableGateway extends DbSelect
{
    /**
     * Constructs instance.
     *
     * @param TableGateway                      $tableGateway
     * @param null|Where|\Closure|string|array  $where
     * @param null|string|array                 $order
     * @param null|string|array                 $group
     * @param null|Having|\Closure|string|array $having
     */
    public function __construct(TableGateway $tableGateway, $where = null, $order = null, $group = null, $having = null)
    {
        $sql    = $tableGateway->getSql();
        $select = $sql->select();
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        }
        if ($group) {
            $select->group($group);
        }
        if ($having) {
            $select->having($having);
        }

        $resultSetPrototype = $tableGateway->getResultSetPrototype();
        parent::__construct($select, $sql, $resultSetPrototype);
    }
}
