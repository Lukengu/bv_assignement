<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\ApiConnection;


/**
 *  test case.
 */
class ApiConnectionTest  extends KernelTestCase
{
    /**
     * 
     * @var ApiConnection
     */
    private $connection;
    
  

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        static::bootKernel();
        $this->connection  = static::$container->get('App\Service\ApiConnection');

        // TODO Auto-generated ApiConnectionTest::setUp()
    }
    
    /**
     * @dataProvider userDataProvider
     */
    public function testLoginApiCommunication($assertion, $username, $password)
    {
      
        $this->assertEquals($assertion, strlen($this->connection->login($username,$password)) > 0);
       
    }
    
    /**
     * @dataProvider existingUserDataProvider
     */
    public function testUserMachine($assertion, $username, $password)
    {
        $access_token = $this->connection->login($username,$password);
        $machines =  $this->connection->getMachines($access_token);
        $this->assertEquals($assertion, count($machines));
        
    }
    
    /**
     * @dataProvider userRegisterProvider
     */
    public function testUserRegister($assertion,$name, $username, $password)
    {
        $result = $this->connection->registerUser([
            'name' => $name,
            'password' => $password,
            'username' => $username
        ]);
      
        
       
        $this->assertEquals($assertion,$result[0]);
        
    }
    

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated ApiConnectionTest::tearDown()
        $this->connection = null;
        parent::tearDown();
      
    }
    
    public function existingUserDataProvider()
    {
        return [
            [50, "username1", "password1"],
            [15000, "username2", "password2"]
         
        ];
    }
    
    
    public function userDataProvider()
    {
        return [
            [true, "username1", "password1"],
            [true, "username2", "password2"],
            [false, "username1", "password2"]
        ];
    }
    
    public function userRegisterProvider()
    {
        return [
            [true,md5(random_int(10,30)), md5(random_int(10,30)), md5(random_int(10,30))],
            [false, "name", "username1", "password1"]
            
        ];
    }
    
 

    
}

