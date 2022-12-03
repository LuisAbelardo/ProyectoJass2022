<?php namespace App\Traits;

trait HasDefaultImage
{
    /**
     * Trait para obtener una imagen por defecto en caso no tener una
     * @param string $imageUrl
     * @param string $name
     * @return string
     */
    public function getImage(string $imageUrl, string $name) {
        
        if(!$imageUrl){
            $name = trim($name);
            $name = str_replace(" ", "+", $name);
            $imageUrl = "https://ui-avatars.com/api/?name={$name}&size=160&background=fff&color=072146";
        }
        
        return $imageUrl;
    }
}