<?php
/**
 * Created by PhpStorm.
 * User: Christiaan Goslinga
 * Date: 21-11-2016
 * Time: 13:36
 */

class bolcom extends CI_Controller
{
    public static $apiClient = null;
    public static $bolPartnerSiteId;

//    function __autoload($className)
//    {
//        $fileName = preg_replace('/^BolCom\\\\(\w+)/', '../libraries/bolcom/$1.php', $className);
//        // var_dump($fileName);
//        print_r($fileName);
//        if (file_exists($fileName)) {
//            return require_once $fileName;
//        }
//    }

    public function run()
    {
        foreach (glob(APPPATH."/libraries/bolcom/*.php") as $filename)
        {
            //print_r($filename);
            include_once $filename;
        }

        $bol_api_key = '9230D01DCDE7401EAA7339F19AC7FF8C';
        $bol_api_format = 'json';
        $bol_api_debug_mode = 0;
        $bol_api_library_version = 'v.2.1.0';
        self::$bolPartnerSiteId = '12345';
        self::$apiClient = new Client($bol_api_key, $bol_api_format, $bol_api_debug_mode);

//        switch (isset($_GET["action"])) {
//            case 'search' :
//                $this->search($param = '');
//                break;
//        }

        // search products /catalog/v4/search/ + queryParams
        if (!isset($params['q'])) {
            $gamename = $this->session->game;
            $q = $gamename["name"];
            $ids = '18200';
            $pids = 0;
            $searchfield = "";
            //self::printValue('Performing searchresults request based on q = "' . $q . '", ids = "Nederlandse boeken (1430)", "Nederlandse boeken (8293)" and "Tot &euro; 30 (4855)", 5 items and sort on "sales_ranking"');
        } else {
            $q = urldecode($params['q']);
            $ids = (!isset($params['ids']) ? null : $params['ids']);
            $pids = (!isset($params['pids']) ? null : $params['pids']);
            $searchfield = (!isset($params['searchfield']) ? null : $params['searchfield']);
            //self::printValue('Performing searchresults request based on q = "' . $q . '", ids = "' . $ids . '" and sort on "sales_ranking"');
        }
        //self::printValue('----');
        $response = self::$apiClient->getSearch($q, $ids, $pids, 0, 10, 'sales_ranking', false, true, true, true, '', $searchfield);

//        $data["gameresponse"] = $response->products[0]->attributeGroups[0]->attributes[2]->value;

        $genre = array(25080=>"Actie",25081=>"Avontuur",25082=>"Race",25083=>"Open wereld",25084=>"Shooter",25085=>"Sport",25086=>"Role Playing Game (RPG)",25087=>"Platform",25088=>"Simulatie",25089=>"Strategie",
                       25090=>"Vecht",25091=>"Muziek",25092=>"Party",25093=>"Japanase Role Playing Game (JRPG)",25094=>"Masive Multiplayer Online (MMO)",25095=>"Puzzel",25096=>"Educatie");

        $gameresponse = "";
        $numItems = count($response->products);
        $i = 0;

        foreach($response->products as $producten)
        {
            if(++$i !== $numItems)
            {
                $gameresponse .= $producten->attributeGroups[0]->attributes[2]->value . "|";
            }

            else
            {
                $gameresponse .= $producten->attributeGroups[0]->attributes[2]->value;
            }
        }

        $searchgenres = array_unique(explode("|", $gameresponse));
        $genreid= "";

        foreach($searchgenres as $genrename)
        {
            $genreid .= ", " . array_search($genrename,$genre);
        }

        $data["genreid"] = $genreid;
        $data["gameresponse"] = array_unique(explode("|", $gameresponse));

        $data["gamename"] = $this->session->game;

        $ids = '18200' . $genreid;
        $sorting = 'rankasc';
        $response = self::$apiClient->getLists('toplist_default', $ids, 0, 10, $sorting, false, true, false, false);


        $list = array();
        foreach ($response->products as $child) {
            $list[] = new Product($child);
        }

        $recomend = "";
        foreach ($list as $item) {
            $recomend .= $this->printProduct($item);
        }

        $data["recomend"] = $recomend;

        $this->load->view('bolcom/run', $data);
    }

    private function printProduct($product, $title = null)
    {
        $result = "";
        $result .= '<pre>';
        if ($title)
            $result .= $title . ":\n";
        else
            $result .= "Product:\n";
        $result .= 'id: ' . $product->getId() . "\n";
        $result .= 'title: ' . $product->getTitle() . "\n";
        $result .= 'price: ' . $product->getFirstAvailablePrice() . "\n";
        $result .= '</pre>';
        return $result;
    }
//
//    public function search($params)
//    {
//        // search products /catalog/v4/search/ + queryParams
//        if (!isset($params['q'])) {
//            $q = 'Fallout 4';
//            $ids = '18200';
//            $pids = 0;
//            $searchfield = "";
//            //self::printValue('Performing searchresults request based on q = "' . $q . '", ids = "Nederlandse boeken (1430)", "Nederlandse boeken (8293)" and "Tot &euro; 30 (4855)", 5 items and sort on "sales_ranking"');
//        } else {
//            $q = urldecode($params['q']);
//            $ids = (!isset($params['ids']) ? null : $params['ids']);
//            $pids = (!isset($params['pids']) ? null : $params['pids']);
//            $searchfield = (!isset($params['searchfield']) ? null : $params['searchfield']);
//            //self::printValue('Performing searchresults request based on q = "' . $q . '", ids = "' . $ids . '" and sort on "sales_ranking"');
//        }
//        //self::printValue('----');
//        $response = self::$apiClient->getSearch($q, $ids, $pids, 0, 10, 'sales_ranking', false, true, true, true, '', $searchfield);
//        $data["gameresponse"] = $response->products[0]->attributeGroups[0]->attributes[2]->value;
//
//        $this->load->view('bolcom/run', $data);
//    }
}
?>