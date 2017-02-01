<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Subscriber;

interface GroupManagerInterface
{
    public function get($id);
    public function findOneBy(array $criteria);
    public function findBy(array $criteria, array $orderBy = array(), $limit = null, $offset = null);
    public function create();
}
