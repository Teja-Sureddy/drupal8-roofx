<?php

namespace Drupal\home\Controller;

class home_controller{
    public function getData(){
        try{
            $home_service = \Drupal::service('home.data');
            $home_data = $home_service->getHomeData();
            $brand_carousel = $home_service->getBrandCarousel();

            $team_service = \Drupal::service('team.data');
            $team_data = $team_service->getTeamData();
            $team_details = $team_service->getTeamDetailsData(true);

            $service = \Drupal::service('services.data');
            $services_data = $service->getServicesData();
            $service_details = $service->getServiceDetailsData(true);
            
            $projects_service = \Drupal::service('projects.data');
            $projects_data = $projects_service->getprojectsData();
            $project_details = $projects_service->getprojectDetailsData(true);
        }
        catch(Exception $error){
            \Drupal::logger('home')->warning($error->getMessage());
        }
        return[
            '#theme' => 'home',
            '#module_path' => '{{$base_path}}/modules/custom/home',
            '#home_data' => $home_data,
            '#brand_carousel' => $brand_carousel,
            '#projects_data' => $projects_data,
            '#project_details' => $project_details,
            '#services_data' => $services_data,
            '#service_details' => $service_details,
            '#team_data' => $team_data,
            '#team_details' => $team_details,
        ];
    }
}
?>