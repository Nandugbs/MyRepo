<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nandini.B
 * Date: 7/7/13
 * Time: 7:27 PM
 * To change this template use File | Settings | File Templates.
 */
namespace AlbumTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__."/../../../../../config/application.config.php"
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
            ->disableOriginalConstructor()
            ->getMock();

        $albumTableMock->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(array()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
            ->disableOriginalConstructor()
            ->getMock();

        $albumTableMock->expects($this->once())
            ->method('saveAlbum')
            ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);

        $postData = array('title' => 'Led Zeppelin III', 'artist' => 'Led Zeppelin');
        $this->dispatch('/album/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/album/');
    }
}