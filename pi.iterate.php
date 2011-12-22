<?php if ( ! defined('APP_VER')) exit('No direct script access allowed');


$plugin_info = array(
	'pi_name'        => 'Iterate',
	'pi_version'     => '1.0',
	'pi_author'      => 'Pixel &amp; Tonic',
	'pi_author_url'  => 'http://pixelandtonic.com',
	'pi_description' => 'Iterates through one or more pipe-delimited strings, passed in as parameters',

	'pi_usage'       => '{exp:iterate breakfast="Coffee|Mimosa|Eggs Benedict"}' . NL
	                  . '    <li>{breakfast}</li>' . NL
	                  . '{/exp:iterate}' . NL . NL
	                  . 'Parameters:' . NL . NL
	                  . '- delimiter="|"' . NL
	                  . '- offset="2"' . NL
	                  . '- limit="5"' . NL . NL
	                  . 'Tags:' . NL . NL
	                  . '- {count}' . NL
	                  . '- {total_results}' . NL
	                  . '- {switch="X|Y|Z"}'
);


/**
 * Iterate
 *
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2011 Pixel & Tonic, Inc
 */
class Iterate {

	public $return_data = '';

	/**
	 * Constructor
	 */
	function __construct()
	{
		$EE =& get_instance();

		if (($delimiter = $EE->TMPL->fetch_param('delimiter')) !== FALSE)
		{
			unset($delimeter);
		}
		else
		{
			$delimiter = '|';
		}
				
		if (($offset = $EE->TMPL->fetch_param('offset')) !== FALSE)
		{
			unset($EE->TMPL->tagparams['offset']);
		}

		if (($limit = $EE->TMPL->fetch_param('limit')) !== FALSE)
		{
			unset($EE->TMPL->tagparams['limit']);
		}
		
		if ($EE->TMPL->tagparams)
		{
			foreach ($EE->TMPL->tagparams as $key => $val)
			{
				$arrays[$key] = array_filter(explode($delimiter, $val));
				$length = count($arrays[$key]);

				if (! isset($longest) || $length > $longest)
				{
					$longest = $length;
				}
			}

			$start = $offset ? $offset : 0;
			$stop = ($limit && ($start + $limit < $longest)) ? $start + $limit : $longest;

			for ($i = $start; $i < $stop; $i++)
			{
				foreach ($arrays as $key => $arr)
				{
					$v[$key] = isset($arr[$i]) ? $arr[$i] : '';
				}

				$vars[] = $v;
			}

			if (isset($vars))
			{
				$this->return_data = $EE->TMPL->parse_variables($EE->TMPL->tagdata, $vars);
			}
		}
	}

}
