<?php

namespace Drupal\attraction_tickets\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\attraction_tickets\Client\MyClient;

define('LIMIT', 10);

/**
 * Class MyController.
 *
 * @package Drupal\my_custom_module\Controller
 */
class AtsController extends ControllerBase
{

    /**
     * Drupal\attraction_tickets\Client\MyClient definition.
     *
     * @var \Drupal\attraction_tickets\Client\MyClient
     */
    protected $AtsApiClient;

    /**
     * {@inheritdoc}
     */
    public function __construct(MyClient $Ats_api_client)
    {
        $this->AtsApiClient = $Ats_api_client;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('attraction_tickets.client')
        );
    }

    /**
     * Content.
     *
     * @return array
     *   Return array.
     */
    public function content()
    {
        $title = !empty(\Drupal::request()->query->get('title')) ? \Drupal::request()->query->get('title') : '';
        $page = !empty(\Drupal::request()->query->get('page')) ? \Drupal::request()->query->get('page') : 0;
        $offset = LIMIT * $page;
        //var_dump($title);
        $params = [
            'geo' => 'en',
            'offset' => $offset,
            'title' => trim($title)
        ];

        $request = $this->AtsApiClient->request('get', '/api/products', $params, []);
        $results = json_decode($request, true);
        //return [];
        $data = array();

        // # add all the data in one multiple dim array
        $data['products'] = $results;

        //display search form
        $build['form'] = $this->formBuilder()->getForm('Drupal\attraction_tickets\Form\SearchForm');
        // display the content in the middle section of the page
        $build['table'] = array(
            '#theme' => 'ats_product_list', // assign the theme [products-list.html.twig]
            '#title' => 'Product Search', // assign the page title
            '#data' => $data,
        );

        $total = $results['meta']['total_count'];
        $num_per_page = LIMIT;
        $pager = \Drupal::service('pager.manager')->createPager($total, $num_per_page);
        $page = $pager->getCurrentPage();

        // Next, retrieve the items for the current page and put them into a
        // render array.
        $offset = $num_per_page * $page;
        $build['pager'] = array(
            '#type' => 'pager'
        );

        return $build;
    }
}
