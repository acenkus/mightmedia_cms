<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) {
	$k = escape(ceil((int)$url['k']));
} else {
	$k = 0;
}
$limit = 50;
$text = '';

//Kategorijų sąrašas
$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai'  ORDER BY `pavadinimas`");
if ($sqlas && mysql_num_rows($sqlas) > 0 && !isset($url['m'])) {
	while ($sql = mysql_fetch_assoc($sqlas)) {
		$path = mysql_fetch_assoc(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' ORDER BY `pavadinimas`"));
		$path1 = explode(",", $path['path']);

		if ($path1[(count($path1) - 1)] == $k) {
			$sqlkiek = kiek('straipsniai', "WHERE `kat`=" . escape($sql['id']) . " AND `rodoma`='TAIP'");
			$info[] = array(" " => "<img src='images/naujienu_kat/" . $sql['pav'] . "' alt='Kategorija' border='0' />", "{$lang['category']['about']}" => "<h2><a href='?id," . $url['id'] . ";k," . $sql['id'] . "'>" . $sql['pavadinimas'] . "</a></h2>" . $sql['aprasymas'] . "<br>", "{$lang['category']['articles']}" => $sqlkiek, );
		}
	}
	include_once ("priedai/class.php");
	$bla = new Table();
	if (isset($info)) {
		lentele("{$lang['system']['categories']}", $bla->render($info), false);
	}

}
//Kategorijų sąrašo pabaiga
//Jei pasirinkta kategoriją
if ($k >= 0 && empty($url['m'])) {

	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `rodoma`='TAIP' 
		AND `kat`='" . $k . "' ORDER BY `date` DESC LIMIT $p, $limit") or die(mysql_error());
	$pav = mysql_fetch_assoc(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='$k' "));
	$viso = mysql_num_rows($sql);


	if ($viso > 0) {
		if (LEVEL >= $sqlas['teises'] || LEVEL == 1 || LEVEL == 2) {
			while ($row = mysql_fetch_assoc($sql)) {
				if (isset($conf['puslapiai']['straipsnis.php']['id'])) {
					$text .= "<h1>" . $row['pav'] . "</h1>
				<i>" . $row['t_text'] . "</i><br><a href=?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";m," . $row['id'] . ">{$lang['article']['read']}</a><hr></hr>\n";

				}
			}

			lentele($pav['pavadinimas'], $text, false, array('Viso', $viso));
		} else {
			klaida($lang['system']['warning'], "{$lang['article']['cant']}.");
		}
		if ($viso > $limit) {
			lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
		}
		unset($text, $row, $sql);

	} else {
		klaida($lang['system']['warning'], $lang['system']['no_content']);
	}
} elseif (!empty($url['m'])) {


	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `rodoma`='TAIP' AND `id`='" . (int)$url['m'] . "' LIMIT 1 ") or die(mysql_error());


	$row = mysql_fetch_assoc($sql);
	$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $row['kat'] . "' AND `kieno`='straipsniai' ORDER BY `pavadinimas` LIMIT 1") or die(mysql_error());
	$sqlas = mysql_fetch_assoc($sqlas);
	if ($sql && teises($sql['teises'], $_SESSION['level'])) {
		$text = "<blockquote><i>" . $row['t_text'] . "</i><br><hr></hr><br>\n
		" . $row['f_text'] . "</blockquote>
		<hr />{$lang['article']['date']}: " . date('Y-m-d H:i:s ', $row['date']) . "; {$lang['article']['author']}: <b>" . $row['autorius'] . "</b>";
		lentele($row['pav'], $text);
		include ("priedai/komentarai.php");

		komentarai($url['m'], true);
	} else {
		klaida($lang['system']['warning'], "{$lang['article']['cant']}.");
	}
}

?>