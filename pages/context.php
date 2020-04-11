
<?php


    session_start();

?>

<button id="btnLoad">Load Content</button>
<div id="content">
  Please click "Load Content" button to load content.
</div>



<script>

$("#btnLoad").click(function(){

// Put an animated GIF image insight of content



// Make AJAX call

});

window.onload = function(){
    $("#content").empty().html('<img src="./img/loading14.gif" />');
    //$("#content").load("index.php?url=pages/person_risk_all.php");
}

</script>