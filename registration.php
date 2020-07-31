<?php 
	require_once("Helper/Akamai/TokenAuth.php"); 
	require_once("Helper/Akamai/InvalidArgumentException.php"); 
	\Magento\Framework\Component\ComponentRegistrar::register(\Magento\Framework\Component\ComponentRegistrar::MODULE, 'SolidStateNetworks_ddpmodule',__DIR__); 
?>