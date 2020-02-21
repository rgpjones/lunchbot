<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Pimple\Container;
use RgpJones\Rotabot\Messenger\Memory;
use RgpJones\Rotabot\Operation\OperationDelegator;
use RgpJones\Rotabot\Operation\OperationProvider;
use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Storage\NullStorage;

/**
 * Behat context class.
 */
class FeatureContext implements Context
{
    private $container;

    private $operationDelegator;

    private $response;

    /**
     * @BeforeScenario
     */
    public function setup()
    {
        $this->container = new Container;
        $this->registerServices($this->container);
        $this->operationDelegator = new OperationDelegator(new OperationProvider());
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
    }

    /**
     * @Given I am a rota user
     */
    public function iAmARotaUser()
    {
        $this->container['username'] = 'test';
        $this->container['rota_manager']->addMember($this->container['username']);
    }

    /**
     * @When I send the text :operationText
     */
    public function iSendTheText($operationText)
    {
        $this->container['text'] = substr($operationText, 6);
        $this->response = $this->operationDelegator->runOperation($this->container);
    }

    /**
     * @Then I should receive a direct response of
     */
    public function iShouldReceiveADirectResponseOf(PyStringNode $string)
    {
        if (strpos($this->response, (string) $string) === false) {
            throw new \Exception(sprintf("Expected:\n%s\nbut got\n%s\n", $string, $this->response));
        }
    }

    /**
     * @Given it is :username user's turn today
     */
    public function isUsersTurnToday($username)
    {
        $today = new DateTime;
        /** @var RotaManager $manager */
        $manager = $this->container['rota_manager'];
        $manager->addMember($username);
        $manager->setMemberForDate($today, $username);
    }

    /**
     * @Then I should see the messenger response of
     */
    public function iShouldSeeTheMessengerResponseOf(PyStringNode $string)
    {
        $messages = $this->container['messenger']->getMessages();

        if (strpos($messages[0], (string) $string) === false) {
            throw new Exception(sprintf('Expected %s but got %s', $string, $messages[0]));
        }
    }



    protected function registerServices(Container $container): Container
    {
        $container['storage'] = function () use ($container) {
            return new NullStorage();
        };

        $container['rota_manager'] = function () use ($container) {
            return new RotaManager($container['storage']);
        };

        $container['messenger'] = function () {
            return new Memory();
        };

        return $container;
    }
}
