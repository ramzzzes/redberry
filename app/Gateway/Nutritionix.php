<?php


namespace App\Gateway;


use GuzzleHttp\Client;

class Nutritionix
{
    public $params = [];

    public function __construct()
    {
        $this->params['appId'] = env('NUTRITIONIX_APP_ID');
        $this->params['appKey'] = env('NUTRITIONIX_APP_KEY');
    }



    public function request($method = 'POST', $url = '', $params = [])
    {
        $params = array_merge($this->params,$params);

        try {
            $client = new Client();
            $response = $client->request($method, env('NUTRITIONIX_URL') . $url,[
                'json' => $params
            ]);
            $res['code'] = 200;
            $res['data'] = json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            $res['code'] = $e->getCode();
            $res['data'] = urldecode(strip_tags($e->getMessage()));
        }

        return $res;
    }

    public function calculate($text)
    {
        // get most relevant record [order by scoring]
        $data = $this->request('POST','/search',[
            'query' => $text,
            'fields' => [
                'item_name',
                'nf_calories',
            ],
            'sort' => [
                'field' => '_score',
                'order' => 'desc',
            ]
        ]);


        if($data['code'] != 200) {
            return false;
        }

        // empty record
        if(empty($data['data']['hits'])){
            return false;
        }

        return $data['data']['hits'][0]['fields']['nf_calories'];
    }


}
