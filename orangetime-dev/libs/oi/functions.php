<?php
defined('ABSPATH') or die();

/**
 * Väljastab keelevaliku menüü
 *
 * Muuda vaikeväärtuseid $args:
 *
 * bool dropdown - Kas kuvada keelevalikud dropdown menüüna või mitte (eeldab Bootstrapi).
 * bool echo - Valikuline, Vaikemisi TRUE. Kas väljastada või tagastada.
 *
 * @since 1.0.0
 *
 */
function oi_the_lang_switch( $args = array() )
{
	$defaults = array(
		'dropdown' => false,
		'echo' => true
	);
	$args = wp_parse_args( $args, $defaults );
	$args = (object) $args;

	if( function_exists( 'icl_get_languages' ) )
	{
		global $sitepress;
		$output = array();
		$default_lang_code = $sitepress->get_default_language();
		$langs = icl_get_languages('skip_missing=0&orderby=code');

		$langs = array_map( create_function('$key', '

			switch($key["language_code"])
			{
				case "ru" : $abbr = "RU"; break;
				case "en" : $abbr = "EN"; break;
				case "et" : $abbr = "ET"; break;
				case "lt" : $abbr = "LT"; break;
				case "lv" : $abbr = "LV"; break;
				default:
					$abbr = $key["language_code"];
				break;
			}

			return array(
				"language_code"	=> $key["language_code"],
				"language_abbr"	=> $abbr,
				"native_name"	=> $key["native_name"],
				"url"			=>	$key["url"]
			);'

		), $langs );

		$first_lang = array( $default_lang_code => $langs[$default_lang_code] );
		unset( $langs[$default_lang_code] );

		$langs = array_merge((array) $first_lang, (array) $langs);

		if( count( $langs ) )
		{

			if( true === $args->dropdown )
			{

				$output[] = '<div class="dropdown lang-switch">';
				$output[] = sprintf('<button type="button" class="dropdown-toggle" id="langswitch" data-toggle="dropdown" aria-expanded="false"><span>%s</span></button>', $langs[ICL_LANGUAGE_CODE]['language_abbr']);
				$output[] = '<ul class="dropdown-menu" role="menu" aria-labelledby="langswitch">';

				foreach( $langs as $lang )
				{
					if( ICL_LANGUAGE_CODE == $lang['language_code'] )
						continue;

					array_push($output,
						sprintf('<li class="lang-switch__item" role="presentation"><a href="%s" rel="alternate" hreflang="%s" title="%s" class="lang-switch__url" role="menuitem" tabindex="-1">%s</a></li>',
							esc_url( $lang['url'] ),
							$lang['language_code'],
							$lang['native_name'],
							$lang['native_name']
						)
					);
				}

				$output[] = '</ul>';
				$output[] = '</div>';

			} else {

				$output[] = '<ul class="lang-switch">';

				foreach( $langs as $lang )
				{
					array_push($output,
						sprintf('<li%s><a href="%s" rel="alternate" hreflang="%s" title="%s" class="lang-switch__url">%s</a></li>',
							( ICL_LANGUAGE_CODE == $lang['language_code'] ) ? ' class="lang-switch__item  current-item"' : ' class="lang-switch__item"',
							esc_url( $lang['url'] ),
							$lang['language_code'],
							$lang['native_name'],
							$lang['language_abbr']
						)
					);
				}

				$output[] = '</ul>';
			}
		}

		if( true === $args->echo )
			echo implode("\n", $output);
		else
			return implode("\n", $output);
	}
}

/**
 * Väljastab alammenüü. Teeb vaikimisi valiku aktiivse esimese astme menüü punkti põhjal.
 *
 *
 * Muuda vaikeväärtuseid $args:
 *
 * string theme_location - Millist menüüd soovitakse sõeluda.
 * string xpath - Valikuline. xPath süntaks.
 * string before - Valikuline. Mida lisada enne alammenüüd. Tegemist siis HTML-iga.
 * string after - Valikuline. Mida lisada peale alammenüüd. Tegemist siis HTML-iga.
 * bool echo - Valikuline, Vaikemisi TRUE. Kas väljastada või tagastada.
 *
 * @param array $args Argumendid
 * @return String Kui $echo väärtus on FALSE.
 *
 * @since 1.0.0
 *
 */
function oi_the_submenu( $args = array() )
{

	$defaults = array(
		'theme_location' => 'mainnav',
		'xpath' => "./li[contains(@class,'current-menu-item') or contains(@class,'current-menu-ancestor')  or contains(@class,'current-post-parent') ]/ul",
		'before' => '<div class="mainnav-submenu-section">',
		'after' => '</div>',
		'echo' => true
	);
	$args = wp_parse_args( $args, $defaults );
	$args = (object) $args;

	$output = array();

	$menu_tree = wp_nav_menu( array( 'theme_location' => $args->theme_location, 'container' => '', 'echo' => false ) );
	$menu_tree_XML = new SimpleXMLElement( $menu_tree );
	$path = $menu_tree_XML->xpath( $args->xpath );




	if( ! empty( $path ) )
	{
		$output[] = $args->before;
		$output[] = $path[0]->asXML();
		$output[] = $args->after;
	}

	if( $args->echo )
		echo implode('', $output );
	else
		return implode('', $output );

}


/**
 * Abifunktsioonid
 *
 *
 * @since 1.0.0
 *
 */
function pre($var, $dont_print = 0, $exclude_html = 0)
{
	$result = '';

	if (!$exclude_html) {
		$result .= '<hr size=1><b>Variable:</b><br><pre>';
	}

	ob_start();
	print_r($var);
	$result .= ob_get_contents();
	ob_end_clean();

	if (!$exclude_html) {
		$result .= '</pre><hr size=1>';
	}

	if ($dont_print){

		return $result;

	} else {

		echo $result;

	}
}

/**
 * Võimaldab kontrollida, milline menüü on aktiivne.
 * Vajalik siis, kui on soov veenduda, kas külastaja kasutas lehel liikumiseks X menüüd (ID)
 *
 * @since 1.0.0
 *
 */

function oi_is_menu($menu_name)
{
	$is = false;

	if( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) )
	{
		$items = wp_get_nav_menu_items($locations[$menu_name]);
		_wp_menu_item_classes_by_context($items);

		foreach($items as $item)
		{
			if($item->current == 1)
			{
				$is = true;
				break;
			}
		}
	}

	return $is;
}

/**
 * Kuva ekraanil kõik filtrid, mis registreeritud
 * Näiteks: list_hooked_functions('wp_head');
 *
 * Allikas: http://www.rarst.net/
 *
 * @since 1.0.0
 *
 */

function list_hooked_functions($tag=false)
{
	global $wp_filter;
	if ($tag)
	{
		$hook[$tag]=$wp_filter[$tag];
		if (!is_array($hook[$tag]))
		{
			trigger_error("Nothing found for '$tag' hook", E_USER_WARNING);
			return;
		}
	}
	else {
		$hook=$wp_filter;
		ksort($hook);
	}
	echo '<pre>';
	foreach($hook as $tag => $priority)
	{
		echo "<br />TAG =&gt; <strong>$tag</strong><br />";
		ksort($priority);
		foreach($priority as $priority => $function)
		{
			echo $priority . ' (priority)';
			foreach($function as $name => $properties)
			{
				echo " =&gt; $name<br />";
			}
		}
	}
	echo '</pre>';
	return;
}

/**
 * "sprintf" ja "printf" funktsioonide edasiarendus
 *
 * Kasutatakse nii:
 *
 * 		$foo = array('age' => 5, 'name' => 'john');
 *   	echo oi_template("%(name)s is %(age)s years old", $foo);
 *
 * @since 1.0.0
 *
 */
function oi_template($template, $args)
{
	$names = preg_match_all('/%\((.*?)\)/', $template, $matches, PREG_SET_ORDER);

	$values = array();
	foreach($matches as $match) {
		$values[] = $args[$match[1]];
	}

	$template = preg_replace('/%\((.*?)\)/', '%', $template);
	return vsprintf($template, $values);
}


