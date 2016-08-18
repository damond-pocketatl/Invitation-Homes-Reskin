<?php
function get_current_filter_url( $p_id = '25')
	{
		$link = get_permalink( $p_id );
		$query_s = ( isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!="")?$_SERVER['QUERY_STRING']:'';
		if($query_s!="")
		{
			$query_s_array = explode("&", $query_s);
			if(is_array($query_s_array) && count($query_s_array) > 0)
			{
				foreach ($query_s_array as $key=>$val) :
					$valArray =  explode("=", $val);

					if(is_array($valArray ) && $valArray[1])
						$link = add_query_arg( $valArray[0],  $valArray[1], $link );
				endforeach;
			}
		}
		return $link;
	}

function get_current_filter_key($arg)
	{
		if (isset($_GET[ $arg ])) $current_filter = explode(',', $_GET[ $arg ]); else $current_filter = array();
		if (!is_array($current_filter)) $current_filter = array();
		return $current_filter;
	}