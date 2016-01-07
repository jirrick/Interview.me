<?php
    $elem = $this->element;
    $elemName = $elem->getName();
    $label = $elem->getLabel();
    $options = $this->element->getDecorator('ViewScript')->getOptions();
    $language = My_Model::get('Languages')->getById($options['languageId']);
?>

<fieldset class="mar10">
    <div class="question-separator">Question</div>
    <?php if ($language === null) { ?>
        <span class="question-text"><?php echo $this->escape($label);?></span> <?php
    } else {
        ?><pre><code class="language-<?php echo $language->getkod(); ?>"><?php echo $this->escape($label); ?></code></pre> <?php
    } ?>
    <textarea name="<?php echo $elemName; ?>" id="<?php echo $elemName; ?>" class="question-answer"/>
    </textarea>
</fieldset>
