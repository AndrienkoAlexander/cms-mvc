      <h1><?php echo $results['pageTitle']?></h1>

      <form action="/mvc/admin/car/<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="carId" value="<?php echo $results['car']->id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

        <ul>

          <li>
            <label for="name">Car Name</label>
            <input type="text" name="name" id="name" placeholder="Name of the car" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['car']->name )?>" />
          </li>

          <li>
            <label for="price">Car Price</label>
            <input type="text" name="price" id="price" placeholder="Price of the car" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['car']->price )?>" />
          </li>

          <li>
            <label for="serial_num">Car Serial number</label>
            <input type="text" name="serial_num" id="serial_num" placeholder="Serial number of the car" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['car']->serial_num )?>" />
          </li>

          <li>
            <label for="year">Car Year</label>
            <input type="text" name="year" id="year" placeholder="Year of the car" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['car']->year )?>" />
          </li>

          <li>
            <label for="gear_box">Car Gear box</label>
            <input type="text" name="gear_box" id="gear_box" placeholder="Gear box of the car" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['car']->gear_box )?>" />
          </li>

          <li>
            <label for="power">Car Power</label>
            <input type="text" name="power" id="power" placeholder="Power of the car" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['car']->power )?>" />
          </li>

          <li>
            <label for="categoryId">Car Category</label>
            <select name="categoryId">
              <option value="0"<?php echo !$results['car']->categoryId ? " selected" : ""?>>(none)</option>
            <?php foreach ( $results['categories'] as $category ) { ?>
              <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['car']->categoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
            <?php } ?>
            </select>
          </li>

        </ul>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>

      </form>

<?php if ( $results['car']->id ) { ?>
      <p><a href="/mvc/admin/car/deleteCar?carId=<?php echo $results['car']->id ?>" onclick="return confirm('Delete This Car?')">Delete This Car</a></p>
<?php } ?>

