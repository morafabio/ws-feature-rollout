<?php


namespace Features;


use Predis\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class PercentageFeature extends AbstractFeature implements FeatureInterface
{
    protected $userQuota = 0;

    public function initialize(Session $session)
    {
        if ($session->has('userQuota')) {
            $this->userQuota = $session->get('userQuota');
            return;
        }

        $this->userQuota = mt_rand(0, 100);
    }

    public function matches(Client $redis)
    {
        $threshold = (int) $redis->get(sprintf('%s-threshold', $this->getName()));
        return ($threshold && $this->userQuota > $threshold);
    }

    public function getUserQuota()
    {
        return $this->userQuota;
    }
}

/*
 * SET featureFacebook-threshold 60
 */