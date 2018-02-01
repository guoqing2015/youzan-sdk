<?php


namespace Hanson\Youzan;


use Symfony\Component\HttpFoundation\Request;

class Push
{

    /**
     * @var Request
     */
    private $request;
    private $clientId;
    private $secret;

    public function __construct($clientId, $secret, Request $request)
    {
        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function parse()
    {
        $data = $this->request->getContent();
        
        $data = json_decode($data, true);

        if ($data['test'] === true) {
            echo json_encode(['code' => 0, 'msg' => 'success']);
            return;
        }

        $this->checkSign($data);

        $data['msg'] = json_decode(urldecode($data['msg']), true);

        return $data;
    }

    public function checkSign($data)
    {
        $sign = md5($this->clientId.$data['msg'].$this->secret);

        if($sign != $data['sign']){
            throw new YouzanException('签名不正确');
        }
    }

}
