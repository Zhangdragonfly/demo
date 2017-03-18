function getDefaultRetailPrice(origin_price){
    var retail_price = 0;
    if(origin_price >= 200000){
        retail_price = origin_price * 1.15;
    } else if(origin_price >= 100000 && origin_price < 200000){
        retail_price = origin_price * 1.2;
    } else if(origin_price >= 50000 && origin_price < 100000){
        retail_price = origin_price * 1.25;
    } else if(origin_price >= 10000 && origin_price < 50000){
        retail_price = origin_price * 1.3;
    } else if(origin_price >= 3000 && origin_price < 10000){
        retail_price = origin_price * 1.4;
    } else if(origin_price >= 1000 && origin_price < 3000){
        retail_price = origin_price * 1.6;
    } else if(origin_price >= 0 && origin_price < 1000){
        retail_price = origin_price * 3;
    }
    return retail_price;
}