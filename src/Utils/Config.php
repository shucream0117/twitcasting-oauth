<?php

namespace Shucream0117\TwitCastingOAuth\Utils;

/**
 * wrapper of \Noodlehaus\Config
 */
class Config
{
    const DEFAULT_CONF_PATH = __DIR__ . '/../../config/config.php';

    /** @var \Noodlehaus\Config */
    protected $config;

    const REQUIRED_FIELDS = [
        'client_id', 'client_secret', 'callback_url',
    ];

    public function __construct(string $path = self::DEFAULT_CONF_PATH)
    {
        $config = \Noodlehaus\Config::load($path);
        foreach (static::REQUIRED_FIELDS as $filedName) {
            if (is_null($config->get($filedName))) {
                throw new \Exception("`{$filedName}` field is required");
            }
        }
        $this->config = $config;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }
}
