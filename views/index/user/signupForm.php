      <form action="/mvc/user/signup" method="post" style="width: 50%;">
        <input type="hidden" name="signup" value="true" />

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

        <ul>

          <li>
            <label for="username">Username</label>
            <input type="text" name="name" id="name" placeholder="Your username" required autofocus maxlength="20" />
          </li>

          <li>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Your email" required autofocus maxlength="20" />
          </li>

          <li>
            <label for="password1">Password</label>
            <input type="password" name="password1" id="password1" placeholder="Your password" required maxlength="20" />
          </li>

          <li>
            <label for="password2">Password</label>
            <input type="password" name="password2" id="password2" placeholder="Repeat your password" required maxlength="20" />
          </li>


        </ul>

        <div class="buttons">
          <input type="submit" name="signup" value="Sign up" />
        </div>

      </form>