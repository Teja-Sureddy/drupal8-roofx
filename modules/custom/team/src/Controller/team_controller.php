<?php

namespace Drupal\team\Controller;

class team_controller{
    public function getData(){
        try{
            $service = \Drupal::service('team.data');
            $team_data = $service->getTeamData();
            $team_details = $service->getTeamDetailsData();
        }
        catch(Exception $error){
            \Drupal::logger('Team')->warning($error->getMessage());
        }
        return[
            '#theme' => 'team',
            '#module_path' => '{{$base_path}}/modules/custom/team',
            '#team_data' => $team_data,
            '#team_details' => $team_details,
        ];
    }
}
?>