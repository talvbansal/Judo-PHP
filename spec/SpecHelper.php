<?php

namespace spec;

use Judopay\Configuration;
use Illuminate\Http\Client\Factory;

/*
 * Test the client without sending the requests
 */
class SpecHelper
{
    /**
     * Returns a mock configuration
     */
    public static function getConfiguration()
    {
        $configuration = new Configuration(
            array(
                'apiToken'  => 'token',
                'apiSecret' => 'secret',
                'judoId'    => '123-456',
            )
        );

        return $configuration;
    }

    public static function getBodyResponse($responseCode, $responseBody = [])
    {
        return Factory::response($responseBody, $responseCode);
    }

    public static function getFixtureResponse($responseCode, $fixtureFile = null)
    {
        $fixtureContent = '{}';
        if (!empty($fixtureFile)) {
            $fixtureContent = file_get_contents(__DIR__.'/fixtures/'.$fixtureFile);
        }

        return self::getBodyResponse($responseCode, json_decode($fixtureContent, true));
    }
}
