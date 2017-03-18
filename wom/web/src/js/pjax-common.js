function plainContentLengthLimit(){
    $('.plain-text-length-limit').each(function(){
        var content = $(this).text().trim();
        var length_limit = $(this).attr('data-limit');
        var content_length = content.length;

        if(length_limit == undefined){
            length_limit = 5;
        }

        if(content_length > length_limit){
            $(this).text(content.substr(0, length_limit) + '...');
        }
        $(this).attr('data-value', content);
    })
};