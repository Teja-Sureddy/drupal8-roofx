<?php

namespace Drupal\projects\Controller;

class projects_controller{
    public function getData(){
        try{
            $service = \Drupal::service('projects.data');
            $projects_data = $service->getprojectsData();
            $project_details = $service->getprojectDetailsData();
        }
        catch(Exception $error){
            \Drupal::logger('projects')->warning($error->getMessage());
        }
        return[
            '#theme' => 'projects',
            '#module_path' => '{{$base_path}}/modules/custom/projects',
            '#projects_data' => $projects_data,
            '#project_details' => $project_details,
        ];
    }
}
?>