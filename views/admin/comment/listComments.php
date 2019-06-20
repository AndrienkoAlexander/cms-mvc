      <h1>All Comments</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Comment Date Time</th>
          <th>Comment</th>
          <th></th>
        </tr>

<?php foreach ( $results['comments'] as $comment ) { ?>

        <tr>
          <td><?php echo date("d.m.Y H:i", strtotime($comment->date_time))?></td>
          <td>
            <?php echo $comment->message?>
          </td>
          <td>
            <?php if ( $comment->id ) { ?>
            <?php if($comment->shown) {?>
            <a href="/mvc/admin/comment/shownComment?commentId=<?php echo $comment->id ?>" onclick="return confirm('Disable this Comment?')">Disable comment</a>
            <?php } else {?>
            <a href="/mvc/admin/comment/shownComment?commentId=<?php echo $comment->id ?>" onclick="return confirm('Enable this Comment?')">Enable comment</a>
            <?php }?>
            <?php } ?>
          </td>
        </tr>

<?php } ?>

      </table>

      <p><?php echo $results['totalRows']?> coment<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
