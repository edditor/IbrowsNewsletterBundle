<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Newsletter;

interface SendSettingsManagerInterface
{
    public function get($id);
    public function findOneBy(array $criteria);
    public function findBy(array $criteria, array $orderBy = array(), $limit = null, $offset = null);
    public function create();
}
