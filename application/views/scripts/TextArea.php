<?php
    $elem = $this->element;
    $elemName = $elem->getName();
    $label = $elem->getLabel();
    $options = $this->element->getDecorator('ViewScript')->getOptions();
    $language = My_Model::get('Languages')->getById($options['languageId']);
?>

<fieldset class="extspc">
    <?php if ($language === null) { ?>
        <p class="question-text"><?php echo $this->escape($label);?></p><?php
    } else {
        ?><pre><code class="language-<?php echo $language->getkod(); ?>"><?php echo $this->escape($label); ?></code></pre> <?php
    } ?>
    <textarea name="<?php echo $elemName; ?>" id="<?php echo $elemName; ?>" placeholder="Fill in your answer..." class="question-answer"/></textarea>
</fieldset>
