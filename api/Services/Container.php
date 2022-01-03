<?php




/**
 * Defines Shop Container.
 */
class Container implements ContainerInterface {


  //Trait
  use Common_Methods;
 

   private $services = [];
  public static function create(PDO $pdo){
    return new static($pdo);
  }

   public function __construct(PDO $pdo){
     $this->services['pdo'] = $pdo;
   }
   
   public function get($service_name){

    //var_dump($this->authRoute($_POST));

    if(!isset($this->services[$service_name])){
      switch($service_name){
        case 'database':
          $service = new DatabaseService($this->get('pdo'));
        break;
        case 'rest_service':
                                    //Use the the get_api_service method to get the service name
          $service = new RestService($this->get($this->getService($_POST)->service));
        break;
        case 'user':
          $service = new UserService($this->get('database'));
        break;
        case 'product':
          $service = new ProductService($this->get('database'));
        break;
        case 'category':
          $service = new CategoryService($this->get('database'));
        break;
        case 'cart':
          $service = new CartService($this->get('database'), $this->get('product'));
        break;
        case 'order':
          $service = new OrderService($this->get('database'),$this->get('user'),$this->get('cart'),$this->get('orderedItem'));
        break;
        case 'orderedItem':
          $service = new OrderedItemsService($this->get('database'), $this->get('product'));
        break;
        case 'utils':
          $service = new UtilitiesService($this->get('user'),$this->get('order'));
        break;

      }
      $this->services[$service_name] = $service;
    }

    return $this->services[$service_name];
   }

  private function getService($values){

    $result = (object)["service" => ""];

    if(isset($values['request'])){

      $service = $this->authRoute($values);

      if($service){

        if($service->service->access && $service->method->access){

          if(in_array($_SERVER['REQUEST_METHOD'], $service->requestMethods)){

            $result = (object)["service" => $service->service->name];

          }

        }

      }

    }

    return $result;

  }

}

