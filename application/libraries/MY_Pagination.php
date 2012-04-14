<?php

if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * 繼承CI_Pagination
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage application/libraries/
 */
class MY_Pagination extends CI_Pagination
{

	var $full_tag_open = '<ul class="pager">';
	var $full_tag_close = '</ul><div class="c"></div>';
	var $first_tag_open = '<li class="previous">';
	var $first_tag_close = '</li>';
	var $first_tag_text = '&laquo; 較後';
	var $last_tag_open = '<li class="next">';
	var $last_tag_close = '<li>';
	var $last_tag_text = '&laquo; 較前';
	var $use_page_numbers = TRUE;
	var $num_pages;

	public function get_cur_page()
	{
		return ( $this->cur_page < 1 || $this->cur_page > $this->num_pages ) ? 1 : $this->cur_page;
	}

	/**
	 * 重寫了
	 * @access	public
	 * @return	string
	 */
	function create_pages()
	{
		// If our item count or per-page total is zero there is no need to continue.
		if ( $this->total_rows == 0 OR $this->per_page == 0 )
		{
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil( $this->total_rows / $this->per_page );

		$this->num_pages = $num_pages;

		// Is there only one page? Hm... nothing more to do here then.
		if ( $num_pages == 1 )
		{
			return '';
		}

		// Set the base page index for starting page number
		if ( $this->use_page_numbers )
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}

		// Determine the current page number.
		$CI = & get_instance();

		if ( $CI->config->item( 'enable_query_strings' ) === TRUE OR $this->page_query_string === TRUE )
		{
			if ( $CI->input->get( $this->query_string_segment ) != $base_page )
			{
				$this->cur_page = $CI->input->get( $this->query_string_segment );

				// Prep the current page - no funny business!
				$this->cur_page = (int)$this->cur_page;
			}
		}
		else
		{
			if ( $CI->uri->segment( $this->uri_segment ) != $base_page )
			{
				$this->cur_page = $CI->uri->segment( $this->uri_segment );

				// Prep the current page - no funny business!
				$this->cur_page = (int)$this->cur_page;
			}
		}

		// Set current page to 1 if using page numbers instead of offset
		if ( $this->use_page_numbers AND $this->cur_page == 0 )
		{
			$this->cur_page = $base_page;
		}

		$this->num_links = (int)$this->num_links;

		if ( $this->num_links < 1 )
		{
			show_error( 'Your number of links must be a positive number.' );
		}

		if ( !is_numeric( $this->cur_page ) )
		{
			$this->cur_page = $base_page;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ( $this->use_page_numbers )
		{
			if ( $this->cur_page > $num_pages )
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ( $this->cur_page > $this->total_rows )
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}

		$uri_page_number = $this->cur_page;

		if ( !$this->use_page_numbers )
		{
			$this->cur_page = floor( ($this->cur_page / $this->per_page) + 1 );
		}

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ( $CI->config->item( 'enable_query_strings' ) === TRUE OR $this->page_query_string === TRUE )
		{
			$this->base_url = rtrim( $this->base_url ) . '&amp;' . $this->query_string_segment . '=';
		}
		else
		{
			$this->base_url = rtrim( $this->base_url, '/' ) . '/';
		}

		// And here we go...
		$output = '';
		if ( $this->cur_page == 1 )
		{
			$next = $this->cur_page + 1;
			$output .= $this->first_tag_open . '<a href="' . $this->base_url . $next . '" rel="prev">'.$this->first_tag_text.'</a>' . $this->first_tag_close;
		}
		else if ( $this->cur_page == $num_pages )
		{
			$next = $this->cur_page - 1;
			$output .= $this->last_tag_open . '<a href="'. $this->base_url . $next .'" rel="next">'.$this->last_tag_text.'</a>' . $this->last_tag_close;
		}
		else
		{
			$next = $this->cur_page + 1;
			$output .= $this->first_tag_open . '<a href="'. $this->base_url . $next .'" rel="prev">'.$this->first_tag_text.'</a>' . $this->first_tag_close;
			$next = $this->cur_page - 1;
			$output .= $this->last_tag_open . '<a href="'. $this->base_url . $next .'" rel="next">'.$this->last_tag_text.'</a>' . $this->last_tag_close;
		}
		return $this->full_tag_open . $output . $this->full_tag_close;
	}

}

// END MY_Pagination Class

/* End of file MY_Pagination.php */
/* Location: ./application/libraries/MY_Pagination.php */