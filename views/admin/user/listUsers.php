      <h1>All Users</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>IP</th>
          <th></th>
        </tr>

<?php foreach ( $results['users'] as $user ) { ?>

        <tr>
          <td><?php echo $user->name ?></td>

          <td><?php echo $user->email ?></td>

          <td><?php echo $user->IP ?></td>

          <td>
            <a href="/mvc/admin/user/deleteUser?userId=<?php echo $user->id ?>" onclick="return confirm('Delete this User?')">Delete user</a>
          </td>
        </tr>

<?php } ?>

      </table>

      <p><?php echo $results['totalRows']?> user<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>