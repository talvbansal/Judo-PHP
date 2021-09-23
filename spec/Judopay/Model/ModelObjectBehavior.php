<?php

namespace spec\Judopay\Model;

use Judopay\Request;
use spec\SpecHelper;
use PhpSpec\ObjectBehavior;

abstract class ModelObjectBehavior extends ObjectBehavior
{
    protected $configuration;

    public function let()
    {
        $this->configuration = SpecHelper::getConfiguration();
        $this->beConstructedWith(new Request($this->configuration));
    }

    protected function concoctRequest($fixtureFile)
    {
        SpecHelper::getFixtureResponse(200, $fixtureFile);
        
        return new Request($this->configuration);
    }

    public function getMatchers(): array
    {
        return array(
            'contain' => function ($subject, $key) {
                return (strpos($subject, $key) !== false);
            },
        );
    }
}
