<?php
namespace Drupal\team\Service;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class team_service{

    public function __construct() {}

    public function getTeamData(){
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
        $conditions = $query->condition('type', 'team')
                            ->condition('status', 1, '=')
                            ->sort('created','DESC')
                            ->range(0, 1)->execute();
        $array = \Drupal\node\Entity\Node::loadMultiple($conditions);

        foreach($array as $val){
            $title = $val->getTitle();
            $banner = $val->get('field_team_top_banner')->entity->url();
            $heading = $val->get('field_team_heading')->value;
            $team_title = $val->get('field_team_title')->value;
            $team_data = array(
                'title' => $title,
                'banner' => $banner,
                'heading' => $heading,
                'team_title' => $team_title,
            );
            return $team_data;
            break;
        }
    }    
    
    public function getTeamDetailsData($home = false){
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery()
                ->condition('type', 'team_details')
                ->condition('status', 1, '=')
                ->sort('created', 'DESC');
        if ($home) {
            $query->range(0, 4);
        }
        $conditions = $query->execute();
        $array = \Drupal\node\Entity\Node::loadMultiple($conditions);

        $team_details_data = array();
        foreach($array as $val){
            $title = $val->getTitle();
            $node_id = 'node/' . $val->id();
            $image = $val->get('field_profile_image_one')->entity->url();
            $designation = $val->get('field_designation')->value;

            $socials = array();
            foreach($val->get('field_member_socials')->referencedEntities() as $social){
                $class = $social->field_text->value;
                $link = $social->field_description->value;
                $socials[] = array(
                    'class' => $class,
                    'link' => $link,
                );
            };

            $team_details_data[] = array(
                'title' => $title,
                'node_id' => $node_id,
                'image' => $image,
                'designation' => $designation,
                'socials' => $socials
            );

        }
        return $team_details_data;
    }
}
?>