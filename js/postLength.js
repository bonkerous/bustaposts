
$("#postData").on("input", function(){
    $("#postLength").html(160 - $("#postData").val().length + " characters left");
});