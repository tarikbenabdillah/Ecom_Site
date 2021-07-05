<?php
include_once "db.php";
// 1-Get the file data
$strJsonFileContents = file_get_contents("products.json");
// 2- decode the data to json 
$products =json_decode($strJsonFileContents);

 //loop indise json array PRODUCTS
   foreach ($products as $key => $current) {
    //destructing $variables
    $ref  = $current->ref;
    $name = $current->name;
    $type = $current->type;
    $price= $current->price;
    $shipping= $current->shipping;
    $description= $current->description;
    $manufacturer= $current->manufacturer;
    $image= $current->image;

    //check $description and $name from special caractaers like ' " 
    $description=str_replace("'","",$description);
    $description=str_replace('"',' ',$description);
    $name=str_replace('"',' ',$name);
    $name=str_replace("'","",$name);

    //insert Product into DATABASE.Products_TABLE
    InsertData("INSERT INTO products (ref,name,type,price,shipping,description,manufacturer,image) VALUES ('$ref','$name','$type','$price','$shipping','$description','$manufacturer','$image')",$conn);
    //loop into product ctagories and insert each one into DATABASE.categories_TABLE and check if they already exist if its the case then skip it !
    
    $categories = $current->category;
    for ($i=0; $i <sizeof($categories) ; $i++) { 
        $current_categorie = $categories[$i];

        //destructing $variables
        $id_cat =$current_categorie->id;
        $name_cat =$current_categorie->name;

        //check if this category already exist in the categories table .
        $result = GetData("SELECT id FROM categories WHERE id='$id_cat' ",$conn);
        if(sizeof($result) == 0) {
         // Insert the categorie into the table 
         InsertData("INSERT INTO categories (id,name) VALUES ('$id_cat','$name_cat')",$conn);
        }
        //associate the the product to the categorie in DATABASE.product_categories_TABLE
         InsertData("INSERT INTO product_categories (ref_prod,id_cat) VALUES ('$ref','$id_cat')",$conn);

               
    }
}
