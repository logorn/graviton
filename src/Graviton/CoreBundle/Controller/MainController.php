<?php
/**
 * controller for start page
 */

namespace Graviton\CoreBundle\Controller;

use JMS\Serializer\Exception\Exception;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Graviton\RestBundle\HttpFoundation\LinkHeader;
use Graviton\RestBundle\HttpFoundation\LinkHeaderItem;

/**
 * MainController
 *
 * @category GravitonCoreBundle
 * @package  Graviton
 * @author   Lucas Bickel <lucas.bickel@swisscom.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.com
 */
class MainController
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface service_container
     */
    private $container;

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container service_container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * create simple start page.
     *
     * @return \Symfony\Component\HttpFoundation\Response $response Response with result or error
     */
    public function indexAction()
    {
        $response = $this->container->get('graviton.rest.response.200');

        $links = LinkHeader::fromString('');
        $links->add(
            new LinkHeaderItem(
                'http://localhost/core/app',
                array(
                    'rel' => 'apps',
                    'type' => 'application/vnd.graviton.schema.collection.app+json'
                )
            )
        );

        $response->headers->set('Link', (string) $links);

        $composer = json_decode(file_get_contents(__DIR__.'/../../../../composer.json'), true);
        $response->headers->set('X-Version', $composer['version']);

        $mainPage = new \stdClass;
        $mainPage->message = 'Please look at the Link headers of this response for further information.';
        $response->setContent(json_encode($mainPage));

        return $response;
    }
}
