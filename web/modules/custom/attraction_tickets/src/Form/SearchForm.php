<?php

namespace Drupal\attraction_tickets\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;


class SearchForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'search_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, array $config = [])
    {
        $title = !empty(\Drupal::request()->query->get('title')) ? \Drupal::request()->query->get('title') : 'search';
        $form['search']['title'] = array(
            '#type' => 'search',
            '#title' => 'title',
            '#required' => TRUE,
            '#placeholder' => $title
        );
        $form['search']['actions'] = [
            '#type' => 'actions'
        ];
        $form['search']['actions']['submit'] = [
            '#type'  => 'submit',
            '#value' => $this->t('Search')

        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $title = $form_state->getValue('title');
        $url = \Drupal\Core\Url::fromRoute('attraction_tickets.products')
            ->setRouteParameters(array('title' => $title));
        $form_state->setRedirectUrl($url);
    }
}
