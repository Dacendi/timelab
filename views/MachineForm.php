<?php
/**
 * Created by PhpStorm.
 * User: Sébastien BAZAUD (alias Dacendi)
 * Date: 01/01/2016
 * Time: 15:28
 * Project: timelab
 */

var_dump($machine);

?>
<div class="wrap">
    <h2><?php _e( "Add machine" , 'timelab' ) ?></h2>
    <form id="<?php echo MachineFormManager::MAC_FOR_ID ?>" method="post" action="#" name="<?php echo MachineFormManager::MAC_FOR_NAME ?>">
        <div id="machinestuff">
            <div id="machine-body" class="metabox-holder columns-2">
                <div id="machine-body-content" class="machine-container">
                    <ul>
                        <li>
                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="<?php echo MachineFormManager::MAC_FOR_TITLE ?>"><?php _e("Machine title" , 'timelab') ?></label>
                            <input id="<?php echo MachineFormManager::MAC_FOR_TITLE ?>" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" type="text" name="<?php echo MachineFormManager::MAC_FOR_TITLE ?>" value="<?php echo $machine->getTitle() ?>" placeholder="<?php _e("Machine title" , 'timelab') ?>">
                        </li>

                        <li>
                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="<?php echo MachineFormManager::MAC_FOR_COD ?>"><?php _e("Machine code" , 'timelab') ?></label>
                            <input id="<?php echo MachineFormManager::MAC_FOR_COD ?>" name="<?php echo MachineFormManager::MAC_FOR_COD ?>" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" type="text" readonly placeholder="<?php _e("auto-generated code" , 'timelab') ?>" value="<?php echo $machine->getCode() ?>" maxlength="8" ><?php /* TODO: rendre optionnel maxlength*/ ?>

                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="machineidfw"><?php _e("First word only", 'timelab') ?></label>
                            <input id="machineidfw" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" type="checkbox" name="machineidfw" >

                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="machineidfi"><?php _e("Force code", 'timelab') ?></label>
                            <input id="machineidfi" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" type="checkbox" name="machineidfi" >
                        </li>

                        <li>
                            <br/>
                        </li>

                        <li>
                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="machinepic"><?php _e( "Icon" , 'timelab' ) ?></label>
                            <input id="machinepic" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" type="file" name="machinepic">
                        </li>

                        <li>
                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="<?php echo MachineFormManager::MAC_FOR_FIRST_DATE ?>"><?php _e( "Date of machine commissioning" , 'timelab' ) ?><span> </span>:</label>
                            <input id="<?php echo MachineFormManager::MAC_FOR_FIRST_DATE ?>" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" type="date" name="<?php echo MachineFormManager::MAC_FOR_FIRST_DATE ?>" value="<?php echo $machine->getStartDate() ?>">
                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="machinelastdate"><?php _e( "Date of machine uncommissioning" , 'timelab' ) ?></label>
                            <input id="machinelastdate" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" type="date" name="machinelastdate">
                        </li>

                        <li>
                            <br/>
                        </li>

                        <li>
                            <label form="<?php echo MachineFormManager::MAC_FOR_ID ?>" for="machinedesc"></label>
                            <?php wp_editor( null, "machinedesc") ?>
                        </li>
                    </ul>
                    <!-- machine editor -->

                    <!-- end of machine editor -->
                </div>
                <div id="machine-container-1" class="machine-container">
                    <!-- machine submit -->
                    <div class="machine-box">
                        <p><?php _e("Machine submit"); ?></p>
                        <input type="submit" form="<?php echo MachineFormManager::MAC_FOR_ID ?>" class="button button-primary button-large" value="<?php _e( "Save" ) ?>">
                    </div>
                    <div class="machine-box">
                        <p><?php _e("Machine mark" , 'timelab') ?></p>
                    </div>
                    <!-- end of machine submit -->
                </div>
                <div id="machine-container-2" class="machine-container">
                    <p><?php _e("More about"); ?></p>
                </div>
            </div>
        </div>
    </form>
</div>
