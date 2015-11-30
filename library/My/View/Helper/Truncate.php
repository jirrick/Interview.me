<?php
class My_View_Helper_Truncate extends Zend_View_Helper_Abstract
{
    public function truncate($text, $length = 100, $options = array()) 
    {
		$default = array(
			'ending' => '...', 'exact' => false
		);
		$options = array_merge($default, $options);
		extract($options);
        if (mb_strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = mb_substr($text, 0, $length - mb_strlen($ending));
		}
		if (!$exact) {
			$spacepos = mb_strrpos($truncate, ' ');
			if (isset($spacepos)) {
				
				$truncate = mb_substr($truncate, 0, $spacepos);
			}
		}
		$truncate .= $ending;
		return $truncate;
	}
}