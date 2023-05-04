<?php
namespace Drupal\home\Service;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class home_service{

    public function __construct() {}
    
    public function getHomeData(){
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery()
                ->condition('type', 'home')
                ->condition('status', 1, '=')
                ->range(0, 1)
                ->sort('created', 'DESC');
        $conditions = $query->execute();
        $array = \Drupal\node\Entity\Node::loadMultiple($conditions);

        foreach($array as $val){
            $title = $val->get('field_home_title')->value;
            $description = $val->get('field_home_description')->value;
            $banner = $val->get('field_home_top_banner')->entity->url();
            $left_banner = $val->get('field_home_top_left_image')->entity->url();

            $cards = array();
            foreach($val->get('field_home_top_cards')->referencedEntities() as $card){
                $title = $card->field_text->value;
                $description = $card->field_description->value;
                $icon = $card->field_icon->entity->url();
                $cards[] = array(
                    'title' => $title,
                    'description' => $description,
                    'icon' => $icon,
                );
            };

            $home_data = array(
                'title' => $title,
                'description' => $description,
                'banner' => $banner,
                'left_banner' => $left_banner,
                'cards' => $cards
            );
            return $home_data;
            break;
        }
    }

    public function getBrandCarousel(){
        $carousel_query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
        $carousel_conditions = $carousel_query->condition('type','brands_carousel')
                            ->condition('status', 1, '=')
                            ->sort('created','DESC')
                            ->range(0, 1)->execute();
        $carousel_array = \Drupal\node\Entity\Node::loadMultiple($carousel_conditions);
        $image_urls = [];
        foreach($carousel_array as $carousel_val){
            $images = $carousel_val->get('field_brand_image')->getValue();
            foreach ($images as $image) {
                $file = \Drupal\file\Entity\File::load($image['target_id']);
                $url = file_create_url($file->getFileUri());
                $image_urls[] = $url;
            }
            break;
        }
        return $image_urls;
    }
}
?>