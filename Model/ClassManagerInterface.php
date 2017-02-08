<?php
namespace Ibrows\Bundle\NewsletterBundle\Model;

interface ClassManagerInterface
{
    public function getModel($name);
    public function getForm($name);
}
