      <h1><?php echo $results['pageTitle']?></h1>

      <form action="/mvc/admin/category/<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="categoryId" value="<?php echo $results['category']->id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

        <ul>

          <li>
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" placeholder="Name of the category" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['category']->name )?>" />
          </li>

          <li>
            <label for="parent">Category Parent</label>
            <input type="text" name="parent" id="parent" placeholder="Parent of the category" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['category']->parent )?>" />
          </li>

        </ul>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>

      </form>

<?php if ( $results['category']->id ) { ?>
      <p><a href="/mvc/admin/category/deleteCategory?categoryId=<?php echo $results['category']->id ?>" onclick="return confirm('Delete This Category?')">Delete This Category</a></p>
<?php } ?>
