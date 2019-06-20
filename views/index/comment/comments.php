<?php 
if (isset($_GET['carId'])) {
	$parent = $_GET['carId'];
?>
<script src="/mvc/js/jquery-1.4.3.min.js"></script>
<script src="/mvc/js/comments.js"></script>

<p id="comment_head">Comments:</p>
<div id="comments"></div>


<form class="commentForm" method="post" action="/mvc/server/commentsServer.php">
	<input type="hidden" id="parent" name="parent" value="<?=$parent?>" />
	<?php if(isset($_SESSION['user'])) { ?>
		<input type="hidden" id="comment" name="comment" value="true" />
		<input type="hidden" id="user" name="user" value="<?=$_SESSION['user']?>" />

		<ul>
			<li>
				<label for="message">Comment</label>
				<textarea name="message" id="message" placeholder="Your comment" required autofocus></textarea>
			</li>
		</ul>

		<div class="buttons">
	          <input type="submit" id="submit" name="submit" value="Send" />
	    </div>
	<?php } else {?>
	<p><a href="/mvc/user/login">Log in</a> to send comment!</p>
	<?php } ?>
</form>

<?php } ?>