<?php
	class Filter
	{
		public $title = "";
		public $noGenres = [];
		public $genres = [];
		public $uploader = "";
		private $mysql;
		function __construct( $mysql, $filter )
		{
			error_reporting(E_ERROR | E_PARSE);
			$this->mysql = $mysql;
			preg_match_all( '/@(\w)+/', $filter, $this->title );
			try
			{
				$this->title = $this->title[0][0];
			}
			catch( Exception $ex )
			{
				$title = "";
			}
			preg_match_all( '/\+(\w)+/', $filter, $this->genres );
			$this->genres = $this->genres[0];
			preg_match_all( '/-(\w)+/', $filter, $this->noGenres );
			$this->noGenres = $this->noGenres[0];
			preg_match_all( '/#(\w)+/', $filter, $this->uploader );
			try
			{
				$this->uploader = $this->uploader[0][0];
			}
			catch( Exception $ex )
			{
				$this->uploader = "";
			}
		}

		public function ParseFiltersToMySQL()
		{
			$builder = "WHERE";
			if( $this->title )
			{
				$builder .= " name LIKE '%" . mysqli_real_escape_string( $this->mysql, str_replace( "@", "", $this->title ) ) . "%'";
			}
			else
			{
				$builder .= " name LIKE '%%'";
			}

			if( $this->noGenres )
			{
				foreach( $this->noGenres as $noGenre )
				{
					$builder .= " AND genre NOT LIKE '%" .  mysqli_real_escape_string( $this->mysql, str_replace( "-", "", $noGenre ) ) . "%'";
				}
			}
			if( $this->genres )
			{
				foreach( $this->genres as $genre )
				{
					$builder .= " AND genre LIKE '%" .  mysqli_real_escape_string( $this->mysql, str_replace( "+", "", $genre ) ) . "%'";
				}
			}
			if( $this->uploader )
			{
				$builder .= " AND uploader LIKE '%" .  mysqli_real_escape_string( $this->mysql, str_replace( "#", "", $this->uploader ) ) . "%'";
			}

			return $builder;
		}
	}
?>