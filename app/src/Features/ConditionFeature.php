<?php


namespace Features;


class ConditionFeature extends AbstractFeature implements FeatureInterface
{
    protected $conditionFn;

    public function __construct($name, callable $conditionFn)
    {
        parent::__construct($name);
        $this->conditionFn = $conditionFn;
    }

    public function matches()
    {
        $fn = $this->conditionFn;
        return $fn();
    }
}

/*
 * http://localhost:8888/index_dev.php?featureFacebook=0
 * http://localhost:8888/index_dev.php?featureFacebook=1
 */