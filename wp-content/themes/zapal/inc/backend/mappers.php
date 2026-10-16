<?php

function WBZX_Zapal_mapping_product(array $product): array
{
    $meta = is_array($product['meta'] ?? null) ? $product['meta'] : [];
    $info = is_array($meta['info'] ?? null) ? $meta['info'] : [];

    return [
        'id' => $product['id'],
        'name' => $product['name'],
        'content' => $product['content'],
        'description' => $info['subtitle'] ?? '',
        'permalink' => $product['permalink'] ?? '',
        'image' => $product['thumbnail'],
        'image_url' => $product['thumbnail_url'] ?? '',
        'recipe_image_url' => $product['recipe_image_url'] ?? '',
        'category' => $product['category'],
        'tags' => $product['tags'],
        'deliveries' => $product['deliveries'] ?? [],
        'uses_global_deliveries' => !empty($product['uses_global_deliveries']),
        'data' => $product['meta'],
    ];
}

function WBZX_Zapal_mapping_recipe(array $recipe): array
{
    return $recipe;
}
