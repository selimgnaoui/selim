<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 28.01.19
 * Time: 16:25
 */


namespace AppBundle\Service;


class AutoService
{  
   
    // this is hard-coded : it should be normally saved as an ENV variable and loaded via the DI and Ijected via the DI. 
    // I can pass the env variable in the docker-compose file ,and then reload it .....
    // see what i did to the em variable 
    $link='https://mitarbeiterautohaus.meinauto.de/data/modelle/17d323d6fd823d9e7f8953085bab63f6';

    $protected $entitymanager;
    // the $em will be injected from the DI into this container
    public function __construct($em){
    $this->$entitymanager=$em;
    }

    /**
     * @var $interval : string 
     * split a string into two parts , and return the second value : used to return the highest price
     * ex usage : 77 - 100 as input will return 100
     */
    public function getPrice($intraval) {
        $prices=explode("-",$intraval);
        // return the highest price
         return $prices[1];
    }
    
    public function savetoDatabase($autos) {
        $prices=explode("-",$intraval);
        // return the highest price
         return $prices[1];
    }

    public function fetchApi(){
        $result =[];
        // fetch the endpoint
        $autos=file_get_contents($this->$link);
        // store data into an array 
        $data =simplexml_load_string($autos);
     
        foreach ($data->data->brand as $brands)
        {   // get the attributes 
            $aux = ($brands->attributes());
            // get the attribute 'name'
            $name=(string)($aux['name']);
            // iterate over the modelgroup elements 
            foreach ($brands->modelgroup as $modelgroup)
            {   // iterate over the models group
               foreach ($modelgroup->model as $model){
                   // get the discount varibale , split it and get the right part of the variable (the highest price)
                   $highest_price=$this->getPrice($model->discount);

                   // if it doesnt exist or its value is higher than  the already saved value, replace it.
                   
                   if (!(array_key_exists($name, $result) ) || ($result[$name] <= $highest_price))
                       $result[$name]=$highest_price;
               }
            }
        }
        // return an asso-array where keys are the auto 's names and value are the highest price
       return $result;


    }







}