<?php
    if(!empty($city))
    { ?>

       <option value=''>Select</option>

       <?php
               foreach($city as $city)
               { ?>

                      <option value="<?php echo $city->id; ?>"><?php echo $city->name; ?></option>


               <?php }
       ?>


    <?php } else { ?>

    <option value=''>Select</option>

    <?php }
?>