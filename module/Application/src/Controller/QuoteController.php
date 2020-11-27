<?php
namespace Application\Controller;

use Zend\RecursiveIteratorIterator;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class QuoteController extends AbstractRestfulController
{


    public function index()
    {
        return getListAction();
    }

    public function getListAction()
    {

        $ip = @$_SERVER['REMOTE_ADDR'];

        try {

            $author = $this->params()->fromRoute('author');
            $limit = $this->params()->fromQuery('limit');

            $quotes = $this->getQuotes($ip);

            $quotes_filtered = $this->filterQuotes($quotes,$author,$limit);

            $json_data = new JsonModel(array('data' => $quotes_filtered));

        }
        catch(\Exception $e) {
            $json_data = new JsonModel(array('error' => $e->getMessage()));

        }

        return $json_data;
    }

    private function filterQuotes($quotes,$author,$limit)
    {
        if (!$limit)
            $limit = 10;

        if ($limit > 10)
            throw new \Exception("Error. Limit mas be equal or less than 10.");

        if (!$quotes)
            throw new \Exception("Error. No quotes found for this author.");

        $count = 0;
        $quotes_filtered = [];
        foreach ($quotes as $quote) {
            if ($quote['author'] == $author || $author == '') {

                $single_quote = $quote['quote'];

                if (substr($single_quote, -1) === '.') {
                    $single_quote = substr($single_quote, 0, -1);
                }

                $single_quote = strtoupper(utf8_decode($single_quote)).'!';

                $quotes_filtered[] = $single_quote;

                if (++$count > $limit - 1)
                    break;
            }
        }

        return $quotes_filtered;
    }

    private function getQuotes($ip) {
        $file = "data/cache/quotes/".$ip.'.json';

        if ($this->updateCache($file)) {

            // TODO: Call API to get quotes instead of JSON

            $string = file_get_contents('data/quotes.json');
            $string = mb_convert_encoding($string, 'UTF-8',
                mb_detect_encoding($string, 'UTF-8, ISO-8859-1', true));

            if ($string !== false) {
                $json_arr = json_decode($string, true);
                $quotes = $json_arr['quotes'];
            }

            file_put_contents($file, $string);

        }

        return $quotes;
    }
    private function updateCache($file) {
        $update_cache = true;
        $update_cache_time_limit = 10;

        $date_file = date ("Y/m/d H:i", @filectime ($file));
        $date_file_time = strtotime($date_file);
        $date_now_time = time();
        $diff = $date_now_time - $date_file_time;
        $h = ($diff/(60*60))%24;
        $m = ($diff/60)%60;

        if ($m > $update_cache_time_limit)
            $update_cache = true;

        return $update_cache;
    }

    public function get($id)
    {   // Action used for GET requests with resource Id
        return new JsonModel(array("data" => array('id'=> 2, 'name' => 'Coda', 'band' => 'Led Zeppelin')));
    }

    public function create($data)
    {   // Action used for POST requests
        return new JsonModel(array('data' => array('id'=> 3, 'name' => 'New Album', 'band' => 'New Band')));
    }

    public function update($id, $data)
    {   // Action used for PUT requests
        return new JsonModel(array('data' => array('id'=> 3, 'name' => 'Updated Album', 'band' => 'Updated Band')));
    }

    public function delete($id)
    {   // Action used for DELETE requests
        return new JsonModel(array('data' => 'album id 3 deleted'));
    }
}