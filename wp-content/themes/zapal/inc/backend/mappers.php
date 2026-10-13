<?php
function WBZX_Zapal_mapping_product(array $product): array {
    return [
        'id' => $product['id'],
        'name' => $product['name'],
        'content' => $product['content'],
        'image' => $product['thumbnail'],
        'category' => $product['category'],
        'tags' => $product['tags'],
        'data' => $product['meta']
    ];
}

function WBZX_Zapal_mapping_recipe(array $recipe): array {
    $recipe = [];
    return $recipe;
}
