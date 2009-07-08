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


if (!defined("OK") || !ar_admin(basename(__file__)))
{
    header('location: ?');
    exit();
}

unset($resultatai, $i, $temp, $lines);
/*$buttons = <<< HTML
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};b,1'">IP {$lang['admin']['bans']}</button>
HTML;*/
$buttons="
<div class=\"btns\">
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};b,1\" class=\"btn\"><span><img src=\"images/icons/bandaid__plus.png\" alt=\"\" class=\"middle\"/>IP {$lang['admin']['bans']}</span></a>
</div>";
lentele($lang['admin']['bans'], $buttons);
unset($buttons, $extra, $text);

if (isset($_GET['d']))
{
    $lines = file('.htaccess');
    $resultatai = array();

    $zodiz = $_GET['d'];
    for ($i = 0; $i < count($lines); $i++)
    {
        $pos = strpos($lines[$i], $zodiz);
		if($pos){$trint=$i;}
    }
   delLineFromFile('.htaccess', $trint+1);
   delLineFromFile('.htaccess', $trint);
	//msg("ka trint?",( $trint)."ir". ($trint+1)."?");
   msg($lang['system']['done'], "IP {$lang['admin']['unbaned']}.");
}
//ip baninimas
if (isset($_GET['b']) && $_GET['b'] == 1)
{
    $title = "IP {$lang['admin']['bans']}"; //Atvaizdavimo pavadinimas
    //$viso = kiek("ban_portai");	//suskaiciuojam kiek isviso irasu
    $forma = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "port"), "IP (xx.xxx.xxx.xx):" => array("type" => "text", "value" => "" . input((isset($url['ip'])) ? $url['ip'] : '') . "", "name" => "ip", ),
        //"Veiksmas:"=>array("type"=>"select","value"=>array("1"=>"Baninti","0"=>"Peradresuoti"),"name"=>"veiksmas"),
        "{$lang['admin']['why']}:" => array("type" => "text", "value" => "", "name" => "priezastis"), "" => array("type" => "submit", "name" => "Portai", "value" => "{$lang['admin']['save']}"));
    if (isset($_POST['ip']) && isset($_POST['priezastis']))
    {
        if (preg_match("/^[0-9]{2,3}[.]{1,1}[0-9]{2,3}[.]{1,1}[0-9]{2,3}[.]{1,1}[0-9]{1,3}$/", $_POST['ip']))
        {
            $sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE ip =INET_ATON(" . escape($_POST['ip']) . ") AND levelis=1");
            if (count($sql) == 0)
            {
                $ip = "#" . $_POST['priezastis'] . "\ndeny from " . $_POST['ip'] . "\n";
                $banip = '.htaccess';
                $fp = fopen($banip, "a");
                $write = fputs($fp, $ip);
                fclose($fp);
                msg($lang['system']['done'], "IP {$_POST['ip']} {$lang['admin']['banned']}.");
                unset($_POST['ip'],$_POST['priezastis']);
            }
            else
            {
                klaida($lang['system']['warning'], "{$lang['admin']['notallowed']}.");
            }
        }
        else
        {
            klaida($lang['system']['warning'], "{$lang['admin']['badip']}.");
        }
    }
}


//Atvaizduojam info ir formas
if (isset($forma) && isset($title))
{
    include_once ('priedai/class.php');
    $bla = new forma();
   lentele($title, $bla->form($forma));
}


/**
 * Banu valdymas
 * Nuskaitom htaccess faila ir gaunam visu banu sarasa
 */

function htaccess_bans()
{
    return;
}

/**
 * Gaunam komentara
 *
 */
function htaccess_all()
{
    foreach ($htaccess as $key => $val)
    {
        if (!empty($val))
            echo comment_htaccess($val);
    }

}

/**
 * Nuskaitom visa htaccess
 *
 * @return str
 */
function read_htaccess()
{
    return file_get_contents('.htaccess');
}
function delLineFromFile($fileName, $lineNum)
{
    global $lang;
    // check the file exists
    if (!is_writable($fileName))
    {
        // print an error
        klaida($lang['system']['error'], $lang['system']['error']);
        // exit the function
        exit;
    }
    else
    {
        // read the file into an array
        $arr = file($fileName);
    }

    // the line to delete is the line number minus 1, because arrays begin at zero
    $lineToDelete = $lineNum - 1;

    // check if the line to delete is greater than the length of the file
    if ($lineToDelete > sizeof($arr))
    {
        // print an error
        klaida($lang['system']['error'], "{$lang['system']['error']} <b>[$lineNum]</b>.");
        // exit the function
        exit;
    }

    //remove the line
    unset($arr["$lineToDelete"]);

    // open the file for reading
    if (!$fp = fopen($fileName, 'w+'))
    {
        // print an error
        klaida($lang['system']['error'], "{$lang['system']['error']} ($fileName)");
        // exit the function
        exit;
    }

    // if $fp is valid
    if ($fp)
    {
        // write the array to the file
        foreach ($arr as $line)
        {
            fwrite($fp, $line);
        }

        // close the file
        fclose($fp);
    }

    //msg($lang['system']['done'],"IP {$lang['admin']['unbaned']}.");
}


/**
 * Gaunam komentarą jei toks yra
 *
 * @param unknown_type $str
 */
function comment_htaccess($str)
{
    if (preg_match('/#.*?$/sim', $str, $regs))
    {
        return $regs[0];
    }
    else
    {
        return "N/A";
    }
}

/**
 * Grazina ip adresus kurie buvo uzdrausti
 *
 * @param unknown_type $str
 * @return unknown
 */
function deny_htaccess($str)
{
    preg_match_all('/(#.*?$)(\n)(deny from )(\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b)/sim', $str, $result, PREG_PATTERN_ORDER);

    foreach ($result[4] as $key => $val)
    {
        $return[$result[4][$key]] = $result[1][$key];
    }
    return @$return;
}

$IPS = deny_htaccess(read_htaccess());
if (count($IPS) > 0)
{
    foreach ($IPS as $key => $val)
    {
        $info[] = array('IP' => $key, $lang['admin']['why'] => trimlink($val, 50), $lang['admin']['action'] => "<a href=\"?id,{$_GET['id']};a,{$_GET['a']};d,{$key}\" title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src=\"images/icons/cross.png\" alt=\"delete\" border=\"0\"></a> ");
    }
}
$title = $lang['admin']['bans'];
//nupiesiam lenteles/sarasus
if (isset($title) && isset($info))
{
    include_once ('priedai/class.php');
    $bla = new Table();
    lentele($title . " - " . count($info), $bla->render($info));
}
unset($_POST['ip'],$_POST['priezastis']);
?>