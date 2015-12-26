<?php
    $elem = $this->element;
    $elemName = $elem->getName();
    $values = $elem->getValue();
    $label = $elem->getLabel();
?>

<fieldset class="mar10">
    <code class="language-java"><?php echo $label; ?></code>
    <br/>
<?php foreach($elem->getMultiOptions() as $option=>$value){ ?>
        <input type="checkbox" name="<?php echo $elemName; ?>[]" id="<?php echo $elemName; ?>-<?php echo $option; ?>" value="<?php echo $option; ?>" <?php if($values && in_array($option, $values)){ echo ' checked="checked"'; }?> />
           <code class="language-java"> <?php echo $value; ?> </code>
        <br/>
<?php } ?>
</fieldset>
