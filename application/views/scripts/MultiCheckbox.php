<?php
    $elem = $this->element;
    $elemName = $elem->getName();
    $values = $elem->getValue();
    $label = $elem->getLabel();
    $options = $this->element->getDecorator('ViewScript')->getOptions();
    $language = My_Model::get('Languages')->getById($options['languageId']);
?>

<fieldset class="extspc">
    <?php if ($language === null) { ?>
        <span class="question-text own-qt-ex"><?php echo $this->escape($label);?></span> <?php
    } else {
        ?><pre><code class="language-<?php echo $language->getkod(); ?>"><?php echo $this->escape($label); ?></code></pre> <?php
    } ?>
    
    <br/>
<?php foreach($elem->getMultiOptions() as $option=>$value){ ?>
        <input type="checkbox" class="question-options" name="<?php echo $elemName; ?>[]" id="<?php echo $elemName; ?>-<?php echo $option; ?>" value="<?php echo $option; ?>" <?php if($values && in_array($option, $values)){ echo ' checked="checked"'; }?> />
           <?php if ($language === null) { ?>
                <span class="option-text"><?php echo $this->escape($value);?></span> <?php
            } else {
                ?><code class="language-<?php echo $language->getkod(); ?>"><?php echo $this->escape($value); ?></code> <?php
            } ?>
        <br/>
<?php } ?>
</fieldset>
