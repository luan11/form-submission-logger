<?php
function displayView($view, $data) {
	extract($data);
	
	include $view;
}