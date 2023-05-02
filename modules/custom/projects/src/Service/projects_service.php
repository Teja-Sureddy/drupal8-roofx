<?php
namespace Drupal\projects\Service;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class projects_service{

    public function __construct() {}

    public function getprojectsData(){
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
        $conditions = $query->condition('type', 'projects')
                            ->condition('status', 1, '=')
                            ->sort('created','DESC')
                            ->range(0, 1)->execute();
        $array = \Drupal\node\Entity\Node::loadMultiple($conditions);

        foreach($array as $val){
            $title = $val->getTitle();
            $banner = $val->get('field_projects_top_banner')->entity->url();
            $heading = $val->get('field_projects_heading')->value;
            $projects_title = $val->get('field_projects_title')->value;
            $projects_data = array(
                'title' => $title,
                'banner' => $banner,
                'heading' => $heading,
                'projects_title' => $projects_title,
            );
            return $projects_data;
            break;
        }
    }    
    
    public function getprojectDetailsData($home = false){
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery()
                ->condition('type', 'project_details')
                ->condition('status', 1, '=')
                ->sort('created', 'DESC');
        if ($home) {
            $query->range(0, 4);
        }
        $conditions = $query->execute();
        $array = \Drupal\node\Entity\Node::loadMultiple($conditions);

        $project_details_data = array();
        foreach($array as $val){
            $title = $val->getTitle();
            $node_id = 'node/' . $val->id();
            $image = $val->get('field_pd_image')->entity->url();
            $description = $val->get('field_pd_description')->value;
            $project_title = $val->get('field_pd_title')->value;

            $project_details_data[] = array(
                'title' => $title,
                'node_id' => $node_id,
                'image' => $image,
                'description' => $description,
                'project_title' => $project_title,
            );

        }
        return $project_details_data;
    }
}
?>