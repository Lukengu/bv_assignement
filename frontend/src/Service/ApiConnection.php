<?php
namespace App\Service;

/**
 * Api Connection Class
 *
 * @author Philippe Bona
 *        
 *        
 */
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;

class ApiConnection
{

    /**
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->client = $httpClient->getClient();
    }

    /**
     * *
     * Login in the Api
     *
     * @param string $username
     * @param string $password
     * @return string
     */
    public function login(string $username, string $password): string
    {
        try {

            $response = $this->client->post('v1/users/login', [
                RequestOptions::JSON => [
                    'username' => $username,
                    'password' => $password
                ]
            ]);

            if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
                $response = json_decode($response->getBody()->getContents());
                return $response->jwt_token;
            }
        } catch (RequestException $ex) {}

        return '';
    }

    /**
     * *
     * get Machines for a user
     *
     * @param string $auth_token
     * @return mixed
     */
    public function getMachines(string $auth_token)
    {
        $response = $this->client->get('v1/machines', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $auth_token
            ]
        ]);
        $machines = [];
        try {
            if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
                $machines = json_decode($response->getBody()->getContents());
            }
        } catch (RequestException $ex) {}

        return $machines[0];
    }
    
    /**
     * Register user
     * @param array $user
     * @return bool
     */
    public function registerUser(array $user): array
    {
        $exception = "";
        
        try {
            $response = $this->client->post('v1/users', [
                RequestOptions::JSON => $user
            ]);
            
            $response = $response->getBody()->getContents();
            if(isset($response->error)){
                return [false, $response->error];
            }
            return [true, $response];
        } catch (RequestException $ex) {
            $exception = $ex->getMessage();
        }

        return [false, $exception];
    }
}

