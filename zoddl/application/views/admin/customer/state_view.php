<?php
    if(!empty($state))
    { ?>

       <option value=''>Select</option>

       <?php
               foreach($state as $state)
               { ?>

                      <option value="<?php echo $state->id; ?>"><?php echo $state->name; ?></option>


               <?php }
       ?>


    <?php } else { ?>

    <option value=''>Select</option>

    <?php }
?>