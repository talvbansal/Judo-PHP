<?php

namespace Tests;

use Tests\Builders\SaveCardBuilder;
use Tests\Helpers\AssertionHelper;
use Tests\Helpers\ConfigHelper;

abstract class SaveCardTests extends JudopayTestCase
{
    public function testValidSaveCard()
    {
        $saveCard = $this->getBuilder()
            ->setAttribute('cv2', '')
            ->build(ConfigHelper::getBaseConfig());

        $result = $saveCard->create();

        AssertionHelper::assertSuccessfulPayment($result);
    }

    public function testValidSaveCardWithNoCv2()
    {
        $saveCard = $this->getBuilder()
            ->build(ConfigHelper::getBaseConfig());

        $result = $saveCard->create();

        AssertionHelper::assertSuccessfulPayment($result);
    }

    protected function getBuilder()
    {
        return new SaveCardBuilder();
    }
}
