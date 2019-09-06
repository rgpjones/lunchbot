<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use RgpJones\Rotaman\Application;
use RgpJones\Rotaman\RotaManager;
use RgpJones\Rotaman\Storage\NullStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    private $username;

    private $application;

    private $response;

    private $config;

    private $storage;

    /**
     * @BeforeScenario
     */
    public function setup()
    {
        $this->config = new SimpleXMLElement('<config/>');
        $this->config->webhook = 'http://example.com';
        $this->storage = new NullStorage();

        $this->application = new Application(
            [
                'config' => $this->config,
                'storage' => $this->storage,
                'debug' => true
            ]
        );
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        $this->storage = null;
    }

    /**
     * @Given I am a rota user
     */
    public function iAmARotaUser()
    {
        $this->username = 'test';
        $this->application['rota_manager']->addMember($this->username);
    }

    /**
     * @When I type :command
     */
    public function iType($command)
    {
        $text = trim(strstr($command, ' '));

        $request = Request::create(
            '/',
            'POST',
            [
                'text'      => $text,
                'user_name' => $this->username,
            ]
        );

        $this->response = $this->application->handle($request);
    }

    /**
     * @Then I should see
     */
    public function iShouldSee(PyStringNode $string)
    {
        if (strpos($this->response->getContent(), (string) $string) === false) {
            throw new Exception(sprintf('Expected %s but got %s', $string, $this->response->getContent()));
        }
    }

    /**
     * @Given it is :username user's turn today
     */
    public function isShoppingToday($username)
    {
        $today = new DateTime;
        /** @var RotaManager $manager */
        $manager = $this->application['rota_manager'];
        $manager->addMember($username);
        $manager->setMemberForDate($today, $username);
    }

    /**
     * @Then I should see in the channel
     */
    public function iShouldSeeInTheChannel(PyStringNode $string)
    {
        $messages = $this->application['slack']->getMessages();

        if (strpos($messages[0], (string) $string) === false) {
            throw new Exception(sprintf('Expected %s but got %s', $string, $messages[0]));
        }
    }
}
