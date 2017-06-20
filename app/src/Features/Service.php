<?php

namespace Features;

use Predis\Client;

class Service
{
    protected $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function isActive(FeatureInterface $feature)
    {
        if ($feature instanceof SwitchFeature) {
            return $this->redis->get($feature->getName());
        }

        if ($feature instanceof ConditionFeature) {
            return $feature->matches();
        }

        if ($feature instanceof PercentageFeature) {
            return $feature->matches($this->redis);
        }

        return false;
    }
}