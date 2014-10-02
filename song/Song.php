<?php

class Song
{
	public $url;
	public $customName;
	public $tags;
	public $notes;
	public $name;

	function __construct( $url, $customName, $tags, $notes, $name )
	{
		$this->url = $url;
		$this->customName = $customName;
		$this->tags = $tags;
		$this->notes = $notes;
		$this->name = $name;
	}
}