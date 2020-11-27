<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use Application\Controller\QuoteController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class QuoteControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testQuotesCanBeListed()
    {
        $this->dispatch('/api/shout/all', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(QuoteController::class); // as specified in router's controller name alias
        $this->assertControllerClass('QuoteController');
        $this->assertMatchedRouteName('api/shout');
    }

    public function testQuotesCanBeListedByAuthor()
    {
        $this->dispatch('/api/shout/Dalai Lama', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(QuoteController::class); // as specified in router's controller name alias
        $this->assertControllerClass('QuoteController');
        $this->assertMatchedRouteName('api/shout');

        $response = $this->getResponse();
        $data = json_decode($response->getBody(true), true);

        $this->assertEmpty(@$data['error'],"Error: ".@$data['error']);
        $this->assertGreaterThan(0,count($data));

    }

    public function testQuotesCanBeListedByAuthorWithLimit()
    {
        $this->dispatch('/api/shout/Dalai Lama?limit=10', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(QuoteController::class); // as specified in router's controller name alias
        $this->assertControllerClass('QuoteController');
        $this->assertMatchedRouteName('api/shout');

        $response = $this->getResponse();
        $data = json_decode($response->getBody(true), true);

        $this->assertEmpty(@$data['error'],"Error: ".@$data['error']);
        $this->assertGreaterThan(1,count(@$data['data']));

    }

    public function testQuotesCanBeListedByAuthorAndLimit()
    {
        $this->dispatch('/api/shout/Dalai Lama?limit=1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(QuoteController::class); // as specified in router's controller name alias
        $this->assertControllerClass('QuoteController');
        $this->assertMatchedRouteName('api/shout');

        $response = $this->getResponse();
        $data = json_decode($response->getBody(true), true);

        $this->assertEmpty(@$data['error'],"Error: ".@$data['error']);
        $this->assertEquals(1,count(@$data['data']));

    }
}
