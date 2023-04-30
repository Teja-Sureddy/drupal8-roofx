<?php
namespace Drupal\services\Service;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class services_service{

    public function __construct() {}

    public function getServicesData(){
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
        $conditions = $query->condition('type', 'services')
                            ->condition('status', 1, '=')
                            ->sort('created','DESC')
                            ->range(0, 1)->execute();
        $array = \Drupal\node\Entity\Node::loadMultiple($conditions);

        foreach($array as $val){
            $title = $val->getTitle();
            $banner = $val->get('field_services_top_banner')->entity->url();
            $heading = $val->get('field_service_heading')->value;
            $services_title = $val->get('field_service_title')->value;
            $services_data = array(
                'title' => $title,
                'banner' => $banner,
                'heading' => $heading,
                'services_title' => $services_title,
            );
            return $services_data;
            break;
        }
    }    
    
    public function getServiceDetailsData(){
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
        $conditions = $query->condition('type', 'service_details')
                            ->condition('status', 1, '=')
                            ->sort('created','DESC')->execute();
        $array = \Drupal\node\Entity\Node::loadMultiple($conditions);

        $service_details_data = array();
        foreach($array as $val){
            $title = $val->getTitle();
            $node_id = 'node/' . $val->id();
            $icon = $val->get('field_sd_title_icon')->entity->url();
            $image = $val->get('field_sd_image')->entity->url();
            $description = $val->get('field_sd_description')->value;

            $service_details_data[] = array(
                'title' => $title,
                'node_id' => $node_id,
                'icon' => $icon,
                'image' => $image,
                'description' => $description,
            );

        }
        return $service_details_data;
    }
}
?>