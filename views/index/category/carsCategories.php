<?php 
	$categories_menu = categories_to_string($categories_tree); 

	/**
	* Tree to HTML string
	**/
  	function categories_to_string($data) {
	    $string = "";
	    foreach($data as $item) {
	      $string .= categories_to_template($item);
	    }
	    return $string;
  	}

	function categories_to_template($category) {
    	$string = "";
		$string .= '<li>';
		$string .= '<a href="/mvc/car/viewCategory?categoryId='. $category['id']. '">' . $category['name'] . '</a>';
		if(isset($category['childs'])) {
			if($category['childs']){
				$string .= '<ul>';
				$string .= categories_to_string($category['childs']);
				$string .= '</ul>';
			}
		}
		$string .= '</li>';
		return $string;
	}
?>

<div class="catalog">
	<ul class="category"> 
		<?php  echo $categories_menu; ?>
	</ul>
</div>

<script src="/mvc/js/jquery-1.4.3.min.js"></script>
<script src="/mvc/js/jquery.accordion.js"></script>
<script src="/mvc/js/jquery.cookie.js"></script>
<script src="/mvc/js/categories.js"></script>