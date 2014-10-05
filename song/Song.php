<?php

class Song
{
	public $id;
	public $url;
	public $customName;
	public $tags;
	public $notes;
	public $name;
	public $image;

	function __construct( $id, $url, $customName, $tags, $notes, $name, $image )
	{
		$this->id = $id;
		$this->url = $url;
		$this->customName = $customName;
		$this->tags = $tags;
		$this->notes = $notes;
		$this->name = $name;
		$this->image = $image;
	}
}