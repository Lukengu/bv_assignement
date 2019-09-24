<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ApiConnection;

class DashboardController extends AbstractController
{

    /**
     *
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
      

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController'
        ]);
    }

    public function linedata(ApiConnection $apiConnection, Request $request)
    {
        $month = (int) $request->get('month', 0);
        // echo $month; exit;

        $auth_token = $this->getUser()->getAccessToken();
        $machines = $apiConnection->getMachines($auth_token);

        // Filter for wanted data
        $formatted_line = [];

        foreach ($machines as $machine) {
            $_m = new \DateTime( $machine->last_login_date);
            $_m = $_m->format("m");
            
            array_push($formatted_line, [
                $machine->last_login_date,
                $machine->machine_type,
                $_m
            ]);
        }

        usort($formatted_line, [
            $this,
            'orderByDate'
        ]);
        $formatted_line = array_filter($formatted_line, function ($array) use ($month) {

            $date = new \DateTime($array[0]);
            $m = (int) $date->format('m');
            if ($m < $month) {
                return $array;
            }
        });
        array_walk($formatted_line, [
            $this,
            'formatMonth'
        ]);
        $final  = [];
        foreach ($formatted_line as $child) {
            $key = $child[0] . $child[1].$child[2];
            
            if(!array_key_exists($key, $final)) {
                // check if we already found objects with the same data, if not then we start with 0 as containercount
                $final[$key] = [
                    'month' =>  $child[0],
                    'name' => $child[1],    
                    'n' => 0,
                    '_m' => $child[2]
                ];
            }
            
            $final[$key]['n'] += 1;
        }
        
        $formatted_line = array_values($final);
        array_unshift($formatted_line, ['month','name','n', '_m']);
        
        $text = "";
        foreach($formatted_line as $line ){
            $text .= implode(",", $line).PHP_EOL; 
        }
        
        $response = $this->render('dashboard/line_data.html.twig', [
            'data' => $text
        ]);
       
      
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="data.csv"');
        
        return $response;
    }

    private function orderByDate($array1, $array2)
    {
        $date1 = new \DateTime($array1[0]);
        $date2 = new \DateTime($array2[0]);
        return $date2 < $date1;
    }

    private function formatMonth(&$value, $key)
    {
        $date = new \DateTime($value[0]);
        $value[0] = $date->format('M');
       
    }
}
