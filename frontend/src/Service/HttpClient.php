<?php
namespace App\Service;

use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Api Connection Class
 *
 * @author Philippe Bona
 *        
 *        
 */
class HttpClient
{

    /**
     * *
     *
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * *
     *
     * @var Client
     */
    private $client;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->client = new Client([
            'base_uri' => $this->params->get('app.api_endpoint')
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}

