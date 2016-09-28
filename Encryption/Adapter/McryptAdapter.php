<?php

namespace Ibrows\Bundle\NewsletterBundle\Encryption\Adapter;

class McryptAdapter extends AbstractAdapter
{

    /**
     * @var string
     */
    protected $cypher;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var string
     */
    protected $iv;

    /**
     * @param string $cypher
     * @param string $mode
     * @param int    $ivMode
     */
    public function __construct($cypher = MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_ECB, $ivMode = MCRYPT_RAND)
    {
        $this->cypher = $cypher;
        $this->mode = $mode;

        $ivSize = mcrypt_get_iv_size($cypher, $mode);
        $this->iv = mcrypt_create_iv($ivSize, $ivMode);
    }

    /**
     * @param  string $data
     * @param  string $key
     * @return string
     */
    public function encrypt($data, $key)
    {
        // strip key to max length
        $key = substr($key, 0, 32);

        return mcrypt_encrypt($this->cypher, $key, $data, MCRYPT_MODE_ECB, $this->iv);
    }

    /**
     * @param  string $data
     * @param  string $key
     * @return string
     */
    public function decrypt($data, $key)
    {
        // strip key to max length
        $key = substr($key, 0, 32);

        return trim(mcrypt_decrypt($this->cypher, $key, $data, MCRYPT_MODE_ECB, $this->iv));
    }

}
