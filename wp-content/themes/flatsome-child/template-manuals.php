<?php
/**
 * Template Name: Navodila
 *
 */

get_header();
?>

<style type="text/css">
	
	#mojspisek {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

ul#mojspisek li {
    margin-bottom: 12px;
	    display: inline-flex;
    width: 24%;
	padding: 5px;
    line-height: 1.1;
}


@media only screen and (max-width: 1000px) {
 ul#mojspisek li {

    width: 49%;
	
}

}

#mojspisek li a {
    border: 1px solid #ddd;
    margin-top: -1px;
    /* background-color: #f6f6f6; */
    /* padding: 12px; */
    text-decoration: none;
    font-size: 18px;
    color: black;
	height: 302x;
    display: block;
}

img.slikaizdelka {
    margin-left: auto !important;
    margin-right: auto !important;
    display: block;
	    object-fit: cover;
		
		height: 200px;
    width: 100% !important;
    max-height: 200px;
	min-height: 200px;
		
    height: 200px;
}

ul#mojspisek li a span {
    background-color: #f6f6f6;
	    display: block;
    height: 100px;
  padding: 15px 10px 10px 10px;
    font-size: 15px;
    font-weight: 600;
}


#vnoss {
	
	width: 50%;
}

</style>
<div id="content" class="content-area page-wrapper" role="main">
	<div class="container">
	<div class="row row-main">
		<div class="col text-center">
				
				<h1><?php the_title(); ?></h1>
				
				<p>
				<?php the_content(); ?>
				</p>
			
			
			</div>
		</div><!-- .large-12 -->
		
		
			<div class="row row-main">
		<div class="col text-center">
				
			<input type="text" id="vnoss" onkeyup="myFunction()" placeholder="<?php echo get_field('navodila_dodaten_tekst1'); ?>" title="<?php echo get_field('navodila_dodaten_tekst2'); ?>" class="form-control">
			
			
			</div>
		</div><!-- .large-12 -->
		
		<div class="row row-main">
			<div class="col">
			
					<?php
					$navodila = get_field("navodila");
					//var_dump($navodila);
					?>
					
					<?php if(  $navodila ): ?>
					<ul id="mojspisek">
						
					  <?php foreach($navodila as $n):  ?>
					   <li data-sku="<?php echo $n['sku']; ?>">
					   
					   <a  href="<?php echo $n['pdfn']; ?>"><img class="slikaizdelka" src="<?php echo $n['slka']; ?>">
					  
					  <span data-sku="<?php echo $n['sku']; ?>" ><?php echo $n['izdelek']; ?></span>
					   
					   </a>
					   
					   </li>
					   <?php endforeach; ?>

					 
					
					
					</ul>
					<?php endif; ?>
					
					<script>
					function myFunction() {
						var input, filter, ul, li, a, i, txtValue, data;
						
						input = document.getElementById("vnoss");
						filter = input.value.toUpperCase();
						
						
						ul = document.getElementById("mojspisek");
						li = ul.getElementsByTagName("li");
						
						
						
						for (i = 0; i < li.length; i++) {
							
							a = li[i].getElementsByTagName("span")[0];
							
							
							
							txtValue = a.textContent || a.innerText;
							
							console.log( txtValue );
							
							data =  a.getAttribute('data-sku');
							
						    // console.log( data );
							//console.log( "---" );
							
							
							if (  txtValue.toUpperCase().indexOf(filter) > -1  ||  data.toUpperCase().indexOf(filter) > -1   ) {
								
								li[i].style.display = "";
						
						} else {
							
							
								li[i].style.display = "none";
							}
						}
					}
					</script>
				
				
			</div>
		</div><!-- .large-12 -->
		
	</div><!-- .row -->
</div>

<?php
get_footer();

?>
