<?php

namespace spec\Judopay;

use PHPUnit\Framework\Assert;
use spec\SpecHelper;
use PhpSpec\ObjectBehavior;

class RequestSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith(SpecHelper::getConfiguration());
        $this->shouldHaveType('Judopay\Request');
    }

    public function it_sends_the_right_authorization_header_for_oauth2()
    {
        // Add access token to configuration
        $oauthAccessToken = 'xyz';
        $config = SpecHelper::getConfiguration();
        $config->add('oauthAccessToken', $oauthAccessToken);

        // Create a request
        $this->beConstructedWith($config);

        $headers = $this->getHeaders();

        // Make sure the Authorization header is correct
        Assert::assertEquals('Bearer '.$oauthAccessToken, $headers->getWrappedObject()['Authorization']);
    }

    public function getMatchers(): array
    {
        return array(
            'startWith' => function ($subject, $key) {
                return (stripos(trim($subject), $key) === 0);
            },
        );
    }
}
