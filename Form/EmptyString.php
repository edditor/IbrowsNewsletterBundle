<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

/**
 * EmptyString
 * Workaround for
 * [Form] Setting "empty_data" to "" still results in NULL
 * https://github.com/symfony/symfony/issues/5906
 * http://stackoverflow.com/questions/9928702/symfony2-forms-interpret-blank-strings-as-nulls
 */
class EmptyString
{
    public function __toString()
    {
        return '';
    }
}
