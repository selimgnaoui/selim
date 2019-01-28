<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Auto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }
    /**
     * @Route("/show", name="show")
     */
    public function showAction(Request $request)
    {
        $link='https://mitarbeiterautohaus.meinauto.de/data/modelle/17d323d6fd823d9e7f8953085bab63f6';
        $result =[];
        $autos=file_get_contents($link);

        $data =simplexml_load_string($autos);
     // dump( $data->data->brand);
        foreach ($data->data->brand as $brands)
        {
            $aux = ($brands->attributes());
            $name=(string)($aux['name']);

            foreach ($brands->modelgroup as $modelgroup)
            {
               foreach ($modelgroup->model as $model){
                   $highest_price=$this->getPrice($model->discount);
                   if (!(array_key_exists($name, $result) ) || ($result[$name] <= $highest_price))
                       $result[$name]=$highest_price;
               }




            }


        }
        $entityManager = $this->getDoctrine()->getManager();


        foreach ($result as $auto_name=>$price){
          $auto = new Auto();
          $auto->setName($auto_name);
          $auto->setPrice($price);
          $entityManager->persist($auto);



        }
        $entityManager->flush();

     //  echo $this->getPrice('22 - 25');


        // replace this example code with whatever you need
        return $this->render('default/show.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,'autos'=>$result
        ));
    }



    public function getPrice($intraval) {

        $prices=explode("-",$intraval);

        // return the highest price
         return $prices[1];



    }

}
