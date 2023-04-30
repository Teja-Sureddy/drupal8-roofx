<?php

namespace Drupal\services\Controller;

class services_controller{
    public function getData(){
        try{
            $service = \Drupal::service('services.data');
            $services_data = $service->getServicesData();
            $service_details = $service->getServiceDetailsData();
        }
        catch(Exception $error){
            \Drupal::logger('Services')->warning($error->getMessage());
        }
        return[
            '#theme' => 'services',
            '#module_path' => '{{$base_path}}/modules/custom/services',
            '#services_data' => $services_data,
            '#service_details' => $service_details,
        ];
    }
}
?>